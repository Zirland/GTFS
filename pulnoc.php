<?php
include 'header.php';

$query = "SELECT trip_id, min(stop_sequence) FROM stoptime GROUP BY trip_id ORDER BY trip_id;";
if ($result = mysqli_query($link, $query)) {
	while ($row = mysqli_fetch_row($result)) {
		$trip_id = $row[0];
		$min_stop = $row[1];

		$query10 = "SELECT arrival_time FROM stoptime WHERE (trip_id = '$trip_id' AND stop_sequence = '$min_stop');";
		if ($result10 = mysqli_query($link, $query10)) {
			while ($row10 = mysqli_fetch_row($result10)) {
				$odjezd = $row10[0];

				$H = substr($odjezd,0,2);
				if ($H > 23) {
					$vlozeni = mysqli_query($link, "INSERT INTO routelist VALUES ('$trip_id');");
					echo "$trip_id - $odjezd<br />";
				}
			}
		}
	}
}

$query = "SELECT * from routelist;";
if ($result = mysqli_query($link, $query)) {
	while ($row = mysqli_fetch_row($result)) {
		$trip_id = $row[0];

		$query10 = "SELECT arrival_time,departure_time,stop_sequence FROM stoptime WHERE (trip_id = '$trip_id');";
		if ($result10 = mysqli_query($link, $query10)) {
			while ($row10 = mysqli_fetch_row($result10)) {
				$prijezd = $row10[0];
				$odjezd = $row10[1];
				$seq = $row10[2];

				$HP = substr($prijezd,0,2) - 24;
				if ($HP < 10) {$HP = "0".$HP;}
				$HO = substr($odjezd,0,2) - 24;		
				if ($HO < 10) {$HO = "0".$HO;}

				$novyP = $HP . substr($prijezd,2);
				$novyO = $HO . substr($odjezd,2);

				echo "$trip_id [$seq] - $novyP $novyO <br/>";
				$oprav = mysqli_query($link, "UPDATE stoptime SET arrival_time = '$novyP', departure_time = '$novyO' WHERE (trip_id = '$trip_id' AND stop_sequence = '$seq');"); 
				$smaz = mysqli_query($link, "DELETE FROM routelist WHERE CISLO7='$trip_id';");
			}
		}
	}
}
		
include 'footer.php';
?>
