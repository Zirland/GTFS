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

		$ready0 = "INSERT INTO trip (trip_id, shape_id, route_id, trip_headsign, direction_id, block_id, wheelchair_accessible, bikes_allowed, active) VALUES ('$trip', '$trip', '$linka', '$trip_headsign','$smer','$blok','$invalida','$cyklo','$aktif');";
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
		$stop_sequence = $_POST['poradi'];
		$distance = $_POST['distance'];

		$ready1 = "INSERT INTO stoptime (stop_id, trip_id, arrival_time, departure_time, pickup_type, drop_off_type, stop_sequence) VALUES ('$stop_id','$trip','$arrival','$departure','$pickup_type','$drop_off_type','$stop_sequence');";
		$aktualz1 = mysqli_query($link, $ready1);

		$pom38 = mysqli_fetch_row(mysqli_query($link, "SELECT stop_lat, stop_lon FROM stop WHERE (stop_id = '$stop_id');"));
		$lat = $pom38[0];
		$lon = $pom38[1];
	
		$ready2 = "INSERT INTO force_shape (shape_id, shape_pt_lat, shape_pt_lon, shape_pt_sequence, shape_dist_traveled) VALUES ('$trip','$lat','$lon','$stop_sequence', '$distance');";
		$aktualz2 = mysqli_query($link, $ready2);
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
$z = 1;

$query108 = "SELECT stop_id,arrival_time,departure_time,pickup_type,drop_off_type,stop_sequence FROM stoptime WHERE (trip_id = '$trip');";
if ($result108 = mysqli_query($link, $query108)) {
    while ($row108 = mysqli_fetch_row($result108)) {
	$stop_id = $row108[0];
	$arrival_time = $row108[1];
	$departure_time = $row108[2];
	$pickup_type = $row108[3];
	$drop_off_type = $row108[4];
	$stop_sequence = $row108[5];

	$query184 = "SELECT stop_name FROM stop WHERE stop_id = '$stop_id';";
	$pom118 = mysqli_fetch_row(mysqli_query($link, $query184));
	$nazev_stanice = $pom118[0];
	
	$ready188 = "SELECT shape_dist_traveled FROM force_shape WHERE (shape_id='$trip' AND shape_pt_sequence = '$stop_sequence');";
	$pom188 = mysqli_fetch_row(mysqli_query($link, $ready188));
	$kilometry = $pom188[0];

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
	echo "<td>$kilometry</td></tr>";
	$z = $z+1;
    }
	
	echo "<tr><td><input name=\"stop_id\" value=\"$stop_id\" type=\"hidden\">
		<input name=\"poradi\" value=\"$z\" type=\"hidden\">";
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
	echo "<td><input type=\"text\" name=\"distance\" value=\"\"></td></tr>";
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
