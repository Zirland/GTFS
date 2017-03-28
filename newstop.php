<?php
include 'header.php';

$action = @$_POST['action'];

switch ($action) {
	case 'nova' :
		$stopid = $_POST['stopid'];
		$stopname = $_POST['stopname'];
		$stoplat = $_POST['stoplat'];
		$stoplon = $_POST['stoplon'];
		
		$prikaz4 = mysqli_query($link, "INSERT INTO stop (stop_id, stop_code, stop_name, stop_desc, stop_lat, stop_lon, zone_id, stop_url, location_type, parent_station, stop_timezone, wheelchair_boarding, active)  VALUES ('$stopid','','$stopname','','$stoplat','$stoplon','','','0','','','0','0');");
		break;
}

echo "<table>";
echo "<tr><td colspan=\"4\">Insert new stop</td></tr>";

echo "<form method=\"post\" action=\"newstop.php\" name=\"nova\">
		<input name=\"action\" value=\"nova\" type=\"hidden\">";
		

echo "<tr><td>Stop ID</td><td>Stop name</td><td>Latitude ~50.123456</td><td>Longitude ~16.987654</td></tr>";
echo "<tr><td><input type=\"text\" name=\"stopid\"></td><td><input name=\"stopname\" value=\"\" type=\"text\"></td><td><input name=\"stoplat\" type=\"text\"></td><td><input name=\"stoplon\" type=\"text\"></td></tr>";
echo "<tr><td colspan=\"4\"><input type=\"submit\" value=\"Insert\"></td></tr>";
echo "<table>";


include 'footer.php';
?>
