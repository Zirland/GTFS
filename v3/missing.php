<?php
include 'header.php';

$query = "SELECT DISTINCT stop_id FROM GTFS.stoptime WHERE (stop_id NOT IN (SELECT stop_id FROM stop)) ORDER BY stop_id;";
if ($result = mysqli_query($link, $query)) {
	while ($row = mysqli_fetch_row($result)) {
		$stop_id = $row[0];
		$ZELEZN = "00".substr($stop_id,0,2);
		$ZST = substr($stop_id,2,6);
		$OB = "0".substr($stop_id,-1);
		$pom11 = mysqli_fetch_row(mysqli_query($link, "SELECT NAZEVDB FROM kango.DB WHERE (ZELEZN='$ZELEZN' AND ZST='$ZST' AND OB='$OB');"));
		$nazev = $pom11[0];
		//$prior = mysqli_query($link, "UPDATE kango.loads SET POCET='1000' WHERE (ZELEZN='$ZELEZN' AND ZST='$ZST' AND OB='$OB');");
		
		echo "$stop_id - $nazev <br>";
	}
}

include 'footer.php';
?>
