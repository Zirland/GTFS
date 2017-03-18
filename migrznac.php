<?php
include 'header.php';

$query = "SELECT trip_id FROM trip;";
if ($result = mysqli_query($link, $query)) {
    while ($row = mysqli_fetch_row($result)) {
	$trip_id = $row[0];
	$vlak = substr($trip_id,0,-2);
	$lomeni = substr($vlak,-1);
	$cislo7 = $vlak."/".$lomeni;
	
	$cyklo = 0;
	$invalida = 0;
	
	$query86 = "SELECT POZNAM,KODZNAC FROM kango.OBP WHERE (CISLO7='$cislo7');";
	if ($result86 = mysqli_query($link, $query86)) {
	    while ($row86 = mysqli_fetch_row($result86)) {
		$poznamka = $row86[0];
		$znacka = $row86[1];
		
		switch ($znacka) {
		    case 8:
		    case 9:
			$cyklo = 1;
			break;
			
		    case 7:
			$invalida = 1;
			break;
		}

		if (strpos($poznamka, "jízdní kolo") !== false) {$cyklo=1;}
		if (strpos($poznamka, "jízdních kol") !== false) {$cyklo=2;}
		if (strpos($poznamka, "vozík") !== false) {$invalida=1;}
	    }
	}
	$query37 = "UPDATE trip SET wheelchair_accessible=$invalida, bikes_allowed=$cyklo WHERE (trip_id = '$trip_id');";

	$akce = mysqli_query($link, $query37);
    }
}


echo "Migrace provedena";

include 'footer.php';
?>
