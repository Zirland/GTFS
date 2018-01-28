<?php
include 'header.php';

$trip = @$_GET['id'];
$action = @$_POST['action'];
$nucenatrasa = @$_GET['trasa'];

if ($nucenatrasa) {
	$vynucena = mysqli_query ($link, "UPDATE trip SET shape_id = (SELECT shape_id FROM kango.forceshape WHERE trip_id = '$trip') WHERE trip_id = '$trip';"); 
}

switch ($action) {
	case "hlava" :
		$trip = $_POST['trip_id'];
		$linka = $_POST['route_id'];
		$smer = $_POST['smer'];
		$blok = $_POST['block_id'];
		$invalida = $_POST['invalida'];
		$cyklo = $_POST['cyklo'];
		$aktif = $_POST['aktif'];

		$ready0 = "UPDATE trip SET route_id='$linka', direction_id='$smer', block_id='$blok', wheelchair_accessible='$invalida', bikes_allowed='$cyklo', active='$aktif' WHERE (trip_id = '$trip');";

		$aktualz0 = mysqli_query ($link, $ready0);
	break;

	case "zastavky" :
		$trip = $_POST['trip_id'];
		$pocet = $_POST['pocet'];

		for ($y = 0; $y < $pocet; $y++) {
			$$ind = $y;
			$arrindex = "arrive".${$ind};
			$arrival_time = $_POST[$arrindex];
			$depindex = "leave".${$ind};
			$departure_time = $_POST[$depindex];
			$rzmindex = "rezim".${$ind};
			$rzm = $_POST[$rzmindex];
			$pickup_type = substr ($rzm,0,1);
			$drop_off_type = substr ($rzm,1,1);
			$seqindex = "poradi".${$ind};
			$stop_sequence = $_POST[$seqindex];
			$nameindex = "stopname".${$ind};
			$stop_name = $_POST[$nameindex];
			$stpidindex = "stop_id".${$ind};
			$stop_id = $_POST[$stpidindex];
			$stp2idindex = "stop2_id".${$ind};
			$stop2_id = $_POST[$stp2idindex];
			$rertindex = "reroute".${$ind};
			$reroute = $_POST[$rertindex];

			$delindex = "delete".${$ind};
			$delete = $_POST[$delindex];

			if ($reroute == 1) {
				$query54 = "UPDATE stoptime SET stop_id = '$stop2_id' WHERE ((trip_id = '$trip') AND (stop_sequence = '$stop_sequence'));";
				$prikaz54 = mysqli_query ($link, $query54);
			}

			switch ($delete) {
				case 1 :
					$query58 = "DELETE FROM stoptime WHERE ((trip_id = '$trip') AND (stop_sequence = '$stop_sequence'));";
					$prikaz58 = mysqli_query ($link, $query58);
				break;

				default :
					$ready1 = "UPDATE stoptime SET arrival_time='$arrival_time', departure_time='$departure_time', pickup_type='$pickup_type', drop_off_type='$drop_off_type' WHERE ((trip_id ='$trip') AND (stop_sequence = '$stop_sequence'));";
					$aktualz1 = mysqli_query ($link, $ready1);

					$ready2 = "UPDATE stop SET stop_name='$stop_name' WHERE (stop_id ='$stop_id');";
					$aktualz2 = mysqli_query ($link, $ready2);
				break;
			}
		}

		if (strpos ($trip, 'F') !== false) {
			$vlak = substr ($trip,1,-2);
		} else {
			$vlak = substr ($trip,0,-2);
		}

		$lomeni = substr ($vlak,-1);
		$cislo7 = $vlak."/".$lomeni;

		$pom163 = mysqli_fetch_row (mysqli_query ($link, "SELECT max(stop_sequence) FROM stoptime WHERE (trip_id = '$trip');"));
		$max_trip = $pom163[0];

		$pom129 = mysqli_fetch_row (mysqli_query ($link, "SELECT min(stop_sequence) FROM stoptime WHERE (trip_id = '$trip');"));
		$min_trip = $pom129[0];

		$pomfinstop=mysqli_fetch_row (mysqli_query ($link, "SELECT stop_id FROM stoptime WHERE (trip_id='$trip' AND stop_sequence='$max_trip');"));
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

		$tvartrasy = "";
		$i = 0;

		$query131 = "SELECT * FROM kango.DTV WHERE (CISLO7='$cislo7');";
		if ($result131 = mysqli_query ($link, $query131)) {
			while ($row131 = mysqli_fetch_row ($result131)) {
				$stopstat = $row131[1];
				$stopzst = $row131[2];
				$stopob = $row131[3];
				$ZST = substr ($stopstat,-2).$stopzst.substr ($stopob,-1);
				$i = $i + 1;

				if ($i <= $max_trip && $i >= $min_trip) {
					$tvartrasy .= $ZST."|";
				}
			}
		}

		$dotaz86 = "UPDATE trip SET trip_headsign = '$headsign', shape_id = '$tvartrasy' WHERE trip_id = '$trip';";
		$prikaz86 = mysqli_query ($link, $dotaz86);
	break;

	case "grafikon" :
		$trip = $_POST['trip_id'];
		$grafi = "";
		$invert = $_POST['invert'];
		$altern = $_POST['altern'];
		$proti = @$_POST['proti'];
		
		switch ($invert) {
			case 1 :
				for ($v = 0; $v < 365; $v++) {
					$$ind = $v;
					$index = "grafikon".${$ind};
					$mtrx = $_POST[$index];

					switch ($mtrx) {
						case 1 :
							$grafi .= "0";
						break;
						case 0 :
							$grafi .= "1";
						break;
					}
				}
			break;

			default :
				for ($v = 0; $v < 365; $v++) {
					$$ind = $v;
					$index = "grafikon".${$ind};
					$mtrx = $_POST[$index];
					$grafi.=$mtrx;
				}
			break;
		}

		$denne = $_POST['denne'];
		if ($denne == 1) {
			$grafi = "";
			for ($i = 0; $i < 365; $i++) {
				$grafi .= "1";
			}
		}

		if ($altern == "1") {
			$pom84 = mysqli_fetch_row (mysqli_query ($link, "SELECT matice FROM trip WHERE (trip_id = '$proti');"));
			$matice = $pom84[0];
			$grafi = "";

			$grafikon = str_split ($matice);
			for ($w = 0; $w < 365; $w++) {
				switch ($grafikon[$w]) {
					case 0 :
						$grafi .= "1";
					break;
					case 1 :
						$grafi .= "0";
					break;
				}
			}
		}

		$maticestart = mktime (0,0,0,12,10,2017);
		$typkodu = @$_POST['typkodu'];
		$datumod = @$_POST['datumod'];
		$datumdo = @$_POST['datumdo'];
		if ($datumdo == "") {
			$datumdo = $datumod;
		}

		switch ($typkodu) {
			case "0" :
			break;
			case "1" : // echo "jede od ".$datumod." do ".$datumdo."<br/>"; 
				$Dod = substr ($datumod,0,2); $Mod = substr ($datumod,2,2); $Yod = substr ($datumod,-4); $timeod = mktime (0,0,0,$Mod, $Dod, $Yod); 
				$zacdnu = round(($timeod - $maticestart) / 86400); 
				$Ddo = substr ($datumdo,0,2); $Mdo = substr ($datumdo,2,2); $Ydo = substr ($datumdo,-4); $timedo = mktime (0,0,0,$Mdo, $Ddo, $Ydo); 
				$kondnu = round(($timedo - $maticestart) / 86400); 

				for ($g = 0; $g < 365; $g++) {
					if ($g>=$zacdnu && $g <=$kondnu) {$grafi[$g] = 1;}
				}
			break;

			case "4" : // echo "nejede od ".$datumod." do ".$datumdo."<br/>";
				$Dod = substr ($datumod,0,2); $Mod = substr ($datumod,2,2); $Yod = substr ($datumod,-4); $timeod = mktime (0,0,0,$Mod, $Dod, $Yod);
				$zacdnu = round(($timeod - $maticestart) / 86400); 
				$Ddo = substr ($datumdo,0,2); $Mdo = substr ($datumdo,2,2); $Ydo = substr ($datumdo,-4); $timedo = mktime (0,0,0,$Mdo, $Ddo, $Ydo); 
				$kondnu = round(($timedo - $maticestart) / 86400); 

				for ($g = 0; $g < 365; $g++) {
					if ($g>=$zacdnu && $g <=$kondnu) {$grafi[$g] = 0;}
				}
			break;
		}

		$operace = "UPDATE trip SET matice='$grafi' WHERE (trip_id = '$trip');";
		$vykonej = mysqli_query ($link, $operace) or die (mysqli_error ());
	break;
}

