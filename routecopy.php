<?php
include 'header.php';

$trip_id = $_GET['id'];

$lasttrip = substr($trip_id,-1);
$vlak = substr($trip_id,0,-2);
$lomeni = substr($vlak,-1);
$cislo7 = $vlak."/".$lomeni;

$nexttrip=$lasttrip+1;

$new_trip_id = $vlak.$lomeni.$nexttrip;

$hlavicka = mysqli_fetch_row(mysqli_query($link, "SELECT JMENVL FROM kango.HLV WHERE (CISLO7='$cislo7');"));
$jmeno = $hlavicka[0];

$pom5 = mysqli_fetch_row(mysqli_query($link, "SELECT * FROM trip WHERE trip_id = '$trip_id';"));
$matice = $pom5[1];

$cyklo = 0;
$invalida= 0;

$poradi = substr($trip_id, -3, 1);

switch ($poradi % 2) {
	case "0" : $odd = "1"; break;
        case "1" : $odd = "0"; break;
}

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
    '$odd',
    '',
    '$new_trip_id',
    '$invalida',
    '$cyklo',
    '1'
);";
$command = mysqli_query($link, $query1) or die("Trip Error description: " . mysqli_error($link));

$i = 0;
$miss = 0;

$zststrt = 0;
$zststp = 0;
 
$query13 = "SELECT * FROM kango.KDV WHERE cislo7= '$cislo7';";
if ($result13 = mysqli_query($link, $query13)) {
        while ($row13 = mysqli_fetch_row($result13)) {
                $ZELEZNZ = $row13[2];
                $ZSTZ = $row13[3];
                $OBZ = $row13[4];
                $ZELEZNDO = $row13[5];
                $ZSTDO = $row13[6];
                $OBDO = $row13[7];

                $pomz = substr($ZELEZNZ,-2).$ZSTZ.substr($OBZ,-1);
                $pomdo = substr($ZELEZNDO,-2).$ZSTDO.substr($OBDO,-1);

                if ($zststrt == 0) {$zststrt = $pomz;}
                if ($zststp == 0) {$zststp = $pomdo;}
                if ($zststp == $pomz) {$zststp = $pomdo;}
        }
}

$start = 0;
$end = 0;

$query2 = "SELECT * FROM kango.DTV WHERE (CISLO7='$cislo7');";
if ($result2 = mysqli_query($link, $query2)) {
	while ($row2 = mysqli_fetch_row($result2)) {
		$ZELEZN = $row2[1];
		$ZST = $row2[2];
		$OB = $row2[3];
		$STKOLPRIJ = $row2[6];
		$DP = $row2[7];
		$HP = $row2[8];
		$MP = $row2[9];
		$SP = $row2[10];
		$DO = $row2[13];
		$HO = $row2[14];
		$MO = $row2[15];
		$SO = $row2[16];
		$STKOLODJ = $row2[17];
		$ZNAM = $row2[28];
		$PICK = $row2[30];
		$DROP = $row2[31];
		$ODJCASPRIJ = $row2[49];
		$i = $i + 1;
		
		$nast = 0;
		$vyst = 0;

                $aktual = substr($ZELEZN,-2).$ZST.substr($OB,-1);
                $ignore = 0;
                if ($start==0 && $end==0 && $aktual!=$zststrt) {$ignore = 1;}
                if ($start==1 && $end==1) {$ignore = 1;}
                if ($start==0 && $aktual==$zststrt) {$start = 1; $ignore = 0; $DP=$DO; $HP=$HO; $MP=$MO; $SP=$SO;}
                if ($start==1 && $aktual==$zststp) {$end = 1; $ignore = 0; $DO=$DP; $HO=$HP; $MO=$MP; $SO=$SP;}
                if ($ZELEZN != '0054') {$ignore=1;}

                switch ($ZNAM) {
                        case '1' : $nast = 3; $vyst = 3; break;
                        default :
                                if ($PICK == '1') {$vyst = 1;}
                                if ($DROP == '1') {$nast = 1;}
                        break;
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

                if ($arrival == "00:0" || $arrival == "24:0" || $arrival == "48:0" || $arrival =="72:0") {$ignore = 1;}

                switch ($SP) {
                        case 0 : $arrival=$arrival.":00"; break;
                        case 1 : $arrival=$arrival.":30"; break;
                }

                $depart = $hdo.":".$mno;
                switch ($SO) {
                        case 0 : $depart=$depart.":00"; break;
                        case 1 : $depart=$depart.":30"; break;
                }


                if ($arrival == "24:0:00") {$arrival = "24:00:00";}
                if ($depart == "24:0:00") {$depart = "24:00:00";}

                $stop_id = substr($ZELEZN,-2).$ZST.substr($OB,-1);
                $query88 = "SELECT stop_name, active FROM stop WHERE stop_id = '$stop_id';";
                if ($result88 = mysqli_query($link, $query88)) {
                        $radky=mysqli_num_rows($result88);
                        $row88 = mysqli_fetch_row($result88);
                        $jmeno = $row88[0];
                        $aktivita = $row88[1];

                        if ($aktivita == "2" && $arrival != "00:0:00") {$nast = 2; $vyst = 2; $ignore = 0;}
                        if ($aktivita == "0" || $radky == 0) {$ignore = 1;}

                        if ($miss==0 && $ignore==0) {
                                if ($ODJCASPRIJ == 1) {$depart = $arrival;}

                                $query3 = "INSERT INTO stoptime VALUES ('$new_trip_id','$arrival','$depart','$stop_id','$i','','$nast','$vyst','','');";
//                              echo "$prikaz3<br/>";
                                $command = mysqli_query($link, $query3) or die("Stop Error description: " . mysqli_error($link));
                        }

                        $miss = 0;
                        $ignore = 0;

                }
        }
}


$vypoctani_trasy = mysqli_query($link, "INSERT INTO kango.shapecheck (shape_id, complete) VALUES ($new_trip_id, '0');");

echo "Vytvořena trasa $new_trip_id. <a href=\"tripedit.php?id=$new_trip_id\">Přejít na editaci</a>";

include 'footer.php';
?>
