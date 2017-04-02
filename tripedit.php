<?php
include 'header.php';

$trip = @$_GET['id'];
$action = @$_POST['action'];

switch ($action) {
	case "hlava" :
	
	$trip = $_POST['trip_id'];
	$linka = $_POST['route_id'];
	$trip_headsign = $_POST['headsign'];
	$smer = $_POST['smer'];
	$blok = $_POST['block_id'];
	$invalida = $_POST['invalida'];
	$cyklo = $_POST['cyklo'];
	$aktif = $_POST['aktif'];

	$ready0 = "UPDATE trip SET route_id='$linka', trip_headsign='$trip_headsign', direction_id='$smer', block_id='$blok', wheelchair_accessible='$invalida', bikes_allowed='$cyklo', active='$aktif' WHERE (trip_id = '$trip');";

	$aktualz0 = mysqli_query($link, $ready0);
	
	$vlak = substr($trip,0,-2);
	$lomeni = substr($vlak,-1);
	$nextvlak = $vlak+2;
	$nextlomeni = substr($nextvlak,-1);
	$nexttrip = $nextvlak.$nextlomeni."A";
	break;
	
	case "zastavky" :
	$trip = $_POST['trip_id'];
	
	for ($y = 0; $y < 40; $y++) {
			$$ind = $y;
			$arrindex = "arrive".${$ind};
			$arrival_time = $_POST[$arrindex];
			$depindex = "leave".${$ind};
			$departure_time = $_POST[$depindex];
			$rzmindex = "rezim".${$ind};
			$rzm = $_POST[$rzmindex];
			$pickup_type = substr($rzm,0,1);
			$drop_off_type = substr($rzm,1,1);
			$seqindex = "poradi".${$ind};
			$stop_sequence = $_POST[$seqindex];
			$nameindex = "stopname".${$ind};
			$stop_name = $_POST[$nameindex];
			$stpidindex = "stop_id".${$ind};
			$stop_id = $_POST[$stpidindex];
			
			$delindex = "delete".${$ind};
			$delete = $_POST[$delindex];
			switch ($delete) {
				case 1 : 
					$query58 = "DELETE FROM stoptime WHERE ((trip_id = '$trip') AND (stop_sequence = '$stop_sequence'));";
					$prikaz58 = mysqli_query($link, $query58);
				break;
				
				default : 
					$ready1 = "UPDATE stoptime SET arrival_time='$arrival_time', departure_time='$departure_time', pickup_type='$pickup_type', drop_off_type='$drop_off_type' WHERE ((trip_id ='$trip') AND (stop_sequence = '$stop_sequence'));";
					$aktualz1 = mysqli_query($link, $ready1);

					$ready2 = "UPDATE stop SET stop_name='$stop_name' WHERE (stop_id ='$stop_id');";
					$aktualz2 = mysqli_query($link, $ready2);
				break;
    		}
   	}
	break;
    
	case "grafikon" :
	    $trip = $_POST['trip_id'];
	    $grafi = "";
	    $invert = $_POST['invert'];
	    $altern = $_POST['altern'];
	    $proti = @$_POST['proti'];
	
   		switch ($invert) {
	   	case 1 :
	   		for ($v = 0; $v < 553; $v++) {
			$$ind = $v;
			$index = "grafikon".${$ind};
			$mtrx = $_POST[$index];
			
			switch ($mtrx) {
				case 1 : $grafi.="0";break;
				case 0 : $grafi.="1";break;			
	    		}
	   	}
	   		break;
	   		
	    default :
	    	for ($v = 0; $v < 553; $v++) {
			$$ind = $v;
			$index = "grafikon".${$ind};
			$mtrx = $_POST[$index];
			$grafi.=$mtrx;
		}
		}
		
		$denne = $_POST['denne'];
		if ($denne == 1) {$grafi = "1111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111";}

		if ($altern == "1") {
			$pom84 = mysqli_fetch_row(mysqli_query($link, "SELECT matice FROM trip WHERE (trip_id = '$proti');"));
		 	$matice = $pom84[0];

			$grafi = "";

			$grafikon = str_split($matice);
			for ($w = 0; $w < 553; $w++) {
				switch ($grafikon[$w]) {
				case 0 : $grafi.="1"; break;
				case 1 : $grafi.="0"; break;
				}			
			}
		}
		
	    $operace = "UPDATE trip SET matice='$grafi' WHERE (trip_id = '$trip');";
	    $vykonej = mysqli_query($link, $operace) or die(mysqli_error());
	break;
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

echo "<td colspan=\"2\"><a href = \"routeedit.php?id=$linka\">Zpět na linku</a><td>";
echo "<td><a href=\"tripedit.php?id=$nexttrip\">Další spoj</a></td>";
echo "<td colspan=\"2\"><a href = \"routecopy.php?id=$trip_id\">Nový spoj tohoto vlaku</a><td>";
echo "</tr><tr>";


echo "<form method=\"post\" action=\"tripedit.php\" name=\"hlava\">
		<input name=\"action\" value=\"hlava\" type=\"hidden\">
		<input name=\"trip_id\" value=\"$trip_id\" type=\"hidden\">";
echo "<td>$trip_id</td><td>Linka: <select name=\"route_id\">";

$query45 = "SELECT route_id, route_short_name, route_long_name FROM route ORDER BY route_short_name;";
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
echo "Invalida: <select name=\"invalida\"><option value=\"0\"";
if ($invalida == '0') {echo " SELECTED";}
echo "></option><option value=\"1\"";
if ($invalida == '1') {echo " SELECTED";}
echo ">Vlak vhodný pro přepravu</option><option value=\"2\"";
if ($invalida == '2') {echo " SELECTED";}
echo ">Vlak neumožňuje přepravu</option></select><br />";
echo "Cyklo: <select name=\"cyklo\"><option value=\"0\"";
if ($cyklo == '0') {echo " SELECTED";}
echo "></option><option value=\"1\"";
if ($cyklo == '1') {echo " SELECTED";}
echo ">Vlak vhodný pro přepravu</option><option value=\"2\"";
if ($cyklo == '2') {echo " SELECTED";}
echo ">Vlak neumožňuje přepravu</option></select>";
echo "</td>";
echo "<td>Aktivní <input type=\"checkbox\" name=\"aktif\" value=\"1\"";
if ($aktif == '1') {echo " CHECKED";}
echo "></td><td><input type=\"submit\"></td></tr></form>";
echo "<tr><td colspan=\"5\">";

$vlak = substr($trip_id,0,-2);
$lomeni = substr($vlak,-1);
$cislo7 = $vlak."/".$lomeni;

$query86 = "SELECT POZNAM FROM kango.OBP WHERE ((CISLO7='$cislo7'));";
if ($result86 = mysqli_query($link, $query86)) {
    while ($row86 = mysqli_fetch_row($result86)) {
	$poznamka = $row86[0];
				
	echo "$poznamka<br />";
	}
}
		
echo "</td></tr>";
echo "</table>";

echo "<table>";
echo "<tr><td>";
echo "<table>";
echo "<tr><th>Stanice</th><th>Příjezd</th><th><Odjezd</th><th>Režim</th><th></th></tr>";

echo "<form method=\"post\" action=\"tripedit.php\" name=\"zastavky\">
		<input name=\"action\" value=\"zastavky\" type=\"hidden\">
		<input name=\"trip_id\" value=\"$trip_id\" type=\"hidden\">";
$z = 0;

$query108 = "SELECT stoptime.stop_id,stoptime.arrival_time,stoptime.departure_time,stoptime.pickup_type,stoptime.drop_off_type,stoptime.stop_sequence, stop.stop_name FROM stoptime LEFT JOIN stop ON stoptime.stop_id = stop.stop_id WHERE (stoptime.trip_id = '$trip_id');";

if ($result108 = mysqli_query($link, $query108)) {
    while ($row108 = mysqli_fetch_row($result108)) {
	$stop_id = $row108[0];
	$arrival_time = $row108[1];
	$departure_time = $row108[2];
	$pickup_type = $row108[3];
	$drop_off_type = $row108[4];
	$stop_sequence = $row108[5];
	$nazev_stanice = $row108[6];

	echo "<tr><td><input name=\"stop_id$z\" value=\"$stop_id\" type=\"hidden\">
		<input name=\"poradi$z\" value=\"$stop_sequence\" type=\"hidden\">
		<input type=\"text\" name=\"stopname$z\" value=\"$nazev_stanice\"></td>";
	echo "<td><input type=\"text\" name=\"arrive$z\" value=\"$arrival_time\"></td>";
	echo "<td><input type=\"text\" name=\"leave$z\" value=\"$departure_time\"></td>";
	echo "<td><select name=\"rezim$z\"><option value=\"00\"></option>";
	echo "<option value=\"01\"";
	if ($drop_off_type == 1) {echo " SELECTED";}
	echo ">Pouze výstup</option>";
	echo "<option value=\"10\"";
	if ($pickup_type == 1) {echo " SELECTED";}
	echo ">Pouze nástup</option>";
	echo "<option value=\"33\"";
	if ($drop_off_type == 3) {echo " SELECTED";}
	echo ">Zastavuje na znamení</option>";
	echo "<select></td>";
	echo "<td><input type=\"checkbox\" name=\"delete$z\" value=\"1\"></td></tr>";
	$z = $z+1;
    }
}
echo "<input type=\"submit\"></form>";
echo "</table></td><td>";

$pom163 = mysqli_fetch_row(mysqli_query($link, "SELECT max(stop_sequence) FROM stoptime WHERE (trip_id = '$trip_id');"));
$max_trip = $pom163[0];

$pom129 = mysqli_fetch_row(mysqli_query($link, "SELECT min(stop_sequence) FROM stoptime WHERE (trip_id = '$trip_id');"));
$min_trip = $pom129[0];
//vymezení výchozího a konečného bodu
				
$lomeni = substr($trip_id,-2,1);
$vlak = substr($trip_id, 0, -2);
$cislo7 = $vlak."/".$lomeni;

echo "KALENDÁŘ<br />";

$query188 = "SELECT ZELEZN,ZST,OB,KALENDAR FROM kango.DTV WHERE ((KALENDAR != '') AND (CISLO7 = '$cislo7'));";
if ($result188 = mysqli_query($link, $query188)) {
    while ($row188 = mysqli_fetch_row($result188)) {
	$stopstat = $row188[0];
	$stopzst = $row188[1];
	$stopob = $row188[2];
	$kalend = $row188[3];
	$stop_id = substr($stopstat,-2).$stopzst.substr($stopob,-1);
	
	$pom194 = mysqli_fetch_row(mysqli_query($link, "SELECT stop_name FROM stop WHERE (stop_id = '$stop_id');"));
	$zahakal = $pom194[0];
	
	$pom197 = mysqli_fetch_row(mysqli_query($link, "SELECT OMEZ1 FROM kango.KVL WHERE (KALENDAR = '$kalend');"));
	$omeze = $pom197[0];
	
	if ($kalend == '1') {$omeze = "jede denně";}
	echo "Ze stanice $zahakal $omeze<br /><br />";
    }
}

echo "IDS<br />";
$query262 = "SELECT * FROM kango.IDV WHERE (CISLO7 = '$cislo7');";
if ($result262 = mysqli_query($link, $query262)) {
    while ($row262 = mysqli_fetch_row($result262)) {
	$startstat = $row262[2];
	$startzst = $row262[3];
	$startob = $row262[4];
	$stopstat = $row262[5];
	$stopzst = $row262[6];
	$stopob = $row262[7];
	$idsid = $row262[9];
	
	$start_id = substr($startstat,-2).$startzst.substr($startob,-1);
	$stop_id = substr($stopstat,-2).$stopzst.substr($stopob,-1);
	
	$pom276 = mysqli_fetch_row(mysqli_query($link, "SELECT stop_name FROM stop WHERE (stop_id = '$start_id');"));
	$zahaids = $pom276[0];
	$pom278 = mysqli_fetch_row(mysqli_query($link, "SELECT stop_name FROM stop WHERE (stop_id = '$stop_id');"));
	$ukoids = $pom278[0];
		
	$pom281 = mysqli_fetch_row(mysqli_query($link, "SELECT ZKRATKA FROM kango.IDS WHERE (IDIDS = '$idsid');"));
	$ids = $pom281[0];
	
	echo "V úseku $zahaids až $ukoids linka $ids.<br /><br />";
	}
}

/*echo "TRASA<br />";

$i = 0;
$prevstat= "";
$prevzst = "";
$prevob = "";
$vzdal = 0;

$query131 = "SELECT * FROM kango.DTV WHERE (CISLO7='$cislo7');";
if ($result131 = mysqli_query($link, $query131)) {
    while ($row131 = mysqli_fetch_row($result131))  {
	$stopstat = $row131[1];
	$stopzst = $row131[2];
	$stopob = $row131[3];
	$ZST = substr($stopstat,-2).$stopzst.substr($stopob,-1);
	$i = $i + 1;

	$pom139 = mysqli_fetch_row(mysqli_query($link, "SELECT stop_name,stop_lat,stop_lon FROM stop WHERE (stop_id='$ZST');"));
	$name = $pom139[0];
	$lat = $pom139[1];
	$lon = $pom139[2];
	
	$result235 = mysqli_query($link, "SELECT DELKA FROM kango.DU WHERE ((ZELEZN1 = '$prevstat') AND (ZST1 = '$prevzst') AND (OB1 = '$prevob') AND (ZELEZN2 = '$stopstat') AND (ZST2 = '$stopzst') AND (OB2 = '$stopob'));");
	$pom235 = mysqli_fetch_row($result235);
	$ujeto = $pom235[0];
	$radky = mysqli_num_rows($result235);
	if ($radky == 0) {
	    $result240 = mysqli_query($link, "SELECT DELKA FROM kango.DU WHERE ((ZELEZN2 = '$prevstat') AND (ZST2 = '$prevzst') AND (OB2 = '$prevob') AND (ZELEZN1 = '$stopstat') AND (ZST1 = '$stopzst') AND (OB1 = '$stopob'));");
	    $pom240 = mysqli_fetch_row($result240);
	    $ujeto = $pom240[0];
   	} 
	$vzdal = $vzdal + $ujeto;
	$prevstat = $stopstat;
	$prevzst = $stopzst;
	$prevob = $stopob;
	if ($lat != '' && $lon != '' && $i <= $max_trip && $i >= $min_trip) {
	    if ($i == $min_trip) {$vzdal = 0;} 
	    echo "$name - $lat - $lon - $i - $vzdal<br />";
	}
    }
}*/
echo "</td></tr>";
echo "</table>";

echo "</td></tr></table>";

echo "<form method=\"post\" action=\"tripedit.php\" name=\"grafikon\">
		<input name=\"action\" value=\"grafikon\" type=\"hidden\">
		<input name=\"trip_id\" value=\"$trip_id\" type=\"hidden\">";

echo "<input type=\"checkbox\" name=\"denne\" value=\"1\"> Jede denně";
echo "<input type=\"checkbox\" name=\"invert\" value=\"1\"> Invertuj";
echo "<input type=\"checkbox\" name=\"altern\" value=\"1\"> Alternace <input type=\"text\" name=\"proti\" value=\"\">";

// Matice začíná 11.12.2016 
$matice_start = mktime(0,0,0,12,11,2016);
$grafikon = str_split($matice);
echo "<table border=\"1\"><tr><td>";
// 11.12.2016 je 0;
for ($u = 0; $u < 553; $u++) {
    
    $datum=$matice_start+($u*86400);
    $datum_format = date("d.m.", $datum);
    $denvtydnu = date('w',$datum);
    if ($grafikon[$u] == "1") {echo "<span style=\"background-color:green;\">";}
    echo "$datum_format<br /><input type=\"radio\" name=\"grafikon$u\" value=\"0\"";
    if ($grafikon[$u] == "0") {echo " CHECKED";}
    echo "><input type=\"radio\" name=\"grafikon$u\" value=\"1\"";
    if ($grafikon[$u] == "1") {echo " CHECKED";}
    echo "><br />";
    if ($grafikon[$u] == "1") {echo "</span>";}
	
    if ($denvtydnu == "0") {echo "</td><td>";}
}
echo "</td></tr></table>";
echo "<input type=\"submit\"></form>";


include 'footer.php';
?>
