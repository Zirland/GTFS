<?php
include 'header.php';

$chybna = $_GET['err'];

$delstring = "DELETE FROM stoptime WHERE (trip_id = '$chybna');";
$smaztrip = mysqli_query($link, $delstring);
// smaze zastavky vlaku

$vypni = mysqli_query($link, "UPDATE trip SET active=0 WHERE (trip_id='chybna');");
// deaktivuje spoj

$vlak=substr($chybna,0,-2);
$lomeni=substr($vlak,-1);
$cislo7=$vlak."/".$lomeni;
 
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
				if ($PICK == '1') {$nast = 1;}
				if ($DROP == '1') {$vyst = 1;}
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
			'$chybna',
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
		echo $query3;
		$command = mysqli_query($link, $query3) or die("Stop Error description: " . mysqli_error($link));
		}
		
	}
}

include 'footer.php';
?>
