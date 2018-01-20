<?php
$link = mysqli_connect ('localhost', 'gtfs', 'gtfs', 'GTFS');
if (!$link) {
	echo "Error: Unable to connect to MySQL.".PHP_EOL;
	echo "Debugging errno: ".mysqli_connect_errno ().PHP_EOL;
	exit;
}

$file = 'linemap.csv';
$current = "route_short_name,trip_id,lat,lon,seq\n";
file_put_contents ($file, $current);

$akt_route = "SELECT route_id,route_short_name,kraj FROM route WHERE (active='1' AND route_id NOT LIKE 'L%');";
if ($result69 = mysqli_query ($link, $akt_route)) {
	while ($row69 = mysqli_fetch_row ($result69)) {
		$route_id = $row69[0];
		$route_short_name = $row69[1];
		$kraj = $row69[2];

		$akt_trip = "SELECT trip_id,shape_id FROM trip WHERE ((route_id = '$route_id') AND (active='1'));";
		if ($result85 = mysqli_query ($link, $akt_trip)) {
			while ($row85 = mysqli_fetch_row ($result85)) {
				$trip_id = $row85[0];
				$shape_tvar = $row85[1];

				$i = 0;

				$output = explode ('|', $shape_tvar);

				foreach ($output as $prujbod) {
					$pom139 = mysqli_fetch_row (mysqli_query ($link, "SELECT stop_name,stop_lat,stop_lon FROM stop WHERE (stop_id='$prujbod');"));
					$name = $pom139[0];
					$lat = $pom139[1];
					$lon = $pom139[2];
					$i = $i + 1;

					if ($lat != '' && $lon != '') {
						$current = "$route_short_name$kraj, $trip_id, $lat, $lon, $i\n";
						file_put_contents ($file, $current, FILE_APPEND);
					}
				}
			}
		}
	}
}

mysqli_close($link);
?>
