<?php
include 'header.php';

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


include 'footer.php';
?>
