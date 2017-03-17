<?php
include 'header.php';

$trip = $_GET['id'];
$action = $_POST['action'];

switch ($action) {
	case "oprav" :
	
	$trip = $_POST['trip_id'];
	$linka = $_POST['route_id'];
	$matice = $_POST['matice'];
	$trip_headsign = $_POST['headsign'];
	$smer = $_POST['smer'];
	$blok = $_POST['block_id'];
	$invalida = $_POST['invalida'];
	$cyklo = $_POST['cyklo'];
	$aktif = $_POST['aktif'];

	$ready0 = "UPDATE trip SET route_id='$linka', matice='$matice', trip_headsign='$trip_headsign', direction_id='$smer', block_id='$blok', wheelchair_accessible='$invalida', bikes_allowed='$cyklo', active='$aktif' WHERE (trip_id = '$trip');";
	
	$aktualz0 = mysqli_query($link, $ready0);
	
}

echo "<table><tr><td>";
echo "<table>";
echo "<tr>";

$hlavicka = mysqli_fetch_row(mysqli_query($link, "SELECT * FROM trip WHERE (trip_id='$trip');"));
	$trip_id = $hlavicka[2];
	$linka = $hlavicka[0];
	$matice = $hlavicka[1];
	$trip_headsign = $hlavicka[3];
	$smer = $hlavicka[5];
	$blok = $hlavicka[6];
	$invalida = $hlavicka[8];
	$cyklo = $hlavicka[9];
	$aktif = $hlavicka[10];

echo "<form method=\"post\" action=\"tripedit.php\" name=\"oprav\">
		<input name=\"action\" value=\"oprav\" type=\"hidden\">
		<input name=\"trip_id\" value=\"$trip_id\" type=\"hidden\">
		<input name=\"matice\" value=\"$matice\" type=\"hidden\">";
echo "<td>Linka: <select name=\"route_id\">";

$query45 = "SELECT route_id, route_short_name, route_long_name FROM route ORDER BY route_id;";
if ($result45 = mysqli_query($link, $query45)) {
	while ($row45 = mysqli_fetch_row($result45)) {
		$roid = $row45[0];
		$roshname = $row45[1];
		$rolgname = $row45[2];

		echo "<option value=\"$roid\"";
		if ($roid == $linka) {echo " SELECTED";}
		echo ">$roshname - $rolgname</option>";
	}
}
echo "</select></td><td>Směr: <input type=\"text\" name=\"headsign\" value=\"$trip_headsign\"><br />";
echo "<select name=\"smer\"><option value=\"0\"";
if ($smer=='0') {echo " SELECTED";}
echo ">Odchozí</option><option value=\"1\"";
if ($smer=='1') {echo " SELECTED";}
echo ">Příchozí</option></select></td>";
echo "<td>Blok <input type=\"text\" name=\"block_id\" value=\"$blok\"><br/>";
echo "Invalida: <input type=\"checkbox\" name=\"invalida\" value=\"1\"";
if ($invalida == '1') {echo " CHECKED";}
echo "> Cyklista: <input type=\"checkbox\" name=\"cyklo\" value=\"1\"";
if ($cyklo == '1') {echo " CHECKED";}
echo "></td>";
echo "<td>Aktivní <input type=\"checkbox\" name=\"aktif\" value=\"1\"";
if ($aktif == '1') {echo " CHECKED";}
echo "></td><td><input type=\"submit\"></td></tr></form></table>";