echo "<table><tr><td>";
echo "<table>";
echo "<tr>";

$hlavicka = mysqli_fetch_row (mysqli_query ($link, "SELECT * FROM trip WHERE (trip_id='$trip');"));
	$trip_id = $hlavicka[2];
	$linka = $hlavicka[0];
	$matice = $hlavicka[1];
	$trip_headsign = $hlavicka[3];
	$smer = $hlavicka[5];
	$blok = $hlavicka[6];
	$shape = $hlavicka[7];
	$invalida = $hlavicka[8];
	$cyklo = $hlavicka[9];
	$aktif = $hlavicka[10];

echo "<td><a href = \"routeedit.php?id=$linka\">Zpět na linku</a><td>";
echo "<td><form method=\"get\" action=\"tripedit.php\" name=\"id\"><input type=\"text\" name=\"id\" value=\"\"><input type=\"submit\"></form><td>";
echo "<td><a href=\"routecopy.php?id=$trip_id\" target=\"_blank\">Nový trip</a></td>";
echo "<td><a href=\"zajebal.php?err=$trip_id\" target=\"_blank\">Zajebal</a></td>";
echo "<td><a href=\"tripdelete.php?trip=$trip_id\" target=\"_blank\">Smazat trip</a></td>";
echo "</tr><tr>";


