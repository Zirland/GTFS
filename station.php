<?php
include 'header.php';

$action = @$_POST['action'];
$filtr = @$_POST['filtr'];

echo "<form method=\"post\" action=\"station.php\" name=\"filtr\">
	<input name=\"action\" value=\"filtr\" type=\"hidden\">";
	echo "<select name=\"filtr\">";
	$query0 = "SELECT stop_id, stop_name FROM stop WHERE active=1 ORDER BY stop_name;";
	if ($result0 = mysqli_query ($link, $query0)) {
		while ($row0 = mysqli_fetch_row ($result0)) {
			$kod = $row0[0];
			$nazev = $row0[1];

			echo "<option value=\"$kod\"";
			if ($kod == $filtr) {
				echo " SELECTED";
			}
			echo ">$nazev</option>";
		}
		mysqli_free_result($result0);
	}
	echo "</select><select name=\"line\">";
	echo "<input type=\"submit\"></form>";

switch ($action) {
	case "update" :
		$pocet = $_POST['pocet'];

		for ($y = 0; $y < $pocet; $y++) {
			$$ind = $y;
			$numtripindex = "spoj".${$ind};
			$numtrip = $_POST[$numtripindex];
			$newlineindex = "line".${$ind};
			$newline = $_POST[$newlineindex];

			$query30 = "UPDATE trip SET route_id = '$newline' WHERE trip_id = '$numtrip';";
			$prikaz30 = mysqli_query ($link, $query30);
		}
		$action="filtr";
	break;

	case "filtr" : 
		echo "<table border=\"1\">";
		echo "<tr>";
		echo "<th>Vlak</th>
		<th>Dopravce</th>
		<th>Linka</th>
		<th>Čas</th>
		<th>Cílová stanice</th>
		<th>Poznámka</th>";
		echo "</tr>";

		$x = 0;
		$now = date ("H:i:s", time ());
		$end = date ("H:i:s", time ()+3600);

		$query = "SELECT trip_id FROM stoptime WHERE stop_id='$filtr' ORDER BY departure_time;";
		echo $query;
		if ($result = mysqli_query ($link, $query)) {
			while ($row = mysqli_fetch_row ($result)) {
				$trip_id = $row[0];

				$pom0 = mysqli_fetch_row (mysqli_query ($link, "SELECT * FROM trip WHERE (trip_id = '$trip_id');"));
				$routedata = $pom0[0];
				$trip_headsign = $pom0[3];
				$wheel = $pom0[8];
				$bike = $pom0[9];
				$jmeno = $pom0[4];

				$cislo = substr ($trip_id,0,-2);
				$lomeni = substr ($cislo,-1);
				$cislo7 = $cislo."/".$lomeni;

				$pom2 = mysqli_fetch_row (mysqli_query ($link, "SELECT ZKRATKA FROM kango.DOP WHERE IDDOP IN (SELECT IDDOP FROM kango.HLV WHERE CISLO7='$cislo7');"));
				$zkratka = $pom2[0];
				if (strpos ($routedata, 'L') !== false) {
					echo "<form method=\"post\" action=\"station.php\" name=\"update\"><input name=\"action\" value=\"update\" type=\"hidden\"><input name=\"filtr\" value=\"$filtr\" type=\"hidden\"><input name=\"spoj$x\" value=\"$trip_id\" type=\"hidden\">";
					echo "<tr>";
					echo "<td>$cislo</td>";
					echo "<td>$zkratka</td>";
					$row8 = mysqli_fetch_row (mysqli_query ($link, "SELECT route_short_name,route_color,route_text_color FROM route WHERE (route_id = '$routedata') ORDER BY route_short_name;"));
					$route_short_name = $row8[0];
					$route_color = $row8[1];
					$route_text_color = $row8[2];
					echo "<td style=\"background-color: #$route_color; text-align: center;\"><span style=\"color: #$route_text_color;\">";
					echo "<select name=\"line$x\">";
					$query84 = "SELECT route_id, route_short_name, kraj FROM route ORDER BY route_short_name;"; //WHERE route_id NOT LIKE 'L%' 
					if ($result84 = mysqli_query ($link, $query84)) {
						while ($row84 = mysqli_fetch_row ($result84)) {
							$route_id = $row84[0];
							$route_short_name = $row84[1];
							$kraj = $row84[2];

							echo "<option value=\"$route_id\"";
							if ($route_id == $routedata) {
								echo " SELECTED";
							}
							echo ">$route_short_name$kraj</option>";
						}
					}
					echo "</select></td><td>";

					$pom94 = mysqli_fetch_row (mysqli_query ($link, "SELECT * FROM stoptime WHERE ((trip_id = '$trip_id') AND (stop_id = '$filtr'));"));
					$zde = $pom94[2];
					$zde = substr ($zde,0,5);
					echo $zde;
					echo "</td>";

					$pom92 = mysqli_fetch_row (mysqli_query ($link, "SELECT * FROM stoptime WHERE (trip_id = '$trip_id') ORDER BY stop_sequence DESC LIMIT 1;"));
					$clzst = $pom92[3];
					$pom93 = mysqli_fetch_row (mysqli_query ($link, "SELECT stop_name FROM stop WHERE (stop_id = '$clzst');"));
					$kam = $pom93[0];

					echo "<td>$kam</td>";
					echo"<td></td>";
					$x = $x + 1;
					echo "</tr>";
				}
			}
		}
		echo "</table>";
		mysqli_free_result($result);

		echo "<input type=\"hidden\" name=\"pocet\" value=\"$x-1\">";
		echo "<input type=\"submit\">";
		echo "</form>";
	break;
}

include 'footer.php';
?>