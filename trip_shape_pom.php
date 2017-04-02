<?php
include 'header.php';

$query = "SELECT trip_id FROM trip;";
if ($result = mysqli_query($link, $query)) {
	while ($row = mysqli_fetch_row($result)) {
		$trip_id = $row[0];
		
		$prikaz1 = "INSERT INTO kango.shapecheck (shape_id, complete) VALUES ('$trip_id', '0');";
		$command1 = mysqli_query($link, $prikaz1);
	}
}

echo "Hotovo";

include 'footer.php';
?>
