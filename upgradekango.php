<?php
include 'header.php';
/*
$query4 = "DELETE FROM stoptime WHERE trip_id NOT LIKE 'F%';";
$command4 = mysqli_query ($link, $query4);

$query7 = "DELETE FROM trip WHERE trip_id NOT LIKE 'F%';";
$command7 = mysqli_query ($link, $query7);
*/
$query10 = "SELECT SKIP 2 trip_id FROM trip;";
if ($result10 = mysqli_query ($link, $query10)) {
	while ($row10 = mysqli_fetch_row ($result10)) {
		$trip_id = $row10[0];

		$newtrip = substr ($trip_id, 1);

		$query17 = "UPDATE trip SET trip_id = '$newtrip' WHERE trip_id = '$trip_id';";
		$prikaz17 = mysqli_query ($link, $query17);

		$query20 = "UPDATE stoptime SET trip_id = '$newtrip' WHERE trip_id = '$trip_id';";
		$prikaz20 = mysqli_query ($link, $query20);
	}
}

echo "Dokončeno";

include 'footer.php';
?>