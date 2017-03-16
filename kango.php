<?php
include 'header.php';

$action = @$_POST['action'];
$fltr = @$_POST['filtr'];

echo "<form method=\"post\" action=\"kango.php\" name=\"filtr\">
	<input name=\"action\" value=\"filtr\" type=\"hidden\">";
	echo "<select name=\"filtr\">";
	$query0 = "SELECT stop_id, stop_name FROM stop ORDER BY stop_name;";
	if ($result0 = mysqli_query($link, $query0)) {
		while ($row0 = mysqli_fetch_row($result0)) {
			$kod = $row0[0];
			$nazev = $row0[1];

			echo "<option value=\"$kod\"";
			if ($kod == $fltr) {echo " SELECTED";}
			echo ">$nazev</option>";
		}
		mysqli_free_result($result0);
	} else echo("Error description: " . mysqli_error($link));
	
	echo "</select>";
	echo "<input type=\"submit\"></form>";

switch ($action) {
	case "edit" :
	
	$tripid0 = $_POST['trip0'];
	$routeid0 = $_POST['linka0'];
	$hsign0 = $_POST['head0'];
	$smery0 = $_POST['dir0'];
	$omezeni = $_POST['omez'];

	$ready0 = "UPDATE trip SET route_id='$routeid0', trip_headsign='$hsign0', direction_id='$smery0' WHERE (trip_id = '$tripid0');";
	$aktualz0 = mysqli_query($link, $ready0);
	
	$tripid1 = $_POST['trip1'];
	$routeid1 = $_POST['linka1'];
	$hsign1 = $_POST['head1'];
	$smery1 = $_POST['dir1'];

	$ready1 = "UPDATE trip SET route_id='$routeid1', trip_headsign='$hsign1', direction_id='$smery1' WHERE (trip_id = '$tripid1');";
	$aktualz1 = mysqli_query($link, $ready1);
	
	$tripid2 = $_POST['trip2'];
	$routeid2 = $_POST['linka2'];
	$hsign2 = $_POST['head2'];
	$smery2 = $_POST['dir2'];

	$ready2 = "UPDATE trip SET route_id='$routeid2', trip_headsign='$hsign2', direction_id='$smery2' WHERE (trip_id = '$tripid2');";
	$aktualz2 = mysqli_query($link, $ready2);
	
	$tripid3 = $_POST['trip3'];
	$routeid3 = $_POST['linka3'];
	$hsign3 = $_POST['head3'];
	$smery3 = $_POST['dir3'];

	$ready3 = "UPDATE trip SET route_id='$routeid3', trip_headsign='$hsign3', direction_id='$smery3' WHERE (trip_id = '$tripid3');";
	$aktualz3 = mysqli_query($link, $ready3);
	
	$tripid4 = $_POST['trip4'];
	$routeid4 = $_POST['linka4'];
	$hsign4 = $_POST['head4'];
	$smery4 = $_POST['dir4'];

	$ready4 = "UPDATE trip SET route_id='$routeid4', trip_headsign='$hsign4', direction_id='$smery4' WHERE (trip_id = '$tripid4');";
	$aktualz4 = mysqli_query($link, $ready4);
	
	$tripid5 = $_POST['trip5'];
	$routeid5 = $_POST['linka5'];
	$hsign5 = $_POST['head5'];
	$smery5 = $_POST['dir5'];

	$ready5 = "UPDATE trip SET route_id='$routeid5', trip_headsign='$hsign5', direction_id='$smery5' WHERE (trip_id = '$tripid5');";
	$aktualz5 = mysqli_query($link, $ready5);
	
	$tripid6 = $_POST['trip6'];
	$routeid6 = $_POST['linka6'];
	$hsign6 = $_POST['head6'];
	$smery6 = $_POST['dir6'];

	$ready6 = "UPDATE trip SET route_id='$routeid6', trip_headsign='$hsign6', direction_id='$smery6' WHERE (trip_id = '$tripid6');";
	$aktualz6 = mysqli_query($link, $ready6);
	
	$tripid7 = $_POST['trip7'];
	$routeid7 = $_POST['linka7'];
	$hsign7 = $_POST['head7'];
	$smery7 = $_POST['dir7'];

	$ready7 = "UPDATE trip SET route_id='$routeid7', trip_headsign='$hsign7', direction_id='$smery7' WHERE (trip_id = '$tripid7');";
	$aktualz7 = mysqli_query($link, $ready7);
	
	$tripid8 = $_POST['trip8'];
	$routeid8 = $_POST['linka8'];
	$hsign8 = $_POST['head8'];
	$smery8 = $_POST['dir8'];

	$ready8 = "UPDATE trip SET route_id='$routeid8', trip_headsign='$hsign8', direction_id='$smery8' WHERE (trip_id = '$tripid8');";
	$aktualz8 = mysqli_query($link, $ready8);
	
	$tripid9 = $_POST['trip9'];
	$routeid9 = $_POST['linka9'];
	$hsign9 = $_POST['head9'];
	$smery9 = $_POST['dir9'];

	$ready9 = "UPDATE trip SET route_id='$routeid9', trip_headsign='$hsign9', direction_id='$smery9' WHERE (trip_id = '$tripid9');";
	$aktualz9 = mysqli_query($link, $ready9);
		
	$action = "filtr";
	$fltr = $omezeni;


	case "filtr" : 

echo  "<table border=\"1\">";
echo "<tr>";
echo "<th>ID trasy</th>
	<th>Název</th>
	<th>Dopravce</th>
	<th>Linka</th>
	<th>Poznámky</th>
	<th>Výchozí stanice</th>
	<th>Cílová stanice</th>
	<th>Směr</th>
	<th></th>";	
echo "</tr>";

$x = 0;

		echo "<form method=\"post\" action=\"kango.php\" name=\"edit\">
		<input name=\"action\" value=\"edit\" type=\"hidden\">
		<input name=\"omez\" value=\"$fltr\" type=\"hidden\">";


$query = "SELECT trip_id FROM stoptime WHERE (stop_id='$fltr') ORDER BY departure_time;";
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
		
		if ($routedata == 0) {		
		echo "<tr>";
		echo "<td><input type=\"hidden\" name=\"trip$x\" value=\"$trip_id\">$trip_id</td>";
		echo "<td>$jmeno</td><td>$zkratka</td><td>";
		echo "<select name=\"linka$x\"><option value=\"\">----------</option>";
		$query8 = "SELECT * FROM route ORDER BY route_short_name;";
		if ($result8 = mysqli_query($link, $query8)) {
			while ($row8 = mysqli_fetch_row($result8)) {
				$route_id = $row8[0];
				$route_short_name = $row8[2];
				$route_long_name = $row8[3];

				echo "<option value=\"$route_id\"";
				if ($routedata == $route_id) {echo " SELECTED";}
				echo ">$route_short_name: $route_long_name</option>";
			}
		}
		echo "</td><td>";
		
		$query1 = "SELECT POZNAM FROM kango.OBP WHERE ((CISLO7='$cislo7') AND (POZNAM LIKE '%linka%'));";
		if ($result1 = mysqli_query($link, $query1)) {
			while ($row1 = mysqli_fetch_row($result1)) {
				$poznamka = $row1[0];
				
				echo "$poznamka<br />";
			}
		}
		$pom94 = mysqli_fetch_row(mysqli_query($link, "SELECT * FROM stoptime WHERE ((trip_id = '$trip_id') AND (stop_id = '$fltr'));"));
		$zde = $pom94[2];
		echo $zde;
		echo "</td>";
		
		$pom9 = mysqli_fetch_row(mysqli_query($link, "SELECT * FROM stoptime WHERE (trip_id = '$trip_id') ORDER BY stop_sequence ASC LIMIT 1;"));
		$stzst = $pom9[3];
		$odjzd = $pom9[2];
		$pom91 = mysqli_fetch_row(mysqli_query($link, "SELECT stop_name FROM stop WHERE (stop_id = '$stzst');"));
		$odkud = $pom91[0];

                $pom92 = mysqli_fetch_row(mysqli_query($link, "SELECT * FROM stoptime WHERE (trip_id = '$trip_id') ORDER BY stop_sequence DESC LIMIT 1;"));
                $clzst = $pom92[3];
                $prjzd = $pom92[2];
                $pom93 = mysqli_fetch_row(mysqli_query($link, "SELECT stop_name FROM stop WHERE (stop_id = '$clzst');"));
                $kam = $pom93[0];

		echo "<td>$odkud<br />$odjzd</td><td><input type=\"text\" name=\"head$x\" value=\"$kam\"><br />$prjzd</td>";
		echo "<td><select name=\"dir$x\"><option value=\"0\"";
		if ($direction=='0') {echo " SELECTED";}
		echo ">Odchozí</option><option value=\"1\"";
		if ($direction=='1') {echo " SELECTED";}
		echo ">Příchozí</td>";
		echo"</td><td><a href=\"detail.php?vl=$cislo\">Detaily</a></td>";
		$x = $x+1;
		echo "</tr>";
}
	}
	mysqli_free_result($result);
}
echo "<input type=\"submit\"></form>";
echo "<table>";
break;
}

include 'footer.php';
?>
