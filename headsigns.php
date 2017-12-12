<?php
include 'header.php';

$query = "SELECT trip_id FROM trip WHERE trip_headsign='';";
if ($result = mysqli_query($link, $query)) {
	while ($row = mysqli_fetch_row($result)) {
		$trip_id = $row[0];

		$pomendstop = mysqli_fetch_row(mysqli_query($link, "SELECT MAX(stop_sequence) FROM stoptime WHERE (trip_id = '$trip_id');"));
        	$endstopno = $pomendstop[0];

		$pomfinstop=mysqli_fetch_row(mysqli_query($link, "SELECT stop_id FROM stoptime WHERE (trip_id='$trip_id' AND stop_sequence='$endstopno');"));
		$finstop=$pomfinstop[0];
		$pomfinstopparent=mysqli_fetch_row(mysqli_query($link, "SELECT parent_station FROM stop WHERE stop_id='$finstop';"));
		$finstopparent=$pomfinstopparent[0];
		if ($finstopparent == '') {$finstopid = $finstop;} else {$finstopid = $finstopparent;}
		echo "stop $finstop - parent $finstopparent - id $finstopid<br/>";
								
		$query180 = "SELECT stop_name FROM stop WHERE stop_id='$finstopid';";
		$result180 = mysqli_query($link, $query180);
	        $pomhead = mysqli_fetch_row($result180);
	        $headsign = $pomhead[0];

        	$query1701="UPDATE trip SET trip_headsign='$headsign', shape_id='$tvartrasy' WHERE trip_id='$trip_id';";
		echo "$query1701<br/>";
		$prikaz1701=mysqli_query($link, $query1701);
	}
}

include 'footer.php';
?>
