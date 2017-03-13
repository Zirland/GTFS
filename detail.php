<?php
include 'header.php';

$vlak = $_GET['vl'];
$lomeni = substr($vlak,-1);
$cislo7 = $vlak."/".$lomeni;

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

echo "<td>Vlak: $cislo7</td><td>$jmeno</td><td>Dopravce: $dopravce</td><td>Jede od: $datum_od</td><td>Jede do: $datum_do</td></tr>";

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
		$calenar = $row[37];

		$pom2 = mysqli_fetch_row(mysqli_query($link, "SELECT NAZEVDB FROM kango.DB WHERE (ZST='$ZST');"));
		$stanice = $pom2[0];
		$pom3 = mysqli_fetch_row(mysqli_query($link, "SELECT * FROM kango.KVL WHERE (KALENDAR ='$calendar');"));
		$d1 = $pom3[0];
				
		
		echo "<tr>";
		echo "<td>$stanice</td>";
		echo "<td>$DP - $HP:$MP</td>";
		echo "<td>$DO - $HO:$MO</td>";
		echo "</tr>";
	}
	mysqli_free_result($result);
}

echo "<table>";


include 'footer.php';
?>
