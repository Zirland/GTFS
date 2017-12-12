<?php
include 'header.php';

$vlak = $_GET['vl'];
$lomeni = substr($vlak,-1);
$cislo7 = $vlak."/".$lomeni;

echo "<table><tr><td>";
echo "<table>";
echo "<tr>";

$hlavicka = mysqli_fetch_row(mysqli_query($link, "SELECT JMENVL,IDDOP,DATODVL,DATDOVL FROM kango.HLV WHERE (CISLO7='$cislo7');"));
$jmeno = $hlavicka[0];
$iddop = $hlavicka[1];
$datodvl = $hlavicka[2];
$datdovl = $hlavicka[3];

$datum_od = date("j.n.Y", mktime(0, 0, 0, substr($datodvl,2,2), substr($datodvl,0,2), substr($datodvl,-4)));
$datum_do = date("j.n.Y", mktime(0, 0, 0, substr($datdovl,2,2), substr($datdovl,0,2), substr($datdovl,-4)));

$pom1 = mysqli_fetch_row(mysqli_query($link, "SELECT agency_name FROM agency WHERE (agency_id='$iddop');"));
$dopravce = $pom1[0];

$trip_id = $vlak.$lomeni."1";

echo "<td>Vlak: $cislo7</td><td>$jmeno</td><td>Dopravce: $dopravce</td><td>Jede od: $datum_od</td><td>Jede do: $datum_do</td></tr>";

$query = "SELECT * FROM kango.DTV WHERE (CISLO7='$cislo7');";
if ($result = mysqli_query($link, $query)) {
	while ($row = mysqli_fetch_row($result)) {
		$ZELEZN = $row[1];
		$ZST = $row[2];
		$OB = $row[3];
		$DP = $row[7];
		$HP = $row[8];
		$MP = $row[9];
		$SP = $row[10];
		$DO = $row[13];
		$HO = $row[14];
		$MO = $row[15];
		$SO = $row[16];
		$calendar = $row[37];

		$pom2 = mysqli_fetch_row(mysqli_query($link, "SELECT NAZEVDB FROM kango.DB WHERE (ZELEZN='$ZELEZN' AND ZST='$ZST' AND $OB='$OB');"));
		$stanice = $pom2[0];
		
		$hdp = ($DP*24)+$HP;
		if ($hdp<10) $hdp="0".$hdp;
		$mnp = $MP;
		if ($mnp<10) $mnp="0".$mnp;
		$hdo = ($DO*24)+$HO;
		if ($hdo<10) $hdo="0".$hdo;
		$mno = $MO;
		if ($mno<10) $mno="0".$mno;
		
		
		echo "<tr>";
		echo "<td>$ZELEZN $ZST $OB $stanice</td>";
		echo "<td>";
		if ($DP == '') {$hdp=$hdo; $mnp=$mno; $SP=$SO; $HP='0';}
		if ($HP != '') {echo "$hdp:$mnp";}
		echo "</td>";
		echo "<td>";
		if ($DO == '') {$hdo=$hdp; $mno=$mnp; $SO=$SP; $HO='0';}
		if ($HO != '') {echo "$hdo:$mno";}
		echo "</td>";

		switch ($calendar) {
		case 0 : $provoz = ""; break;
		case 1 : $provoz = "Jede denně"; break;
		default : 
		
		$pom3 = mysqli_fetch_row(mysqli_query($link, "SELECT * FROM kango.KVL WHERE (KALENDAR ='$calendar');"));
		$d1 = $pom3[0];
		$m1 = $pom3[1];
		$r1 = $pom3[2];
		$d2 = $pom3[3];
		$m2 = $pom3[4];
		$r2 = $pom3[5];
		$textcal = $pom3[8];
		
		
		$cal_od = date("j.n.Y", mktime(0, 0, 0, $m1 , $d1, $r1));
		$cal_do = date("j.n.Y", mktime(0, 0, 0, $m2 , $d2, $r2));		

		$provoz = "Od $cal_od do $cal_do $textcal"; break;
		}
		echo "<td>$provoz</td>";
		echo "</td>";
		echo "</tr>";
	}
	mysqli_free_result($result);
}

echo "</table></td><td>";

