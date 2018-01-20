<?php
include 'header.php';

$chybna = $_GET['err'];

$delstring = "DELETE FROM stoptime WHERE (trip_id = '$chybna');";
$smaztrip = mysqli_query ($link, $delstring);

$vlak=substr ($chybna,0,-2);
$lomeni=substr ($chybna,-2,1);
$cislo7=$vlak."/".$lomeni;

$i = 0;
$miss = 0;

$zststrt = 0;
$zststp = 0;

$query13 = "SELECT * FROM kango.KDV WHERE cislo7= '$cislo7';";
if ($result13 = mysqli_query ($link, $query13)) {
	while ($row13 = mysqli_fetch_row ($result13)) {
		$ZELEZNZ = $row13[2];
		$ZSTZ = $row13[3];
		$OBZ = $row13[4];
		$ZELEZNDO = $row13[5];
		$ZSTDO = $row13[6];
		$OBDO = $row13[7];

		$pomz = substr ($ZELEZNZ,-2).$ZSTZ.substr ($OBZ,-1);
		$pomdo = substr ($ZELEZNDO,-2).$ZSTDO.substr ($OBDO,-1);

		if ($zststrt == 0) {
			$zststrt = $pomz;
		}
		if ($zststp == 0) {
			$zststp = $pomdo;
		}
		if ($zststp == $pomz) {
			$zststp = $pomdo;
		}
	}
}

$start = 0;
$end = 0;

$query7 = "SELECT * FROM kango.DTV WHERE (CISLO7 = '$cislo7');";
if ($result7 = mysqli_query ($link, $query7)) {
	while ($row7 = mysqli_fetch_row ($result7)) {
		$lomeni = substr ($cislo7,-1);
		$vlak = substr ($cislo7, 0, -2);

		$ZELEZN = $row7[1];
		$ZST = $row7[2];
		$OB = $row7[3];
		$STKOLPRIJ = $row7[6];
		$DP = $row7[7];
		$HP = $row7[8];
		$MP = $row7[9];
		$SP = $row7[10];
		$DO = $row7[13];
		$HO = $row7[14];
		$MO = $row7[15];
		$SO = $row7[16];
		$STKOLODJ= $row7[17];
		$ZNAM = $row7[28];
		$PICK = $row7[30];
		$DROP = $row7[31];
		$ODJCASPRIJ = $row7[49];
		$i = $i + 1;

		$nast = 0;
		$vyst = 0;

		$aktual = substr ($ZELEZN,-2).$ZST.substr ($OB,-1);
		$ignore = 0;
		if ($start==0 && $end==0 && $aktual!=$zststrt) {
			$ignore = 1;
		}
		if ($start==1 && $end==1) {
			$ignore = 1;
		}
		if ($start==0 && $aktual==$zststrt) {
			$start = 1;
			$ignore = 0;
			$DP=$DO;
			$HP=$HO;
			$MP=$MO;
			$SP=$SO;
		}
		if ($start==1 && $aktual==$zststp) {
			$end = 1;
			$ignore = 0;
			$DO=$DP;
			$HO=$HP;
			$MO=$MP;
			$SO=$SP;
		}
		if ($ZELEZN != '0054') {
			$ignore=1;
		}

		switch ($ZNAM) {
			case '1' :
				$nast = 3;
				$vyst = 3;
			break;
			default : 
				if ($PICK == '1') {
					$vyst = 1;
				}
				if ($DROP == '1') {
					$nast = 1;
				}
			break;
		}

		$hdp = ($DP * 24) + $HP;
		if ($hdp < 10) {
			$hdp = "0".$hdp;
		}
		$mnp = $MP;
		if ($mnp < 10) {
			$mnp = "0".$mnp;
		}
		$hdo = ($DO * 24) + $HO;
		if ($hdo < 10) {
			$hdo = "0".$hdo;
		}
		$mno = $MO;
		if ($mno < 10) {
			$mno = "0".$mno;
		}

		if ($DP == '') {
			$hdp = $hdo;
			$mnp = $mno;
			$SP = $SO;
			$HP = '0';
		}
		if ($DO == '') {
			$hdo = $hdp;
			$mno = $mnp;
			$SO = $SP;
			$HO = '0';
		}
		
		$arrival = $hdp.":".$mnp;

		if ($arrival == "00:0" || $arrival == "24:0" || $arrival == "48:0" || $arrival =="72:0") {
			$ignore = 1;
		}

		switch ($SP) {
			case 0 :
				$arrival=$arrival.":00";
			break;
			case 1 :
				$arrival=$arrival.":30";
			break;
		}

		$depart = $hdo.":".$mno;
		switch ($SO) {
			case 0 : $depart=$depart.":00"; break;
			case 1 : $depart=$depart.":30"; break;
		}


		if ($arrival == "24:0:00") {
			$arrival = "24:00:00";
		}
		if ($depart == "24:0:00") {
			$depart = "24:00:00";
		}

		$stop_id = substr ($ZELEZN,-2).$ZST.substr ($OB,-1);
		$query88 = "SELECT stop_name, active FROM stop WHERE stop_id = '$stop_id';";
		if ($result88 = mysqli_query ($link, $query88)) {
			$radky=mysqli_num_rows($result88);
			$row88 = mysqli_fetch_row ($result88);
			$jmeno = $row88[0];	
			$aktivita = $row88[1];

			if ($aktivita == "2" && $arrival != "00:0:00") {
				$nast = 2;
				$vyst = 2;
				$ignore = 0;
			}
			if ($aktivita == "0") {
				$ignore = 1;
			}

			if ($miss==0 && $ignore==0) {
				if ($ODJCASPRIJ == 1) {
					$depart = $arrival;
				}

				$query3 = "INSERT INTO stoptime VALUES ('$chybna','$arrival','$depart','$stop_id','$i','','$nast','$vyst','','');";
				$command = mysqli_query ($link, $query3) or die ("Stop Error description: " . mysqli_error ($link));
			}

			$miss = 0;
			$ignore = 0;
		}
	}
}

