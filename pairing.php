<?php
include 'header.php';

$action = @$_POST['action'];
$a = $_GET['a'];

if ($a == 'skip') {
	$stpid = $_GET['z'];
	$prikaz0 = mysqli_query($link, "UPDATE kango.loads SET pocet = pocet * -1 WHERE (ZST = $stpid);");
}	

switch ($action) {
	case 'paruj' :
		$linked = $_POST['linked'];
		$stopid = $_POST['stopid'];
		$query2 = "SELECT * FROM kango.zastavky WHERE (stop_id = '$linked');";
		if ($result2 = mysqli_query($link, $query2)) {
			while ($row2 = mysqli_fetch_row($result2)) {
				$stop_name = $row2[2];
				$stop_desc = $row2[3];
				$stop_lat = $row2[4];
				$stop_lon = $row2[5];
				$location_type = $row2[8];
				$wheelchair_boarding = $row2[11];

				$prikaz1 = mysqli_query($link, "INSERT INTO stop (stop_id, stop_code, stop_name, stop_desc, stop_lat, stop_lon, zone_id, stop_url, location_type, parent_station, stop_timezone, wheelchair_boarding, active) VALUES ('$stopid','','$stop_name','$stop_desc','$stop_lat','$stop_lon','','','$location_type','','','$wheelchair_boarding','0');");
				$prikaz2 = mysqli_query($link, "DELETE FROM kango.zastavky WHERE (stop_id = '$linked');");
				$prikaz3 = mysqli_query($link, "DELETE FROM kango.loads WHERE (ZST = '$stopid');");
			}
		mysqli_free_result($result2);
		}
	break;
	
	/*case 'nova' :
		$stopid = $_POST['stopid'];
		$stopname = $_POST['stopname'];
		$stoplat = $_POST['stoplat'];
		$stoplon = $_POST['stoplon'];
		
		$prikaz4 = mysqli_query($link, "INSERT INTO stop (stop_id, stop_code, stop_name, stop_desc, stop_lat, stop_lon, zone_id, stop_url, location_type, parent_station, stop_timezone, wheelchair_boarding, active)  VALUES ('$stopid','','$stopname','','$stoplat','$stoplon','','','0','','','0','0');");
		$prikaz5 = mysqli_query($link, "DELETE FROM kango.loads WHERE (ZST = '$stopid');");
		break;*/
}

echo "<table>";
echo "<tr>";
echo "<th>Stop ID</th><th>Data name</th><th>Trips count</th><th></th>";
echo "</tr>";

$query = "SELECT * FROM kango.loads ORDER BY pocet DESC LIMIT 1;";
if ($result = mysqli_query($link, $query)) {
	while ($row = mysqli_fetch_row($result)) {
		$ZST = $row[0];
		$pocet = $row[1];

		$pom2 = mysqli_fetch_row(mysqli_query($link, "SELECT NAZEVDB FROM kango.DB WHERE (ZST='$ZST');"));
		$stanice = $pom2[0];
		
		echo "<tr>";
		echo "<td>$ZST</td>";
		echo "<td>$stanice</td>";
		echo "<td>$pocet</td>";
		echo "<td><a href=\"pairing.php?a=skip&z=$ZST\">Skip this stop</a></td>";
		echo "</tr>";
	
echo "<form method=\"post\" action=\"pairing.php\" name=\"paruj\">
		<input name=\"action\" value=\"paruj\" type=\"hidden\">
		<input name=\"stopid\" value=\"$ZST\" type=\"hidden\">";
		
		echo "<tr><td colspan=\"4\">PLEASE FIND THE STATION WITH SAME NAME: ";
		echo "<select name=\"linked\"><option value=\"\">---</option>";

$query1 = "SELECT * FROM kango.zastavky ORDER BY stop_name;";
if ($result1 = mysqli_query($link, $query1)) {
	while ($row1 = mysqli_fetch_row($result1)) {
		$stop_id = $row1[0];
		$stop_name = $row1[2];
		
		echo "<option value=\"$stop_id\">$stop_name</option>";
	}
	mysqli_free_result($result1);
}
		echo "</select><input type=\"submit\" value=\"Submit\"></form></td></tr>";

}
/*
echo "<tr><td colspan=\"4\">------------------------------</td></tr>";
echo "<tr><td colspan=\"4\">Insert new stop</td></tr>";

echo "<form method=\"post\" action=\"pairing.php\" name=\"nova\">
		<input name=\"action\" value=\"nova\" type=\"hidden\">
		<input name=\"stopid\" value=\"$ZST\" type=\"hidden\">";

echo "<tr><td>Stop ID</td><td>Stop name</td><td>Latitude ~50.123456</td><td>Longitude ~16.987654</td></tr>";
echo "<tr><td>$ZST</td><td><input name=\"stopname\" value=\"$stanice\" type=\"text\"></td><td><input name=\"stoplat\" type=\"text\"></td><td><input name=\"stoplon\" type=\"text\"></td></tr>";
echo "<tr><td colspan=\"4\"><input type=\"submit\" value=\"Insert\"></td></tr>";*/
echo "<table>";

	mysqli_free_result($result);
}

include 'footer.php';
?>