echo "<table>";
echo "<tr><td>TRIP</td></tr>";
$i = 0;
$query1 = "SELECT * FROM kango.DTV WHERE (CISLO7='$cislo7');";
if ($result1 = mysqli_query($link, $query1)) {
	while ($row1 = mysqli_fetch_row($result1)) {
		$ZELEZN = $row1[1];
		$ZST = $row1[2];
		$OB = $row1[3];
		$DP = $row1[7];
		$HP = $row1[8];
		$MP = $row1[9];
		$SP = $row1[10];
		$DO = $row1[13];
		$HO = $row1[14];
		$MO = $row1[15];
		$SO = $row1[16];
		$i = $i + 1;
		$stop = substr($ZELEZN,-2).$ZST.substr($OB,-1);
		
		$pom4 = mysqli_fetch_row(mysqli_query($link, "SELECT stop_name FROM stop WHERE (stop_id='$stop');"));
		$zastavka = $pom4[0];
				
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
		
		if ($mnp != '0' && $mno != '0') {
			echo "<tr>";
			echo "<td>$trip_id</td>";
			echo "<td>$hdp:$mnp:";
			switch ($SP) {
				case 0 : echo "00</td>"; break;
				case 1 : echo "30</td>"; break;
			}
			echo "<td>$hdo:$mno:";
			switch ($SO) {
				case 0 : echo "00</td>"; break;
				case 1 : echo "30</td>"; break;
			}
			echo "<td>$stop</td>";
			echo "<td>$i</td>";
			echo "<td>$zastavka</td>";
			echo "</tr>";
		}
	}
}
		
echo "<tr><td>SHAPE</td></tr>";
$i = 0;
$query2 = "SELECT * FROM kango.DTV WHERE (CISLO7='$cislo7');";
if ($result2 = mysqli_query($link, $query2)) {
	while ($row2 = mysqli_fetch_row($result2)) {
		$ZELEZN = $row2[1];
		$ZST = $row2[2];
		$OB = $row2[3];
		$i = $i + 1;
		
		
		$pom4 = mysqli_fetch_row(mysqli_query($link, "SELECT stop_lat,stop_lon FROM stop WHERE ($ZELEZN='$ZELEZN' AND ZST='$ZST' AND $OB='$OB');"));
		$lat = $pom4[0];
		$lon = $pom4[0];
				
		echo "<tr>";
		echo "<td>$trip_id</td>";
		echo "<td>$lat";
		echo "<td>$lon</td>";
		echo "<td>$i</td>";
		echo "</tr>";
	}
}


echo "</td></tr></table>";

$pom5 = mysqli_fetch_row(mysqli_query($link, "SELECT KALENDAR FROM kango.DTV WHERE (CISLO7='$cislo7');"));
$calpom = $pom5[0];

switch ($calpom) {
	case '1': 
		for ($i = 0; $i < 365; $i++) {
			$matice.="1";
		}
		break;
	default : $pom6 = mysqli_fetch_row(mysqli_query($link, "SELECT * FROM kango.KVL WHERE (KALENDAR ='$calpom');"));
		$dd1 = $pom6[0];
		$mm1 = $pom6[1];
		$rr1 = $pom6[2];
		$matice = $pom6[7];
		break;
}

echo "</td></tr></table>";

// Matice začíná 10.12.2017 
$matice_start = mktime(0,0,0,12,10,2017);
$zitra_den = date("d", time()+86400);
$zitra_mesic = date("m", time()+86400);
$zitra_rok = date("Y", time()+86400);
$calendar_start = mktime(0,0,0,$zitra_mesic,$zitra_den,$zitra_rok);
$calendar_start_format = date("dmY", time()+86400);
$calendar_stop_format = date("dmY", time()+8*86400);
$vtydnu = date('w',$calendar_start);

$sek=$calendar_start-$matice_start;

$min=floor($sek/60);
$sek=$sek%60;

$hod=floor($min/60);
$min=$min%60;

$dni=floor($hod/24);
$hod=$hod%24;

$aktual = substr($matice,$dni,7);

$grafikon = str_split($matice);

echo "<table border=\"1\"><tr><td>";
// 10.12.2017 je 0;
for ($u = 0; $u < 365; $u++) {
    
    $datum=$matice_start+($u*86400);
    $datum_format = date("dm", $datum);
    $denvtydnu = date('w',$datum);
    echo "$datum_format <input type=\"text\" name=\"grafikon$u\" value=\"$grafikon[$u]\" size=\"1\"><br />";

    if ($denvtydnu == "0") {echo "</td><td>";}
}
echo "</td></tr></table>";
				
include 'footer.php';
?>
