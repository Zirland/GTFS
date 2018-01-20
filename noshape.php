<?php
include 'header.php';

$query = "SELECT trip_id FROM GTFS.trip WHERE shape_id='';";
if ($result = mysqli_query ($link, $query)) {
	while ($row = mysqli_fetch_row ($result)) {
		$trip_id = $row[0];

		echo "$trip_id : <a href=\"tripedit.php?id=$trip_id\">Editace</a><br/>";
	}
}

include 'footer.php';
?>