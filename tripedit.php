<?php
include 'header.php';

$trip = @$_GET['id'];
$action = @$_POST['action'];
$rmv = @$_GET['del'];	

if ($rmv != '') {$removal = mysqli_query ($link, "DELETE FROM stoptime WHERE ((trip_id = '$trip') AND (stop_sequence = '$rmv'));");}

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
	$trip = $nextvlak.$nextlomeni."A";
	break;
	
	case "zastavky" :
	$trip = $_POST['trip_id'];
	$arrival_time = $_POST['arrive'];
	$departure_time = $_POST['leave'];
	$rzm = $_POST['rezim'];
	$pickup_type = substr($rzm,0,1);
	$drop_off_type = substr($rzm,1,1);
	$stop_sequence = $_POST['poradi'];
	
	$ready1 = "UPDATE stoptime SET arrival_time='$arrival_time', departure_time='$departure_time', pickup_type='$pickup_type', drop_off_type='$drop_off_type' WHERE ((trip_id ='$trip') AND (stop_sequence = '$stop_sequence'));";

	$aktualz1 = mysqli_query($link, $ready1);
	break;
    
	case "grafikon" :
	    $trip = $_POST['trip_id'];
	    $grafi = "";
	    for ($v = 0; $v < 553; $v++) {
		$$ind = $v;
		$index = "grafikon".${$ind};
		$mtrx = $_POST[$index];
		$grafi.=$mtrx;
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

echo "<td colspan=\"3\"><a href = \"routeedit.php?id=$linka\">Zpět na linku</a><td>";
echo "<td colspan=\"2\"><a href = \"routecopy.php?id=$trip_id\">Nový spoj tohoto vlaku</a><td>";
echo "</tr><tr>";


echo "<form method=\"post\" action=\"tripedit.php\" name=\"hlava\">
		<input name=\"action\" value=\"hlava\" type=\"hidden\">
		<input name=\"trip_id\" value=\"$trip_id\" type=\"hidden\">";
echo "<td>$trip_id</td><td>Linka: <select name=\"route_id\">";

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

echo "<form method=\"post\" action=\"tripedit.php\" name=\"zastavky\">
		<input name=\"action\" value=\"zastavky\" type=\"hidden\">
		<input name=\"trip_id\" value=\"$trip_id\" type=\"hidden\">";
echo "<table>";
echo "<tr><td>";
echo "<table>";
echo "<tr><th>Stanice</th><th>Příjezd</th><th><Odjezd</th><th>Režim</th><th></th></tr>";

$query108 = "SELECT stop_id,arrival_time,departure_time,pickup_type,drop_off_type,stop_sequence FROM stoptime WHERE (trip_id = '$trip_id');";
if ($result108 = mysqli_query($link, $query108)) {
    while ($row108 = mysqli_fetch_row($result108)) {
	$stop_id = $row108[0];
	$arrival_time = $row108[1];
	$departure_time = $row108[2];
	$pickup_type = $row108[3];
	$drop_off_type = $row108[4];
	$stop_sequence = $row108[5];

	$pom118 = mysqli_fetch_row(mysqli_query($link, "SELECT stop_name FROM stop WHERE stop_id = '$stop_id';"));
	$nazev_stanice = $pom118[0];
	
echo "<form method=\"post\" action=\"tripedit.php\" name=\"zastavky\">
		<input name=\"action\" value=\"zastavky\" type=\"hidden\">
		<input name=\"trip_id\" value=\"$trip_id\" type=\"hidden\">
		<input name=\"poradi\" value=\"$stop_sequence\" type=\"hidden\">";

	echo "<tr><td>$nazev_stanice</td>";
	echo "<td><input type=\"text\" name=\"arrive\" value=\"$arrival_time\"></td>";
	echo "<td><input type=\"text\" name=\"leave\" value=\"$departure_time\"></td>";
	echo "<td><select name=\"rezim\"><option value=\"00\"></option>";
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
	echo "<td><a href=\"tripedit.php?id=$trip_id&del=$stop_sequence\">Vymazat</a><input type=\"submit\"></form></td></tr>";
    }
}
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

$query188 = "SELECT ZST,KALENDAR FROM kango.DTV WHERE ((KALENDAR != '') AND (CISLO7 = '$cislo7'));";
if ($result188 = mysqli_query($link, $query188)) {
    while ($row188 = mysqli_fetch_row($result188)) {
	$stop_id = $row188[0];
	$kalend = $row188[1];
	
	$pom194 = mysqli_fetch_row(mysqli_query($link, "SELECT stop_name FROM stop WHERE (stop_id = '$stop_id');"));
	$zahakal = $pom194[0];
	
	$pom197 = mysqli_fetch_row(mysqli_query($link, "SELECT OMEZ1 FROM kango.KVL WHERE (KALENDAR = '$kalend');"));
	$omeze = $pom197[0];
	
	if ($kalend == '1') {$omeze = "jede denně";}
	echo "Ze stanice $zahakal $omeze<br /><br /><br />";
    }
}

echo "TRASA<br />";

$i = 0;
$query131 = "SELECT * FROM kango.DTV WHERE (CISLO7='$cislo7');";
if ($result131 = mysqli_query($link, $query131)) {
    while ($row131 = mysqli_fetch_row($result131))  {
	$ZST = $row131[2];
	$i = $i + 1;
		
	$pom139 = mysqli_fetch_row(mysqli_query($link, "SELECT stop_name,stop_lat,stop_lon FROM stop WHERE (stop_id='$ZST');"));
	$name = $pom139[0];
	$lat = $pom139[1];
	$lon = $pom139[2];

	if ($lat != '' && $lon != '' && $i <= $max_trip && $i >= $min_trip) {
	    echo "$name - $lat - $lon - $i<br />";
	}
    }
}
echo "</td></tr>";
echo "</table>";

echo "</td></tr></table>";

echo "<form method=\"post\" action=\"tripedit.php\" name=\"grafikon\">
		<input name=\"action\" value=\"grafikon\" type=\"hidden\">
		<input name=\"trip_id\" value=\"$trip_id\" type=\"hidden\">";

// Matice začíná 11.12.2016 
$matice_start = mktime(0,0,0,12,11,2016);
$grafikon = str_split($matice);

echo "<table border=\"1\"><tr><td>";
// 11.12.2016 je 0;
for ($u = 0; $u < 553; $u++) {
    
    $datum=$matice_start+($u*86400);
    $datum_format = date("dm", $datum);
    $denvtydnu = date('w',$datum);
    echo "$datum_format <input type=\"text\" name=\"grafikon$u\" value=\"$grafikon[$u]\" size=\"1\"><br />";

    if ($denvtydnu == "0") {echo "</td><td>";}
}
echo "</td></tr></table>";
echo "<input type=\"submit\"></form>";


include 'footer.php';
?>
