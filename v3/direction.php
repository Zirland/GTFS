<?php
include 'header.php';

$query = "SELECT trip_id FROM trip;";
if ($result = mysqli_query($link, $query)) {
	while ($row = mysqli_fetch_row($result)) {
		$trip_id = $row[0];

		$poradi = substr ($trip_id, -3, 1);
		
		switch ($poradi % 2) {
			case "0" : $odd = "1"; break;
			case "1" : $odd = "0"; break;
		}
	
		$prikaz = mysqli_query($link, "UPDATE trip SET direction_id = '$odd' WHERE trip_id = '$trip_id';");
	}
}

include 'footer.php';
?>