$pomendstop = mysqli_fetch_row (mysqli_query ($link, "SELECT MAX(stop_sequence) FROM stoptime WHERE (trip_id = '$trip_id' AND pickup_type != '2');"));
$endstopno = $pomendstop[0];

$pomfinstop=mysqli_fetch_row (mysqli_query ($link, "SELECT stop_id FROM stoptime WHERE (trip_id='$chybna' AND stop_sequence='$endstopno');"));
$finstop=$pomfinstop[0];
$pomfinstopparent=mysqli_fetch_row (mysqli_query ($link, "SELECT parent_station FROM stop WHERE stop_id='$finstop';"));
$finstopparent=$pomfinstopparent[0];
if ($finstopparent == '') {
	$finstopid = $finstop;
} else {
	$finstopid = $finstopparent;
}

$query180 = "SELECT stop_name FROM stop WHERE stop_id='$finstopid';";
$result180 = mysqli_query ($link, $query180);
$pomhead = mysqli_fetch_row ($result180);
$headsign = $pomhead[0];

$pom163 = mysqli_fetch_row (mysqli_query ($link, "SELECT max(stop_sequence) FROM stoptime WHERE (trip_id = '$chybna');"));
$max_trip = $pom163[0];

$pom129 = mysqli_fetch_row (mysqli_query ($link, "SELECT min(stop_sequence) FROM stoptime WHERE (trip_id = '$chybna');"));
$min_trip = $pom129[0];

$tvartrasy = "";
$ii = 0;

$query131 = "SELECT * FROM kango.DTV WHERE (CISLO7='$cislo7');";
if ($result131 = mysqli_query ($link, $query131)) {
	while ($row131 = mysqli_fetch_row ($result131))  {
		$stopstat = $row131[1];
		$stopzst = $row131[2];
		$stopob = $row131[3];
		$ZST = substr ($stopstat,-2).$stopzst.substr ($stopob,-1);
		$ii = $ii + 1;

		if ($ii <= $max_trip && $ii >= $min_trip) {
			$tvartrasy .= $ZST."|";
		}
	}
}

$query1701="UPDATE trip SET trip_headsign='$headsign', shape_id='$tvartrasy' WHERE trip_id='$chybna';";
$prikaz1701=mysqli_query ($link, $query1701);

include 'footer.php';
?>