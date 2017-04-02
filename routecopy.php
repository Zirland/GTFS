<?php
include 'header.php';

$trip_id = $_GET['id'];

$lasttrip = substr($trip_id,-1);
$vlak = substr($trip_id,0,-2);
$lomeni = substr($vlak,-1);
$cislo7 = $vlak."/".$lomeni;

switch ($lasttrip) {
    case "A" : $nexttrip = "B"; break;
    case "B" : $nexttrip = "C"; break;
    case "C" : $nexttrip = "D"; break;
    case "D" : $nexttrip = "E"; break;
    case "E" : $nexttrip = "F"; break;
    case "F" : $nexttrip = "G"; break;
    case "G" : $nexttrip = "H"; break;
    case "H" : $nexttrip = "I"; break;
    case "I" : $nexttrip = "J"; break;
    case "J" : $nexttrip = "K"; break;
    case "K" : $nexttrip = "L"; break;
    case "L" : $nexttrip = "M"; break;
    case "M" : $nexttrip = "N"; break;
    case "N" : $nexttrip = "O"; break;
    case "O" : $nexttrip = "P"; break;
    case "P" : $nexttrip = "Q"; break;
    case "Q" : $nexttrip = "R"; break;
    case "R" : $nexttrip = "S"; break;
    case "S" : $nexttrip = "T"; break;
    case "T" : $nexttrip = "U"; break;
}

$new_trip_id = $vlak.$lomeni.$nexttrip;

$hlavicka = mysqli_fetch_row(mysqli_query($link, "SELECT JMENVL FROM kango.HLV WHERE (CISLO7='$cislo7');"));
$jmeno = $hlavicka[0];

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
		
$pom5 = mysqli_fetch_row(mysqli_query($link, "SELECT KALENDAR FROM kango.DTV WHERE (CISLO7='$cislo7');"));
$calpom = $pom5[0];

switch ($calpom) {
    case '1': 
	$matice="1111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111";
    break;
    
    default : 
	$pom6 = mysqli_fetch_row(mysqli_query($link, "SELECT * FROM kango.KVL WHERE (KALENDAR ='$calpom');"));
	$matice = $pom6[7];
    break;
}

$cyklo = 0;
$invalida= 0;

$query86 = "SELECT POZNAM,KODZNAC FROM kango.OBP WHERE (CISLO7='$cislo7');";
if ($result86 = mysqli_query($link, $query86)) {
    while ($row86 = mysqli_fetch_row($result86)) {
	$poznamka = $row86[0];
	$znacka = $row86[1];
	
	switch($znacka) {
	    case 8 :
	    case 9 : $cyklo = 1;
		break;
	    
	    case 7 : $invalida = 1;
		break;
	}
	
	if (strpos($poznamka, "jízdní kolo") !== false) {$cyklo = 1;}
	if (strpos($poznamka, "jízdních kol") !== false) {$cyklo = 2;}
	if (strpos($poznamka, "vozík") !== false) {$invalida = 1;}
    }
}   




$query1 = "INSERT INTO trip VALUES (
    '0',
    '$matice',
    '$new_trip_id',
    '',
    '$jmeno',
    '0',
    '',
    '$new_trip_id',
    '$invalida',
    '$cyklo',
    '0'
);";
$command = mysqli_query($link, $query1) or die("Trip Error description: " . mysqli_error($link));

$i = 0;
$query2 = "SELECT * FROM kango.DTV WHERE (CISLO7='$cislo7');";
if ($result2 = mysqli_query($link, $query2)) {
	while ($row2 = mysqli_fetch_row($result2)) {
		$ZELEZN = $row2[1];
		$ZST = $row2[2];
		$OB = $row2[3];
		$DP = $row2[7];
		$HP = $row2[8];
		$MP = $row2[9];
		$SP = $row2[10];
		$DO = $row2[13];
		$HO = $row2[14];
		$MO = $row2[15];
		$SO = $row2[16];
		$PICK = $row2[30];
		$DROP = $row2[31];
		$ZNAM = $row2[28];
		$i = $i + 1;
		
		$nast = 0;
		$vyst = 0;

		$stop_id = substr($ZELEZN,-2).$ZST.substr($OB,-1);
		
		switch ($ZNAM) {
			case '1' : $nast = 3; $vyst = 3; break;
			default : 
				if ($PICK == '1') {$vyst = 1;}
				if ($DROP == '1') {$nast = 1;}
		}
				
		$hdp = ($DP*24)+$HP;
		if ($hdp<10) $hdp="0".$hdp;
		$mnp = $MP;
		if ($mnp<10) $mnp="0".$mnp;
		$hdo = ($DO*24)+$HO;
		if ($hdo<10) $hdo="0".$hdo;
		$mno = $MO;
		if ($mno<10) $mno="0".$mno;
				
		if ($DP == '') {$hdp=$hdo; $mnp=$mno; $SP=$SO; $HP='0';}
		if ($DO == '') {$hdo=$hdp; $mno=$mnp; $SO=$SP; $HO='0';}
		
		$arrival = $hdp.":".$mnp;
		if (($arrival != "00:0") && $arrival != "24:0" && $arrival != "48:0" && $arrival !="72:0" ) {
	
			switch ($SP) {
				case 0 : $arrival=$arrival.":00"; break;
				case 1 : $arrival=$arrival.":30"; break;
			}
			$depart = $hdo.":".$mno;
			switch ($SO) {
				case 0 : $depart=$depart.":00"; break;
				case 1 : $depart=$depart.":30"; break;
			}
					
			$query3 = "INSERT INTO stoptime VALUES (
			'$new_trip_id',
			'$arrival',
			'$depart',
			'$stop_id',
			'$i',
			'',
			'$nast',
			'$vyst',
			'',
			''
			);";
		$command = mysqli_query($link, $query3) or die("Stop Error description: " . mysqli_error($link));
		}
		
	}
}

$vypoctani_trasy = mysqli_query($link, "INSERT INTO kango.shapecheck (shape_id, complete) VALUES ($new_trip_id, '0');";

echo "Vytvořena trasa $new_trip_id. <a href=\"tripedit.php?id=$new_trip_id\">Přejít na editaci</a>";

include 'footer.php';
?>
