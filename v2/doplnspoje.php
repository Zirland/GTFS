<?php
include 'header.php';

$query = "SELECT stop_id, parent_station FROM stop WHERE parent_station != '';";
if ($result = mysqli_query($link, $query)) {
	while ($row = mysqli_fetch_row($result)) {
		$stop_id = $row[0];
		$parent_station = $row[1];
		
		$query10 = "INSERT INTO kango.DU_pom (STOP1, STOP2, DELKA) VALUES ('$stop_id', ' $parent_station', '0');";
		$prikaz10 = mysqli_query($link, $query10);
	}
}

include 'footer.php';
?>
