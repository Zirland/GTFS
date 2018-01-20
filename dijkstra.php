<?php
include 'header.php';
$action = @$_POST['action'];
$from = @$_POST['from'];
$to = @$_POST['to'];
echo "<form method=\"post\" action=\"dijkstra.php\" name=\"filtr\"><input name=\"action\" value=\"filtr\" type=\"hidden\">";
echo "Odkud: <select name=\"from\">";
$query0 = "SELECT stop_id, stop_name FROM stop ORDER BY stop_name;";
if ($result0 = mysqli_query ($link, $query0)) {
	while ($row0 = mysqli_fetch_row ($result0)) {
		$kodf = $row0[0];
		$nazevf = $row0[1];
		echo "<option value=\"$kodf\"";
		if ($kodf == $from) {
			echo " SELECTED";
		}
		echo ">$nazevf</option>";
	}
	mysqli_free_result ($result0);
} else {
	echo "Error description: ".mysqli_error ($link);
}
	
echo "</select>";
echo "Kam: <select name=\"to\">";
$query1 = "SELECT stop_id, stop_name FROM stop ORDER BY stop_name;";
if ($result1 = mysqli_query ($link, $query1)) {
	while ($row1 = mysqli_fetch_row ($result1)) {
		$kodt = $row1[0];
		$nazevt = $row1[1];
		echo "<option value=\"$kodt\"";
		if ($kodt == $to) {
			echo " SELECTED";
		}
		echo ">$nazevt</option>";
	}
	mysqli_free_result($result1);
} else {
	echo "Error description: " . mysqli_error($link);
}

echo "</select>";
echo "<input type=\"submit\"></form>";

switch ($action) {
	case "filtr" : 
		$_distArr = array ();
		$query = "SELECT ZELEZN1, ZST1, OB1, ZELEZN2, ZST2, OB2, DELKA from kango.DU;";
		if ($result = mysqli_query ($link, $query)) {
			while ($row = mysqli_fetch_row ($result)) {
				$zelezn1 = $row[0];
				$zst1 = $row[1];
				$ob1 = $row[2];
				$zelezn2 = $row[3];
				$zst2 = $row[4];
				$ob2 = $row[5];
				$delka = $row[6];
				$id1 = substr($zelezn1,-2).$zst1.substr($ob1,-1);
				$id2 = substr($zelezn2,-2).$zst2.substr($ob2,-1);

				$_distArr[$id1][$id2] = $delka;
				$_distArr[$id2][$id1] = $delka;
			}
		}

		$a = $from;
		$b = $to;

		$S = array ();
		$Q = array ();

		foreach (array_keys ($_distArr) as $val) {
			$Q[$val] = 9999999;
		}

		$Q[$a] = 0;

		while (!empty ($Q)){
			$min = array_search(min($Q), $Q);//the most min weight
			if($min == $b) {
				break;
			}
			foreach ($_distArr[$min] as $key=>$val) {
				if (!empty ($Q[$key]) && $Q[$min] + $val < $Q[$key]) {
					$Q[$key] = $Q[$min] + $val;
					$S[$key] = array ($min, $Q[$key]);
				}
			}
			unset ($Q[$min]);
		}

		$path = array ();
		$pos = $b;
		while ($pos != $a) {
			$zelezn = "00".substr ($pos,0,2);
			$zst = substr ($pos,2,6);
			$ob = "0".substr ($pos,-1);
			$query44 = "SELECT NAZEVDB FROM kango.DB WHERE ((ZELEZN = '$zelezn') AND (ZST = '$zst') AND (OB = '$ob'));";

			if ($result44 = mysqli_query($link, $query44)) {
				while ($pom = mysqli_fetch_row($result44)) {
					$stan = $pom[0];
					$item = "$stan";
				} 
			}
			$path[] = $item;
			$pos = $S[$pos][0];
		}

		$zelezn = "00".substr ($a,0,2);
		$zst = substr ($a,2,6);
		$ob = "0".substr ($a,-1);
		$pom = mysqli_fetch_row (mysqli_query ($link, "SELECT NAZEVDB FROM kango.DB WHERE ((ZELEZN = '$zelezn') AND (ZST = '$zst') AND (OB = '$ob'));"));
		$stan = $pom[0];
		$path[] = $stan;
		$path = array_reverse ($path);

		echo "<br />From $a to $b";
		echo "<br />The length is ".$S[$b][1];
		echo "<br />Path is ".implode ('<br />', $path);
	break;
}

include 'footer.php';
?>