echo "<form method=\"post\" action=\"tripedit.php\" name=\"hlava\"><input name=\"action\" value=\"hlava\" type=\"hidden\"><input name=\"trip_id\" value=\"$trip_id\" type=\"hidden\">";
echo "<td>$trip_id</td><td>Linka: <select name=\"route_id\">";

$query45 = "SELECT route_id, route_short_name, route_long_name FROM route ORDER BY route_short_name;";
if ($result45 = mysqli_query ($link, $query45)) {
	while ($row45 = mysqli_fetch_row ($result45)) {
		$roid = $row45[0];
		$roshname = $row45[1];
		$rolgname = $row45[2];

		echo "<option value=\"$roid\"";
		if ($roid == $linka) {
			echo " SELECTED";
		}
		echo ">$roshname - $rolgname</option>";
	}
}
echo "</select></td><td>Směr: $trip_headsign<br />";
echo "<select name=\"smer\"><option value=\"0\"";
if ($smer=='0') {
	echo " SELECTED";
}
echo ">Odchozí</option><option value=\"1\"";
if ($smer=='1') {
	echo " SELECTED";
}
echo ">Příchozí</option></select></td>";
echo "<td>Blok <input type=\"text\" name=\"block_id\" value=\"$blok\"><br/>";
echo "Invalida: <select name=\"invalida\"><option value=\"0\"";
if ($invalida == '0') {
	echo " SELECTED";
}
echo "></option><option value=\"1\"";
if ($invalida == '1') {
	echo " SELECTED";
}
echo ">Vlak vhodný pro přepravu</option><option value=\"2\"";
if ($invalida == '2') {
	echo " SELECTED";
}
echo ">Vlak neumožňuje přepravu</option></select><br />";
echo "Cyklo: <select name=\"cyklo\"><option value=\"0\"";
if ($cyklo == '0') {
	echo " SELECTED";
}
echo "></option><option value=\"1\"";
if ($cyklo == '1') {
	echo " SELECTED";
}
echo ">Vlak vhodný pro přepravu</option><option value=\"2\"";
if ($cyklo == '2') {
	echo " SELECTED";
}
echo ">Vlak neumožňuje přepravu</option></select>";
echo "</td>";
echo "<td>Aktivní <input type=\"checkbox\" name=\"aktif\" value=\"1\"";
if ($aktif == '1') {
	echo " CHECKED";
}
echo "></td><td><input type=\"submit\"></td></tr></form>";
echo "<tr><td colspan=\"5\">";

if (strpos($trip_id, 'F') !== false) {
	$vlak=substr ($trip_id,1,-2);
} else {
	$vlak=substr ($trip_id,0,-2);
}

