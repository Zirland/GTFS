<?php
$link = mysqli_connect('localhost', 'gtfs', 'gtfs', 'GTFS2');
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    exit;
}

echo "<TABLE border=\"1\">";
echo "<TR>";
for ($x=0; $x<32; $x++) {
	echo "<TH align=\"center\">";
	if ($x == 0) {
		echo "";
	} else {
		$namex=mysqli_fetch_row(mysqli_query($link, "SELECT track_name FROM uzly WHERE track_id = '$x';"));
		$xname=substr($namex[0],9);
		echo $xname;
	}
	echo "</TH>";
}
echo "</TR>";

for ($x=1; $x<32; $x++) {
	echo "<TR>";
	for ($y=0; $y<32; $y++) {
		if ($y == 0) {
			$namex=mysqli_fetch_row(mysqli_query($link, "SELECT track_name FROM uzly WHERE track_id = '$x';"));
			$xname=substr($namex[0],9);
			echo "<TD align=\"center\"><B>$xname</B></TD>";
		}
		else if ($y <= $x) {
		echo "<TD align=\"center\">*</TD>";
		} else {
		
		$_distArr = array();
		$query = "SELECT uzel1, uzel2, delka FROM sit;";
		if ($result = mysqli_query($link, $query)) {
			while ($row = mysqli_fetch_row($result)) {
				$uzel1 = $row[0];
				$uzel2 = $row[1];
				$delka = $row[2];

				$_distArr[$uzel1][$uzel2] = $delka;
				$_distArr[$uzel2][$uzel1] = $delka;
    }
}

//the start and the end
$a = $x;
$b = $y;

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
		
		$cas = $S[$b][1];
		$min = floor($cas/60);
		$sec = $cas%60;
		if ($sec<10) {$sec = "0".$sec;}
		
			echo "<TD align=\"center\">$cas s<br/>$min:$sec</TD>";
		}
	}
	echo "</TR>";
}

echo "</TABLE>";

echo "<TABLE border=\"1\">";
echo "<TR>";
for ($x=99; $x<108; $x++) {
	echo "<TH align=\"center\">";
	if ($x == 99) {
		echo "";
	} else {
		$namex=mysqli_fetch_row(mysqli_query($link, "SELECT track_name FROM uzly WHERE track_id = '$x';"));
		$xname=substr($namex[0],9);
		echo $xname;
	}
	echo "</TH>";
}
echo "</TR>";

for ($x=100; $x<108; $x++) {
	echo "<TR>";
	for ($y=99; $y<108; $y++) {
		if ($y == 99) {
			$namex=mysqli_fetch_row(mysqli_query($link, "SELECT track_name FROM uzly WHERE track_id = '$x';"));
			$xname=substr($namex[0],9);
			echo "<TD align=\"center\"><B>$xname</B></TD>";
		}
		else if ($y <= $x) {
		echo "<TD align=\"center\">*</TD>";
		} else {
		
		$_distArr = array();
		$query = "SELECT uzel1, uzel2, delka FROM sit;";
		if ($result = mysqli_query($link, $query)) {
			while ($row = mysqli_fetch_row($result)) {
				$uzel1 = $row[0];
				$uzel2 = $row[1];
				$delka = $row[2];

				$_distArr[$uzel1][$uzel2] = $delka;
				$_distArr[$uzel2][$uzel1] = $delka;
    }
}

//the start and the end
$a = $x;
$b = $y;

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
		
		$cas = $S[$b][1];
		$min = floor($cas/60);
		$sec = $cas%60;
		if ($sec<10) {$sec = "0".$sec;}
		
			echo "<TD align=\"center\">$cas s<br/>$min:$sec</TD>";
		}
	}
	echo "</TR>";
}

echo "</TABLE>";

mysqli_close($link);
?>
