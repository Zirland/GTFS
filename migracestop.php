<?php
include 'header.php';

$query1 = "SELECT trip_id,stop_sequence,stop_id FROM stoptime WHERE stop_id IN (SELECT stop_id FROM stop WHERE location_type='1');";
if ($result1 = mysqli_query ($link, $query1)) {
	while ($row1 = mysqli_fetch_row ($result1)) {
		$trip_id = $row1[0];
		$seq = $row1[1];
		$stop_id = $row1[2];

		$cislo7 = substr ($trip_id,0,-2)."/".substr ($trip_id,-2,1);
		$zst=substr ($stop_id,2,-1);

		$query2 = "SELECT STKOLPRIJ FROM kango.DTV WHERE cislo7='$cislo7' AND ZELEZN='0054' AND ZST='$zst';";
		$nalezni = mysqli_fetch_row (mysqli_query ($link, $query2));
		$kolej = $nalezni[0];

		$query3 = "UPDATE stoptime SET stop_id='54".$zst."0"."$kolej' WHERE trip_id='$trip_id' AND stop_sequence='$seq';";
		$nahrad = mysqli_query ($link, $query3);
	}
}

echo "NAHRAZENO";

include 'footer.php';
?>