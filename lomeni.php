<?php
include 'header.php';

/*
$promazat = mysqli_query($link, "DELETE FROM stoptime WHERE pickup_type = '2' AND stop_sequence = '1';");

$query = "SELECT * FROM GTFS.stoptime WHERE pickup_type='2';";
if ($result = mysqli_query($link, $query)) {
	while ($row = mysqli_fetch_row($result)) {
		$trip_id = $row[0];
		$arrival_time = $row[1];
		$departure_time = $row[2];

		echo "$trip_id : $arrival_time - $departure_time > <a href=\"tripedit.php?id=$trip_id\">Editace</a><br/>";
	}
}
*/

$lastvlak = 0;
$lastmax = 0;

$query19 = "SELECT DISTINCT trip_id FROM trip ORDER BY CAST(trip_id AS unsigned);";
if ($result19 = mysqli_query($link, $query19)) {
	while ($row19 = mysqli_fetch_row($result19)) {
		$trip_id = $row19[0];
		$vlak = substr($trip_id,0,-2);

		$query28 = "SELECT min(stop_sequence), max(stop_sequence) FROM stoptime WHERE trip_id = '$trip_id';";
		if ($result28 = mysqli_query($link, $query28)) {
			while ($row28 = mysqli_fetch_row($result28)) {
				$min = $row28[0];
				$max = $row28[1];

				if ($min != $lastmax && $vlak == $lastvlak) {
					echo "$trip_id > <a href=\"tripedit.php?id=$trip_id\" target=\"_blank\">Editace</a><br/>";
				}
				$lastmax = $max;
				$lastvlak = $vlak;
			}
		}
	}
}



include 'footer.php';
?>
