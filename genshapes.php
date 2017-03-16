<?php
include 'header.php';

$query = "SELECT * FROM trip;";
if ($result = mysqli_query($link, $query)) {
	while ($row = mysqli_fetch_row($result)) {
		$trip_id = $row[2];
		
		$lomeni = substr($trip_id,-2,1);
		$vlak = substr($trip_id, 0, -2);
		$cislo7 = $vlak."/".$lomeni;

		$i = 0;
		$query4 = "SELECT * FROM kango.DTV WHERE (CISLO7='$cislo7');";
		if ($result4 = mysqli_query($link, $query4)) {
			while ($row4 = mysqli_fetch_row($result4)) {
				$ZST = $row4[2];
				$i = $i + 1;
		
				$pom4 = mysqli_fetch_row(mysqli_query($link, "SELECT stop_lat,stop_lon FROM stop WHERE (stop_id='$ZST');"));
				$lat = $pom4[0];
				$lon = $pom4[0];

				if ($lat != '' && $lon != '') {
					$query5 = "INSERT INTO shape VALUES (
					'$trip_id',
					'$lat',
					'$lon',
					'$i',
					''
					);";
				echo $query5."<br/>";
				$command = mysqli_query($link, $query5)  or die("Shape Error description: " . mysqli_error($link));;
				}
			}
		}

	}
}


include 'footer.php';
?>