$lomeni = substr ($vlak,-1);
$cislo7 = $vlak."/".$lomeni;

$query86 = "SELECT POZNAM FROM kango.OBP WHERE ((CISLO7='$cislo7'));";
if ($result86 = mysqli_query ($link, $query86)) {
	while ($row86 = mysqli_fetch_row ($result86)) {
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

echo "<form method=\"post\" action=\"tripedit.php\" name=\"zastavky\"><input name=\"action\" value=\"zastavky\" type=\"hidden\"><input name=\"trip_id\" value=\"$trip_id\" type=\"hidden\">";
$z = 0;

$query108 = "SELECT stoptime.stop_id,stoptime.arrival_time,stoptime.departure_time,stoptime.pickup_type,stoptime.drop_off_type,stoptime.stop_sequence, stop.stop_name FROM stoptime LEFT JOIN stop ON stoptime.stop_id = stop.stop_id WHERE (stoptime.trip_id = '$trip_id');";

if ($result108 = mysqli_query ($link, $query108)) {
	while ($row108 = mysqli_fetch_row ($result108)) {
		$stop_id = $row108[0];
		$arrival_time = $row108[1];
		$departure_time = $row108[2];
		$pickup_type = $row108[3];
		$drop_off_type = $row108[4];
		$stop_sequence = $row108[5];
		$nazev_stanice = $row108[6];

		echo "<tr><td><input name=\"stop_id$z\" value=\"$stop_id\" type=\"hidden\">";
/*		<input type=\"checkbox\" name=\"reroute$z\" value=\"1\">
		<select name=\"stop2_id$z\">";
		$query194 = "SELECT stop_id, stop_name FROM stop WHERE active=1 ORDER BY stop_name;";
		if ($result194 = mysqli_query ($link, $query194)) {
			while ($row194 = mysqli_fetch_row ($result194)) {
				$stopid = $row194[0];
				$stopname = $row194[1];

				echo "<option value=\"$stopid\"";
				if ($stopid == $stop_id) {
					echo " SELECTED";
				}
				echo ">$stopname</option>";
			}
		}
		echo "</select>";*/
		echo "<input type=\"text\" name=\"stopname$z\" value=\"$nazev_stanice\"></td>";
		echo "<td><input type=\"text\" name=\"arrive$z\" value=\"$arrival_time\"></td>";
		echo "<td><input type=\"text\" name=\"leave$z\" value=\"$departure_time\"></td>";
		echo "<td><select name=\"rezim$z\"><option value=\"00\"></option>";
		echo "<option value=\"01\"";
		if ($drop_off_type == 1) {
			echo " SELECTED";
		}
		echo ">Pouze nástup</option>";
		echo "<option value=\"10\"";
		if ($pickup_type == 1) {
			echo " SELECTED";
		}
		echo ">Pouze výstup</option>";
		echo "<option value=\"22\"";
		if ($drop_off_type == 2) {
			echo " SELECTED";
		}
		echo ">Vlak nezastavuje</option>";
		echo "<option value=\"33\"";
		if ($drop_off_type == 3) {
			echo " SELECTED";
		}
		echo ">Zastavuje na znamení</option>";
		echo "<select></td>";
		echo "<td><input name=\"poradi$z\" value=\"$stop_sequence\" type=\"hidden\"><input type=\"checkbox\" name=\"delete$z\" value=\"1\"></td></tr>";
		$z = $z + 1;
	}
}

echo "<input type=\"hidden\" name=\"pocet\" value=\"$z-1\">";
echo "<input type=\"submit\"></form>";
echo "</table></td><td>";

$pom163 = mysqli_fetch_row (mysqli_query ($link, "SELECT max(stop_sequence) FROM stoptime WHERE (trip_id = '$trip_id');"));
$max_trip = $pom163[0];

$pom129 = mysqli_fetch_row (mysqli_query ($link, "SELECT min(stop_sequence) FROM stoptime WHERE (trip_id = '$trip_id');"));
$min_trip = $pom129[0];

echo "KALENDÁŘ<br />";

$query188 = "SELECT ZELEZN,ZST,OB,KALENDAR FROM kango.DTV WHERE ((KALENDAR != '') AND (CISLO7 = '$cislo7'));";
if ($result188 = mysqli_query ($link, $query188)) {
	while ($row188 = mysqli_fetch_row ($result188)) {
		$stopstat = $row188[0];
		$stopzst = $row188[1];
		$stopob = $row188[2];
		$kalend = $row188[3];
		$stop_id = substr ($stopstat,-2).$stopzst.substr ($stopob,-1);

		$pom194 = mysqli_fetch_row (mysqli_query ($link, "SELECT stop_name FROM stop WHERE (stop_id = '$stop_id');"));
		$zahakal = $pom194[0];

		$pom197 = mysqli_fetch_row (mysqli_query ($link, "SELECT OMEZ1 FROM kango.KVL WHERE (KALENDAR = '$kalend');"));
		$omeze = $pom197[0];

		if ($kalend == '1') {
			$omeze = "jede denně";
		}
		echo "Ze stanice $zahakal $omeze<br /><br />";
	}
}

echo "IDS<br />";
$query262 = "SELECT * FROM kango.IDV WHERE (CISLO7 = '$cislo7');";
if ($result262 = mysqli_query ($link, $query262)) {
	while ($row262 = mysqli_fetch_row ($result262)) {
		$startstat = $row262[2];
		$startzst = $row262[3];
		$startob = $row262[4];
		$stopstat = $row262[5];
		$stopzst = $row262[6];
		$stopob = $row262[7];
		$idsid = $row262[9];
		
		$start_id = substr ($startstat,-2).$startzst.substr ($startob,-1);
		$stop_id = substr ($stopstat,-2).$stopzst.substr ($stopob,-1);

		$pom276 = mysqli_fetch_row (mysqli_query ($link, "SELECT stop_name FROM stop WHERE (stop_id = '$start_id');"));
		$zahaids = $pom276[0];
		$pom278 = mysqli_fetch_row (mysqli_query ($link, "SELECT stop_name FROM stop WHERE (stop_id = '$stop_id');"));
		$ukoids = $pom278[0];

		$pom281 = mysqli_fetch_row (mysqli_query ($link, "SELECT ZKRATKA FROM kango.IDS WHERE (IDIDS = '$idsid');"));
		$ids = $pom281[0];

		echo "V úseku $zahaids až $ukoids linka $ids.<br /><br />";
	}
}

echo "TRASA<br />"; //<a href=\"tripedit.php?id=$trip&trasa=1\">VYNUŤ</a>
echo str_replace('|','<br/>',$shape)."<br />";

/* $i = 0;
$tvartrasy = "";
$query131 = "SELECT * FROM kango.DTV WHERE (CISLO7='$cislo7');";
if ($result131 = mysqli_query ($link, $query131)) {
	while ($row131 = mysqli_fetch_row ($result131)) {
		$stopstat = $row131[1];
		$stopzst = $row131[2];
		$stopob = $row131[3];
		$ZST = substr ($stopstat,-2).$stopzst.substr ($stopob,-1);
		$i = $i + 1;

		if ($i <= $max_trip && $i >= $min_trip) {
			$tvartrasy .= $ZST."|";
		}
	}
}

$i = 0;
$prevstop = "";
$vzdal = 0;
$komplet = 1;

$output = explode ("|", $tvartrasy);

foreach ($output as $prujbod) {
	$pom139 = mysqli_fetch_row (mysqli_query ($link, "SELECT stop_name,stop_lat,stop_lon FROM stop WHERE (stop_id='$prujbod');"));
	$name = $pom139[0];
	$lat = $pom139[1];
	$lon = $pom139[2];
	$i = $i + 1;

	$result235 = mysqli_query ($link, "SELECT DELKA FROM kango.DU_pom WHERE (STOP1 = '$prevstop') AND (STOP2 = '$prujbod');");
	$pom235 = mysqli_fetch_row ($result235);
	$ujeto = $pom235[0];
	$radky = mysqli_num_rows($result235);
	$vzdal = $vzdal + $ujeto;
	$prevstop = $prujbod;
						
	if ($i == 1) {
		$vzdal = 0;
	}
	echo "$name,$lat,$lon,$i,$vzdal<br />";
}

$i = 0;
$prevstat= "";
$prevzst = "";
$prevob = "";
$vzdal = 0;

$query131 = "SELECT * FROM kango.DTV WHERE (CISLO7='$cislo7');";
if ($result131 = mysqli_query ($link, $query131)) {
	while ($row131 = mysqli_fetch_row ($result131)) {
		$stopstat = $row131[1];
		$stopzst = $row131[2];
		$stopob = $row131[3];
		$ZST = substr ($stopstat,-2).$stopzst.substr ($stopob,-1);
		$i = $i + 1;

		$pom139 = mysqli_fetch_row (mysqli_query ($link, "SELECT stop_name,stop_lat,stop_lon FROM stop WHERE (stop_id='$ZST');"));
		$name = $pom139[0];
		$lat = $pom139[1];
		$lon = $pom139[2];
		
		$result235 = mysqli_query ($link, "SELECT DELKA FROM kango.DU WHERE ((ZELEZN1 = '$prevstat') AND (ZST1 = '$prevzst') AND (OB1 = '$prevob') AND (ZELEZN2 = '$stopstat') AND (ZST2 = '$stopzst') AND (OB2 = '$stopob'));");
		$pom235 = mysqli_fetch_row ($result235);
		$ujeto = $pom235[0];
		$radky = mysqli_num_rows($result235);
		if ($radky == 0) {
			$result240 = mysqli_query ($link, "SELECT DELKA FROM kango.DU WHERE ((ZELEZN2 = '$prevstat') AND (ZST2 = '$prevzst') AND (OB2 = '$prevob') AND (ZELEZN1 = '$stopstat') AND (ZST1 = '$stopzst') AND (OB1 = '$stopob'));");
			$pom240 = mysqli_fetch_row ($result240);
			$ujeto = $pom240[0];
		}
		$vzdal = $vzdal + $ujeto;
		$prevstat = $stopstat;
		$prevzst = $stopzst;
		$prevob = $stopob;
		if ($lat != '' && $lon != '' && $i <= $max_trip && $i >= $min_trip) {
			if ($i == $min_trip) {
				$vzdal = 0;
			}
			echo "$name - $lat - $lon - $i - $vzdal<br />";
		}
	}
}
*/
echo "</td></tr>";
echo "</table>";

echo "</td></tr></table>";

echo "<form method=\"post\" action=\"tripedit.php\" name=\"grafikon\"><input name=\"action\" value=\"grafikon\" type=\"hidden\"><input name=\"trip_id\" value=\"$trip_id\" type=\"hidden\">";

echo "<input type=\"checkbox\" name=\"denne\" value=\"1\"> Jede denně";
echo "<input type=\"checkbox\" name=\"invert\" value=\"1\"> Invertuj";
echo "<input type=\"checkbox\" name=\"altern\" value=\"1\"> Alternace <input type=\"text\" name=\"proti\" value=\"\">";
echo "<select name=\"typkodu\"><option value=\"0\">--</option><option value=\"1\">Jede</option><option value=\"4\">Nejede</option></select> od <input type=\"text\" name=\"datumod\" value=\"10122017\"> do <input type=\"text\" name=\"datumdo\" value=\"08122018\">";

// Matice začíná 10.12.2017 
$matice_start = mktime (0,0,0,12,10,2017);
$grafikon = str_split ($matice);
echo "<table border=\"1\"><tr><td>";
// 10.12.2017 je 0;
for ($u = 0; $u < 365; $u++) {
	
	$datum=$matice_start + ($u * 86400);
	$datum_format = date ("d.m.", $datum);
	$denvtydnu = date ('w',$datum);
	if ($grafikon[$u] == "1") {
		echo "<span style=\"background-color:green;\">";
	}
	echo "$datum_format<br /><input type=\"radio\" name=\"grafikon$u\" value=\"0\"";
	if ($grafikon[$u] == "0") {
		echo " CHECKED";
	}
	echo "><input type=\"radio\" name=\"grafikon$u\" value=\"1\"";
	if ($grafikon[$u] == "1") {
		echo " CHECKED";
	}
	echo "><br />";
	if ($grafikon[$u] == "1") {
		echo "</span>";
	}
	if ($denvtydnu == "0") {
		echo "</td><td>";
	}
}
echo "</td></tr></table>";
echo "<input type=\"submit\"></form>";

include 'footer.php';
?>