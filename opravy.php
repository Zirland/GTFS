<?php
include 'header.php';

echo "HEADSIGN<br/>";
$query1 = "SELECT trip_id FROM trip WHERE trip_headsign='';";
if ($result1 = mysqli_query ($link, $query1)) {
	while ($row1 = mysqli_fetch_row ($result1)) {
		$trip_id = $row1[0];

		echo "<a href=\"tripedit.php?id=$trip_id\">$trip_id</a><br/>";
	}
}

echo "NO STOP<br/>";
$query2 = "SELECT trip_id FROM trip WHERE trip_id NOT IN (SELECT DISTINCT trip_id FROM stoptime);";
if ($result2 = mysqli_query ($link, $query2)) {
	while ($row2 = mysqli_fetch_row ($result2)) {
		$trip_id = $row2[0];

		echo "<a href=\"tripedit.php?id=$trip_id\">$trip_id</a><br/>";

		$smazat = mysqli_query ($link, "DELETE FROM trip WHERE trip_id='$trip_id';");
	}
}

echo "ONE STOP<br/>";
$query3 = "SELECT trip_id FROM (SELECT trip_id, count(*) AS pocet FROM stoptime GROUP BY trip_id) AS pomoc WHERE pocet=1;";
if ($result3 = mysqli_query ($link, $query3)) {
	while ($row3 = mysqli_fetch_row ($result3)) {
		$trip_id = $row3[0];
		
		echo "<a href=\"tripedit.php?id=$trip_id\">$trip_id</a><br/>";

		$smazat1 = mysqli_query ($link, "DELETE FROM trip WHERE trip_id='$trip_id';");
		$smazat2 = mysqli_query ($link, "DELETE FROM stoptime WHERE trip_id = '$trip_id';");
	}
}

echo "NO TRIP<br/>";
$query4 = "SELECT route_id FROM route WHERE active=1 AND route_id NOT IN (SELECT DISTINCT route_id FROM trip);";
if ($result4 = mysqli_query ($link, $query4)) {
	while ($row4 = mysqli_fetch_row ($result4)) {
		$route_id = $row4[0];
		
		echo "<a href=\"routeedit.php?id=$route_id\">$route_id</a><br/>";

		$deaktivace = mysqli_query ($link, "UPDATE route SET active=0 WHERE route_id='$route_id';");
	}
}

echo "INACTIVE<br/>";
$query5 = "SELECT route_id FROM route WHERE active=0 AND route_id IN (SELECT DISTINCT route_id FROM trip WHERE active=1);";
if ($result5 = mysqli_query ($link, $query5)) {
	while ($row5 = mysqli_fetch_row ($result5)) {
		$route_id = $row5[0];
		
		echo "<a href=\"routeedit.php?id=$route_id\">$route_id</a><br/>";

		$aktivace = mysqli_query ($link, "UPDATE route SET active=1 WHERE route_id='$route_id';");
	}
}

include 'footer.php';
?>