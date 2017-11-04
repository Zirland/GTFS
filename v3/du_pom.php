<?php
include 'header.php';

$query = "SELECT * FROM kango.DU;";
if ($result = mysqli_query($link, $query)) {
	while ($row = mysqli_fetch_row($result)) {
		$ZELEZN1 = $row[0];
		$ZST1 = $row[1];
		$OB1 = $row[2];
		$ZELEZN2 = $row[3];
		$ZST2 = $row[4];
		$OB2 = $row[5];
		$DELKA = $row[6];

		$stop_id1 = substr($ZELEZN1,-2).$ZST1.substr($OB1,-1);
		$stop_id2 = substr($ZELEZN2,-2).$ZST2.substr($OB2,-1);

		$prikaz1 = "INSERT INTO kango.DU_pom (STOP1, STOP2, DELKA) VALUES ($stop_id1, $stop_id2, $DELKA);";
		$command1 = mysqli_query($link, $prikaz1);
		$prikaz2 = "INSERT INTO kango.DU_pom (STOP1, STOP2, DELKA) VALUES ($stop_id2, $stop_id1, $DELKA);";
		$command2 = mysqli_query($link, $prikaz2);
	}
}

echo "Hotovo";

include 'footer.php';
?>
