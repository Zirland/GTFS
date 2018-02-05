<?php
include 'header.php';

$route_id = $_GET['route'];

$query6 = "SELECT DISTINCT trip_id FROM trip WHERE route_id = '$route_id' AND trip_id NOT LIKE 'F%';"; // ORDER BY CAST(trip_id AS unsigned)
if ($result6 = mysqli_query ($link, $query6)) {
	while ($row6 = mysqli_fetch_row ($result6)) {
		$trip_id = $row6[0];

		$query11 = "SELECT min(stop_sequence), max(stop_sequence) FROM stoptime WHERE trip_id = '$trip_id';";
		if ($result11 = mysqli_query($link, $query11)) {
			$row11 = mysqli_fetch_row ($result11);
			$min = $row11[0];
			$max = $row11[1];
		}

		$query17 = "SELECT min(stop_sequence), max(stop_sequence) FROM stoptime WHERE trip_id = 'F$trip_id';";
		if ($result17 = mysqli_query($link, $query17)) {
			$row17 = mysqli_fetch_row ($result17);
			$fmin = $row17[0];
			$fmax = $row17[1];
		}

		if ($min != $fmin || $max != $fmax) {
			echo "$trip_id <a href=\"tripedit.php?id=$trip_id\" target=\"_blank\">Editace</a> > F$trip_id <a href=\"tripedit.php?id=F$trip_id\" target=\"_blank\">Editace</a><br/>";
		}
	}
}

include 'footer.php';
?>