/*
		echo "<table>";
		echo "<tr><th>Linky odchozí</th><th>Linky příchozí</th></tr>";
		echo "<tr><td>";
		
		$query80 = "SELECT * FROM trip WHERE ((route_id = $route_id) AND (direction_id = '0')) ORDER BY trip_id;";
		if ($result80 = mysqli_query($link, $query80)) {
			while ($row80 = mysqli_fetch_row($result80)) {
				$trip_id = $row80[2];
				$trip_headsign = $row80[3];
				$trip_aktif = $row80[10];
				
				$vlak = substr($trip_id,0,-2);
				
				if ($trip_aktif == '0') {echo "X ";}
				echo "$vlak - $trip_headsign - <a href=\"tripedit.php?id=$trip_id\">Upravit</a><br />";
			}
		}
		echo "</td><td>";
		
		$query96 = "SELECT * FROM trip WHERE ((route_id = $route_id) AND (direction_id = '1')) ORDER BY trip_id;";
		if ($result96 = mysqli_query($link, $query96)) {
			while ($row96 = mysqli_fetch_row($result96)) {
				$trip_id = $row96[2];
				$trip_headsign = $row96[3];
				$trip_aktif = $row96[10];
				
				$vlak = substr($trip_id,0,-2);
				
				if ($trip_aktif == '0') {echo "X ";}
				echo "$vlak - $trip_headsign - <a href=\"tripedit.php?id=$trip_id\">Upravit</a><br />";
			}
		}	
		echo "</td></tr></table>";

/*

$datum_od = date("j.n.Y", mktime(0, 0, 0, substr($datodvl,2,2), substr($datodvl,0,2), substr($datodvl,-4)));
$datum_do = date("j.n.Y", mktime(0, 0, 0, substr($datdovl,2,2), substr($datdovl,0,2), substr($datdovl,-4)));

$pom1 = mysqli_fetch_row(mysqli_query($link, "SELECT agency_name FROM agency WHERE (agency_id='$iddop');"));
$dopravce = $pom1[0];

$trip_id = $vlak.$lomeni."A";



$query = "SELECT * FROM kango.DTV WHERE (CISLO7='$cislo7');";
if ($result = mysqli_query($link, $query)) {
	while ($row = mysqli_fetch_row($result)) {
		$ZST = $row[2];
		$DP = $row[7];
		$HP = $row[8];
		$MP = $row[9];
		$SP = $row[10];
		$DO = $row[13];
		$HO = $row[14];
		$MO = $row[15];
		$SO = $row[16];
		$calendar = $row[37];

		$pom2 = mysqli_fetch_row(mysqli_query($link, "SELECT NAZEVDB FROM kango.DB WHERE (ZST='$ZST');"));
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
		echo "<td>$stanice</td>";
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
		$ZST = $row1[2];
		$DP = $row1[7];
		$HP = $row1[8];
		$MP = $row1[9];
		$SP = $row1[10];
		$DO = $row1[13];
		$HO = $row1[14];
		$MO = $row1[15];
		$SO = $row1[16];
		$i = $i + 1;
		
		$pom4 = mysqli_fetch_row(mysqli_query($link, "SELECT stop_name FROM stop WHERE (stop_id='$ZST');"));
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
			echo "<td>$ZST</td>";
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
		$ZST = $row2[2];
		$i = $i + 1;
		
		
		$pom4 = mysqli_fetch_row(mysqli_query($link, "SELECT stop_lat,stop_lon FROM stop WHERE (stop_id='$ZST');"));
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
	case '1': $matice="1111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111";
		break;
	default : $pom6 = mysqli_fetch_row(mysqli_query($link, "SELECT * FROM kango.KVL WHERE (KALENDAR ='$calpom');"));
		$dd1 = $pom6[0];
		$mm1 = $pom6[1];
		$rr1 = $pom6[2];
		$matice = $pom6[7];
		break;
}

echo "</td></tr></table>";

echo $matice."<br />";

// Matice začíná 11.12.2016 
$matice_start = mktime(0,0,0,$mm1,$dd1,$rr1);
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

echo "Aktuálně začíná $vtydnu ".$calendar_start_format." do ".$calendar_stop_format." matice ". $aktual .".\n";

$adjust = substr($aktual,-$vtydnu).substr($aktual,0,-$vtydnu);

$dec=bindec($adjust)+1;

echo "Kalendář je ".$adjust." = ".$dec;

*/

include 'footer.php';
?>
