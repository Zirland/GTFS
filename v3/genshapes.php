<?php
include 'header.php';

$query = "SELECT * FROM trip;";
if ($result = mysqli_query($link, $query)) {
	while ($row = mysqli_fetch_row($result)) {
		$trip_id = $row[2];
		
		$vlak = substr($trip_id,0,-2);
		$lomeni = substr($vlak,-1);
		$cislo7 = $vlak."/".$lomeni;

		$tvartrasy = "";
		$i = 0;

		$pom125 = mysqli_fetch_row(mysqli_query($link, "SELECT max(stop_sequence) FROM stoptime WHERE (trip_id = '$trip_id');"));
		$max_trip = $pom125[0];

		$pom129 = mysqli_fetch_row(mysqli_query($link, "SELECT min(stop_sequence) FROM stoptime WHERE (trip_id = '$trip_id');"));
		$min_trip = $pom129[0];


		$query131 = "SELECT * FROM kango.DTV WHERE (CISLO7='$cislo7');";
		if ($result131 = mysqli_query($link, $query131)) {
			while ($row131 = mysqli_fetch_row($result131))  {
				$stopstat = $row131[1];
				$stopzst = $row131[2];
				$stopob = $row131[3];
				$ZST = substr($stopstat,-2).$stopzst.substr($stopob,-1);
				$i = $i + 1;
	
				if ($i <= $max_trip && $i >= $min_trip) {
				}
			}
		}

		$dotaz = "UPDATE trip SET shape_id = '$tvartrasy' WHERE trip_id = '$trip_id';";
		echo $dotaz;
		$prikaz = mysqli_query($link, $dotaz);
		echo "$trip_id<br />";
	}
}


include 'footer.php';
?>
