<?php
include 'header.php';

$action = @$_POST['action'];
$a = $_GET['a'];

switch ($a) {
	case 'skip' :
		$ZELEZN = $_GET['s'];
		$ZST = $_GET['z'];
		$OB = $_GET['o'];
		$prikaz0 = mysqli_query($link, "UPDATE kango.loads SET pocet = pocet * -1 WHERE ((ZELEZN = '$ZELEZN') AND (ZST='$ZST') AND (OB = '$OB'));");
		break;
		
	case 'unlink' :
		$stpid = $_GET['z'];
		$prikaz0 = "UPDATE stop SET stop_id = '0000' WHERE stop_id = '$stpid';";
		$proved0 = mysqli_query($link, $prikaz0);
}	

switch ($action) {
	case 'potvrd' :
		$linked = $_POST['linked'];
		$stopzst = $_POST['stopzst'];
		$stopstat = $_POST['stopstat'];
		$stopob = $_POST['stopob'];
		$stopid = substr($stopstat,-2).$stopzst.substr($stopob,-1);

		$query80 = mysqli_fetch_row(mysqli_query($link, "UPDATE stop SET stop_id='$stopid' WHERE stop_id = '$linked';"));
		$prikaz5 = mysqli_query($link, "DELETE FROM kango.loads WHERE ((ZELEZN = '$stopstat') AND (ZST='$stopzst') AND (OB = '$stopob'));");
	break;
	
	case 'paruj' :
		$linked = $_POST['linked'];
		$stopzst = $_POST['stopzst'];
		$stopstat = $_POST['stopstat'];
		$stopob = $_POST['stopob'];
		$stopid = substr($stopstat,-2).$stopzst.substr($stopob,-1);
		
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
				$prikaz3 = mysqli_query($link, "DELETE FROM kango.loads WHERE ((ZELEZN = '$stopstat') AND (ZST='$stopzst') AND (OB = '$stopob'));");
				
				$deaktivace = "UPDATE shapetvary SET complete = '0' WHERE (tvartrasy LIKE '%$stop_id%'));";
				$prikaz19 = mysqli_query($link, $deaktivace);

			}
		mysqli_free_result($result2);
		}
	break;

}

echo "<table>";
echo "<tr>";
echo "<th>Stop ID</th><th>Data name</th><th>Trips count</th><th></th>";
echo "</tr>";

$query = "SELECT * FROM kango.loads ORDER BY pocet DESC LIMIT 1;";
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
		echo "<td><a href=\"pairing.php?a=skip&s=$ZELEZN&z=$ZST&o=$OB\">Skip this stop</a></td>";
		echo "</tr>";
	
echo "<form method=\"post\" action=\"pairing.php\" name=\"potvrd\">
		<input name=\"action\" value=\"potvrd\" type=\"hidden\">
		<input name=\"stopzst\" value=\"$ZST\" type=\"hidden\">
		<input name=\"stopstat\" value=\"$ZELEZN\" type=\"hidden\">
		<input name=\"stopob\" value=\"$OB\" type=\"hidden\">";
		
		echo "<tr><td colspan=\"4\">1. PLEASE CONFIRM THE PAIRING: ";

		$query80 = mysqli_fetch_row(mysqli_query($link, "SELECT stop_id, stop_name FROM stop WHERE stop_id = '$ZST';"));
		$stop_id = $query80[0];
		$stop_name = $query80[1];
		
		echo "<INPUT name=\"linked\" type=\"hidden\" value=\"$stop_id\"> <b>$stop_name</b>";
	}
}
		echo "</select><input type=\"submit\" value=\"The same stop\"></form>
		<a href=\"pairing.php?a=unlink&z=$stop_id\">Pairing is incorrect</a></td></tr>";




echo "<form method=\"post\" action=\"pairing.php\" name=\"paruj\">
		<input name=\"action\" value=\"paruj\" type=\"hidden\">
		<input name=\"stopzst\" value=\"$ZST\" type=\"hidden\">
		<input name=\"stopstat\" value=\"$ZELEZN\" type=\"hidden\">
		<input name=\"stopob\" value=\"$OB\" type=\"hidden\">";
		
		echo "<tr><td colspan=\"4\">2. OR FIND THE STATION WITH SAME NAME: ";
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

echo "<table>";

	mysqli_free_result($result);

include 'footer.php';
?>
