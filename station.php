<?php
include 'header.php';

$action = @$_POST['action'];
$filtr = @$_POST['filtr'];
$line = @$_POST['line'];
$direct = @$_POST['direct'];

echo "<form method=\"post\" action=\"station.php\" name=\"filtr\">
	<input name=\"action\" value=\"filtr\" type=\"hidden\">";
	echo "<select name=\"filtr\">";
	$query0 = "SELECT stop_id, stop_name FROM stop ORDER BY stop_name;";
	if ($result0 = mysqli_query($link, $query0)) {
		while ($row0 = mysqli_fetch_row($result0)) {
			$kod = $row0[0];
			$nazev = $row0[1];

			echo "<option value=\"$kod\"";
			if ($kod == $filtr) {echo " SELECTED";}
			echo ">$nazev</option>";
		}
		mysqli_free_result($result0);
	}	
	echo "</select><select name=\"line\">";

	$query1 = "SELECT route_id, route_short_name FROM route ORDER BY route_short_name;";
	if ($result1 = mysqli_query($link, $query1)) {
		while ($row1 = mysqli_fetch_row($result1)) {
			$rt_kod = $row1[0];
			$rt_nazev = $row1[1];

			echo "<option value=\"$rt_kod\"";
			if ($rt_kod == $line) {echo " SELECTED";}
			echo ">$rt_nazev</option>";
		}
		mysqli_free_result($result1);
	}
	echo "</select>";
	echo "<input name=\"direct\" value=\"1\">";
	echo "<input type=\"submit\"></form>";

switch ($action) {
	case "filtr" : 

echo  "<table border=\"1\">";
echo "<tr>";
echo "<th>Vlak</th>
	<th>Název</th>
	<th>Dopravce</th>
	<th>Linka</th>
	<th>Čas</th>
	<th>Cílová stanice</th>
	<th>Poznámka</th>";
echo "</tr>";

$x = 0;

$query = "SELECT trip_id FROM stoptime WHERE (stop_id='$filtr') ORDER BY departure_time;";
if ($result = mysqli_query($link, $query)) {
	while ($row = mysqli_fetch_row($result)) {
		$trip_id = $row[0];


		$pom0 = mysqli_fetch_row(mysqli_query($link, "SELECT * FROM trip WHERE (trip_id = '$trip_id');"));
		$routedata = $pom0[0];
		$trip_headsign = $pom0[3];
		$direction = $pom0[5];
		$wheel = $pom0[8];
		$bike = $pom0[9];
		$jmeno = $pom0[4];


		$cislo = substr($trip_id, 0, -2);
		$lomeni = substr($cislo, -1);
		$cislo7 = $cislo."/".$lomeni;

		$pom1 = mysqli_fetch_row(mysqli_query($link, "SELECT IDDOP FROM kango.HLV WHERE (CISLO7='$cislo7');"));
		$iddop = $pom1[0];
		
		$pom2 = mysqli_fetch_row(mysqli_query($link, "SELECT ZKRATKA FROM kango.DOP WHERE (IDDOP='$iddop');"));				
		$zkratka = $pom2[0];
		
		if ($routedata == $line && $direction == $direct) {		
		echo "<tr>";
		echo "<td>$cislo</td>";
		echo "<td>$jmeno</td><td>$zkratka</td>";
		$row8 = mysqli_fetch_row(mysqli_query($link, "SELECT route_short_name,route_color,route_text_color FROM route WHERE (route_id = $routedata)ORDER BY route_short_name;"));
		$route_short_name = $row8[0];
		$route_color = $row8[1];
		$route_text_color = $row8[2];
		echo "<td style=\"background-color: #$route_color; text-align: center;\"><span style=\"color: #$route_text_color;\">$route_short_name</td><td>";
		
		$pom94 = mysqli_fetch_row(mysqli_query($link, "SELECT * FROM stoptime WHERE ((trip_id = '$trip_id') AND (stop_id = '$filtr'));"));
		$zde = $pom94[2];
		
		$zde = substr($zde,0,5);
		echo $zde;
		echo "</td>";
		
        $pom92 = mysqli_fetch_row(mysqli_query($link, "SELECT * FROM stoptime WHERE (trip_id = '$trip_id') ORDER BY stop_sequence DESC LIMIT 1;"));
        $clzst = $pom92[3];
        $pom93 = mysqli_fetch_row(mysqli_query($link, "SELECT stop_name FROM stop WHERE (stop_id = '$clzst');"));
        $kam = $pom93[0];

		echo "<td>$kam</td>";
		echo"<td><a href=\"detail.php?vl=$cislo\">Detaily</a></td>";
		$x = $x+1;
		echo "</tr>";
}
	}
	mysqli_free_result($result);
}
echo "<table>";
break;
}

include 'footer.php';
?>
