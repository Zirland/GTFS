<?php
include 'header.php';

$routes = "SELECT route_id FROM route WHERE route_long_name NOT LIKE '%–%' LIMIT 20;";
if ($routeresult = mysqli_query ($link, $routes)) {
	while ($routerow = mysqli_fetch_row ($routeresult)) {
		$route = $routerow[0];

		$cislo7 = substr($route,1)."/".substr($route,-1);
		echo "$cislo7: ";

		$query9 = mysqli_fetch_row (mysqli_query ($link, "SELECT JMENVL from kango.HLV WHERE cislo7='$cislo7';"));
		$route_long_name = $query9[0];

		$query_min = mysqli_fetch_row (mysqli_query ($link, "SELECT min(stop_sequence) FROM stoptime WHERE trip_id IN (SELECT trip_id FROM trip WHERE route_id='$route');"));
		$min = $query_min[0];

		$query_min_id = mysqli_fetch_row (mysqli_query ($link, "SELECT stop_id FROM stoptime WHERE stop_sequence=$min AND trip_id IN (SELECT trip_id FROM trip WHERE route_id='$route');"));
		$min_id = $query_min_id[0];
		$pomminstopparent=mysqli_fetch_row (mysqli_query ($link, "SELECT parent_station FROM stop WHERE stop_id='$min_id';"));
		$minstopparent=$pomminstopparent[0];
		if ($minstopparent == '') {
			$minstopid = $min_id;
		} else {
			$minstopid = $minstopparent;
		}
		$query_min_name = mysqli_fetch_row (mysqli_query ($link, "SELECT stop_name FROM stop WHERE stop_id='$minstopid';"));
		$min_name = $query_min_name[0];

		$query_max = mysqli_fetch_row (mysqli_query ($link, "SELECT max(stop_sequence) FROM stoptime WHERE trip_id IN (SELECT trip_id FROM trip WHERE route_id='$route');"));
		$max = $query_max[0];

		$query_max_id = mysqli_fetch_row (mysqli_query ($link, "SELECT stop_id FROM stoptime WHERE stop_sequence=$max AND trip_id IN (SELECT trip_id FROM trip WHERE route_id='$route');"));
		$max_id = $query_max_id[0];
		$pommaxstopparent=mysqli_fetch_row (mysqli_query ($link, "SELECT parent_station FROM stop WHERE stop_id='$max_id';"));
		$maxstopparent=$pommaxstopparent[0];
		if ($maxstopparent == '') {
			$maxstopid = $max_id;
		} else {
			$maxstopid = $maxstopparent;
		}
		$query_max_name = mysqli_fetch_row (mysqli_query ($link, "SELECT stop_name FROM stop WHERE stop_id='$maxstopid';"));
		$max_name = $query_max_name[0];

		if ($route_long_name != '') {
			$wholename = "$route_long_name | $min_name – $max_name";
		} else {
			$wholename = "$min_name – $max_name";
		}
		echo "$wholename<br />";

		$query364 = "UPDATE route SET route_long_name='$wholename' WHERE route_id='$route';";
		$command364 = mysqli_query ($link, $query364);
	}
}

include 'footer.php';
?>