<?php
include 'header.php';

$tripy = "SELECT trip_id FROM trip WHERE (trip_id = shape_id);";
if ($najditripy = mysqli_query ($link, $tripy)) {
    while ($row = mysqli_fetch_row($najditripy)) {
	$trip_id = $row[0];
	
	echo "$trip_id : ";
	$pom125 = mysqli_fetch_row(mysqli_query($link, "SELECT max(stop_sequence) FROM stoptime WHERE (trip_id = '$trip_id');"));
	$max_trip = $pom125[0];

	$pom129 = mysqli_fetch_row(mysqli_query($link, "SELECT min(stop_sequence) FROM stoptime WHERE (trip_id = '$trip_id');"));
	$min_trip = $pom129[0];

	$lomeni = substr($trip_id,-2,1);
	$vlak = substr($trip_id, 0, -2);
	$cislo7 = $vlak."/".$lomeni;
	
	$i = 0;
	$prubeh = "";

	$query131 = "SELECT * FROM kango.DTV WHERE (CISLO7='$cislo7');";
	if ($result131 = mysqli_query($link, $query131)) {
	    while ($row131 = mysqli_fetch_row($result131))  {
		$stopstat = $row131[1];
		$stopzst = $row131[2];
		$stopob = $row131[3];
		$ZST = substr($stopstat,-2).$stopzst.substr($stopob,-1);
		$i = $i + 1;

		if ($i <= $max_trip && $i >= $min_trip) {
		    $prubeh .= $ZST;
		}
	    }
	$oprav = "UPDATE trip SET shape_id = '$prubeh' WHERE trip_id = '$trip_id';";
	$proved = mysqli_query($link, $oprav);
	}
    }
}

include 'footer.php';
?>
