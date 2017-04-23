<?php
include 'header.php';

$query = "SELECT trip_id FROM trip WHERE (trip_id IN (SELECT DISTINCT trip_id FROM GTFS.stoptime WHERE (pickup_type='1' OR drop_off_type='1')));";
if ($result = mysqli_query($link, $query)) {
    while ($row = mysqli_fetch_row($result)) {
		$trip_id = $row[0];
		$vlak = substr($trip_id,0,-2);
		$lomeni = substr($vlak,-1);
		$cislo7 = $vlak."/".$lomeni;

		$query2 = "SELECT * FROM kango.DTV WHERE (CISLO7='$cislo7');";
		echo $query2;
		if ($result2 = mysqli_query($link, $query2)) {
			while ($row2 = mysqli_fetch_row($result2)) {
				$ZELEZN = $row2[1];
				$ZST = $row2[2];
				$OB = $row2[3];
				$PICK = $row2[30];
				$DROP = $row2[31];
				$ZNAM = $row2[28];
		
				$stop_id = substr($ZELEZN,-2).$ZST.substr($OB,-1);
				$nast = 0;
				$vyst = 0;

				switch ($ZNAM) {
					case '1' : $nast = 3; $vyst = 3; break;
					default : 
						if ($PICK == '1') {$nast = 1;}
						if ($DROP == '1') {$vyst = 1;}
				}
			$query37 = "UPDATE stoptime SET pickup_type=$nast, drop_off_type=$vyst WHERE (trip_id = '$trip_id' AND stop_id='$stop_id');";
			$akce = mysqli_query($link, $query37);
			}
		}
		
    }
}


echo "Migrace provedena";

include 'footer.php';
?>
