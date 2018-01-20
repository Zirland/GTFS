<?php
include 'header.php';

$lastvlak = 0;
$lastmax = 0;

$query19 = "SELECT DISTINCT trip_id FROM trip ORDER BY CAST(trip_id AS unsigned);";
if ($result19 = mysqli_query ($link, $query19)) {
	while ($row19 = mysqli_fetch_row ($result19)) {
		$trip_id = $row19[0];
		$vlak = substr ($trip_id,0,-2);

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