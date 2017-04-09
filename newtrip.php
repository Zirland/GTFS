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

		$ready0 = "INSERT INTO trip (trip_id, route_id, trip_headsign, direction_id, block_id, wheelchair_accessible, bikes_allowed, active) VALUES ('$trip', '$linka', '$trip_headsign','$smer','$blok','$invalida','$cyklo','$aktif');";
		$aktualz0 = mysqli_query($link, $ready0);
	break;

	case "zastavky" :
		$trip = $_POST['trip_id'];
		$stop_id = $_POST['stop_id'];
		$arrival = $_POST['arrive'];
		$departure = $_POST['leave'];
		$rezim = $_POST['rezim'];
		$pickup_type = substr($rezim,0,1);
		$drop_off_type = substr($rezim,1,1);
		$last_poradi = $_POST['last_poradi'];
		$last_id = $_POST['last_id'];
		$last_prubeh = $_POST['last_prubeh'];

$_distArr = array();

$query = "SELECT STOP1, STOP2, DELKA from kango.DU_pom;";
if ($result = mysqli_query($link, $query)) {
    while ($row = mysqli_fetch_row($result)) {
		$id1 = $row[0];
		$id2 = $row[1];
		$delka = $row[2];

	$_distArr[$id1][$id2] = $delka;
    }
}

//the start and the end
$a = $last_id;
$b = $stop_id;
$z = $last_poradi;
$prubeh = $last_prubeh;

//initialize the array for storing
$S = array();//the nearest path with its parent and weight
$Q = array();//the left nodes without the nearest path
foreach(array_keys($_distArr) as $val) $Q[$val] = 9999999;
$Q[$a] = 0;

//start calculating
while(!empty($Q)){
    $min = array_search(min($Q), $Q);//the most min weight
    if($min == $b) break;
    foreach($_distArr[$min] as $key=>$val) if(!empty($Q[$key]) && $Q[$min] + $val < $Q[$key]) {
        $Q[$key] = $Q[$min] + $val;
        $S[$key] = array($min, $Q[$key]);
    }
    unset($Q[$min]);
}

//list the path
$path = array();
$pos = $b;

while($pos != $a){
    $path[] = $pos;
    $pos = $S[$pos][0];
}
$path = array_reverse($path);

//print result
echo "<br />From $a to $b";
echo "<br />The length is ".$S[$b][1];
echo "<br />Path is ";
foreach ($path as $prujezd) {
	echo "$prujezd - $z <br />";
	$z = $z + 1;
	$prubeh .= $prujezd;
}

		$ready1 = "INSERT INTO stoptime (stop_id, trip_id, arrival_time, departure_time, pickup_type, drop_off_type, stop_sequence) VALUES ('$stop_id','$trip','$arrival','$departure','$pickup_type','$drop_off_type','$z');";
		$aktualz1 = mysqli_query($link, $ready1);
		
		$ready2 = "UPDATE trip SET shape_id = '$prubeh' WHERE trip_id = '$trip';";
		$aktualz2 = mysqli_query($link, $ready2);
		
		$ready3 = "DELETE FROM kango.forceshape WHERE trip_id = '$trip';";
		echo $ready3;
		$aktualz3 = mysqli_query($link, $ready3);

		$ready4 = "INSERT INTO kango.forceshape (trip_id, shape_id) VALUES ('$trip', '$prubeh');";
		echo $ready4;
		$aktualz4 = mysqli_query($link, $ready4);
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

echo "<td colspan=\"5\"><a href = \"routeedit.php?id=$linka\">Zpět na linku</a><td>";
echo "</tr><tr>";


echo "<form method=\"post\" action=\"newtrip.php\" name=\"hlava\">
		<input name=\"action\" value=\"hlava\" type=\"hidden\">";
echo "<td>Trip: <input name=\"trip_id\" value=\"$trip\" type=\"text\"></td><td>Linka: <select name=\"route_id\">";

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
echo "</table>";

echo "<table>";
echo "<tr><td>";
echo "<table>";
echo "<tr><th>Stanice</th><th>Příjezd</th><th>Odjezd</th><th>Režim</th><th>Vzdálenost</th></tr>";

echo "<form method=\"post\" action=\"newtrip.php\" name=\"zastavky\">
		<input name=\"action\" value=\"zastavky\" type=\"hidden\">
		<input name=\"trip_id\" value=\"$trip\" type=\"hidden\">";
$posledni_seq = 0;

$query108 = "SELECT stop_id,arrival_time,departure_time,pickup_type,drop_off_type,stop_sequence FROM stoptime WHERE (trip_id = '$trip');";
if ($result108 = mysqli_query($link, $query108)) {
    while ($row108 = mysqli_fetch_row($result108)) {
	$stop_id = $row108[0];
	$arrival_time = $row108[1];
	$departure_time = $row108[2];
	$pickup_type = $row108[3];
	$drop_off_type = $row108[4];
	$stop_sequence = $row108[5];

	$pom240 = mysqli_fetch_row(mysqli_query($link, "SELECT shape_id FROM trip WHERE trip_id = '$trip';"));
	$lastprubeh = $pom240[0];
	
	$query184 = "SELECT stop_name FROM stop WHERE stop_id = '$stop_id';";
	$pom118 = mysqli_fetch_row(mysqli_query($link, $query184));
	$nazev_stanice = $pom118[0];
	
	echo "<tr><td>$nazev_stanice</td>";
	echo "<td>$arrival_time</td>";
	echo "<td>$departure_time</td>";
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
    }
	$posledni_seq = $stop_sequence;
	$posledni_id = $stop_id;
	echo "Poslední $posledni_seq - $posledni_id";
	
	echo "<tr><td><input name=\"last_id\" value=\"$posledni_id\" type=\"hidden\">
		<input name=\"last_prubeh\" value=\"$lastprubeh\" type=\"hidden\">
		<input name=\"last_poradi\" value=\"$posledni_seq\" type=\"hidden\">";
	echo "<select name=\"stop_id\"><option value=\"\">-----</option>";
	$query194 = "SELECT stop_id, stop_name FROM stop ORDER BY stop_name;";
	if ($result194 = mysqli_query($link, $query194)) {
		while ($row194 = mysqli_fetch_row($result194)) {
			$stopid = $row194[0];
			$stopname = $row194[1];

			echo "<option value=\"$stopid\">$stopname</option>";
		}
	}
	echo "</select></td>";
	echo "<td><input type=\"text\" name=\"arrive\" value=\"\"></td>";
	echo "<td><input type=\"text\" name=\"leave\" value=\"\"></td>";
	echo "<td><select name=\"rezim$z\"><option value=\"00\"></option>";
	echo "<option value=\"01\">Pouze výstup</option>";
	echo "<option value=\"10\">Pouze nástup</option>";
	echo "<option value=\"33\">Zastavuje na znamení</option>";
	echo "<select></td>";
	echo "</tr>";
}
echo "<input type=\"submit\"></form>";
echo "</table></td><td>";
echo "</td></tr>";
echo "</table>";

echo "</td></tr></table>";

echo "<form method=\"post\" action=\"newtrip.php\" name=\"grafikon\">
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
