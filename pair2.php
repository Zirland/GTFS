<?php
include 'header.php';

$action = @$_POST['action'];

switch ($action) {
	case 'nova' :
		$stopzst = $_POST['stopzst'];
		$stopstat = $_POST['stopstat'];
		$stopob = $_POST['stopob'];
		$act = $_POST['active'];
		$stopid = substr($stopstat,-2).$stopzst.substr($stopob,-1);

		$stopname = $_POST['stopname'];
		$stoplat = $_POST['stoplat'];
		$stoplon = $_POST['stoplon'];
		
		$active = 0;
		if ($act == "1") {$active = 1;}

		$prikaz4 = mysqli_query($link, "INSERT INTO stop (stop_id, stop_code, stop_name, stop_desc, stop_lat, stop_lon, zone_id, stop_url, location_type, parent_station, stop_timezone, wheelchair_boarding, active)  VALUES ('$stopid','','$stopname','','$stoplat','$stoplon','','','0','','','0','$active');");
		$prikaz5 = mysqli_query($link, "DELETE FROM kango.loads WHERE ((ZELEZN = '$stopstat') AND (ZST='$stopzst') AND (OB = '$stopob'));");

		$deaktivace = "UPDATE shapetvary SET complete = '0' WHERE (tvartrasy LIKE '%$stop_id%'));";
		$prikaz19 = mysqli_query($link, $deaktivace);

		break;
}

echo "<table>";
echo "<tr>";
echo "<th>Stop ID</th><th>Data name</th><th>Trips count</th><th></th>";
echo "</tr>";

$query = "SELECT * FROM kango.loads WHERE ZELEZN = '0054' ORDER BY pocet ASC LIMIT 1;";
if ($result = mysqli_query($link, $query)) {
	while ($row = mysqli_fetch_row($result)) {
		$ZELEZN = $row[0];
		$ZST = $row[1];
		$OB = $row[2];
		$pocet = $row[3];

		$pom2 = mysqli_fetch_row(mysqli_query($link, "SELECT NAZEVDB FROM kango.DB WHERE ((ZELEZN = '$ZELEZN') AND (ZST='$ZST') AND (OB = $OB));"));
		$stanice = $pom2[0];
		
		echo "<tr>";
		echo "<td>$ZELEZN $ZST $OB</td>";
		echo "<td>$stanice</td>";
		echo "<td>$pocet</td>";
		echo "<td>";
		
		$pom38 = mysqli_fetch_row(mysqli_query($link, "SELECT CISLO7 FROM kango.DTV WHERE ((ZELEZN = '$ZELEZN') AND (ZST='$ZST') AND (OB = $OB)) LIMIT 1;"));
		$cislo7 = $pom38[0];
		$vlak = substr($cislo7,0,-2);
		$lomeni = substr($cislo7,-1);
		$trip_id = $vlak.$lomeni."A";

		echo "<a href=\"detail.php?vl=$vlak\">Užití</a>"; 			

		echo "</td>";
		echo "</tr>";
	}

echo "<tr><td colspan=\"4\">------------------------------</td></tr>";
echo "<tr><td colspan=\"4\">Insert new stop</td></tr>";

echo "<form method=\"post\" action=\"pair2.php\" name=\"nova\">
		<input name=\"action\" value=\"nova\" type=\"hidden\">
		<input name=\"stopzst\" value=\"$ZST\" type=\"hidden\">
		<input name=\"stopstat\" value=\"$ZELEZN\" type=\"hidden\">
		<input name=\"stopob\" value=\"$OB\" type=\"hidden\">";
		
		$stopid = substr($ZELEZN,-2).$ZST.substr($OB,-1);

echo "<tr><td>Stop ID</td><td>Stop name</td><td>Latitude ~50.123456</td><td>Longitude ~16.987654</td></tr>";
echo "<tr><td>$stopid</td><td><input name=\"stopname\" value=\"$stanice\" type=\"text\"></td><td><input name=\"stoplat\" type=\"text\"></td><td><input name=\"stoplon\" type=\"text\"></td></tr>";
echo "<tr><td>Active <input name=\"active\" value=\"1\" type=\"checkbox\"><td colspan=\"3\"><input type=\"submit\" value=\"Insert\"></td></tr>";
echo "<table>";

	mysqli_free_result($result);
}

include 'footer.php';
?>
