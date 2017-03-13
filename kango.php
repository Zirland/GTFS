<?php
include 'header.php';

echo "<table border=\"1\">";
echo "<tr>";
echo "<th>ID vlaku</th>
	<th>Název</th>
	<th>Dopravce</th>
	<th>Linka</th>
	<th>Poznámky</th>
	<th></th>";
echo "</tr>";

$query = "SELECT CISLO7 FROM kango.IDV WHERE (IDIDS='1') ORDER BY CISLO7;";
if ($result = mysqli_query($link, $query)) {
	while ($row = mysqli_fetch_row($result)) {
		$cislo7 = $row[0];
		$cislo = substr($cislo7, 0, -2);

		$pom1 = mysqli_fetch_row(mysqli_query($link, "SELECT JMENVL,IDDOP FROM kango.HLV WHERE (CISLO7='$cislo7');"));
		$jmeno = $pom1[0];
		$iddop = $pom1[1];
		
		$pom2 = mysqli_fetch_row(mysqli_query($link, "SELECT ZKRATKA FROM kango.DOP WHERE (IDDOP='$iddop');"));				
		$zkratka = $pom2[0];
		
		echo "<tr>";
		echo "<td>$cislo7</td><td>$jmeno</td><td>$zkratka</td><td></td><td>";
		
		$query1 = "SELECT POZNAM FROM kango.OBP WHERE ((CISLO7='$cislo7') AND (POZNAM LIKE '%linka%'));";
		if ($result1 = mysqli_query($link, $query1)) {
			while ($row1 = mysqli_fetch_row($result1)) {
				$poznamka = $row1[0];
				
				echo "$poznamka<br />";
			}
		}
		echo"</td><td><a href=\"detail.php?vl=$cislo\">Detaily</a></td>";
		echo "</tr>";
	}
	mysqli_free_result($result);
}

echo "<table>";


include 'footer.php';
?>
