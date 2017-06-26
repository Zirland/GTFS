<?php
$link = mysqli_connect('localhost', 'gtfs', 'gtfs', 'GTFS2');
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    exit;
}

$file = 'transfers.txt';
$current = "from_stop_id,to_stop_id,transfer_type,min_transfer_time\n";
file_put_contents($file, $current);

$now = microtime(true);
$timestart = $now;
echo "Start: $now<br />";
$prevnow = $now;

$current = "";

for ($x=1; $x<33; $x++) {
	for ($y=1; $y<33; $y++) {
		if ($y <= $x) {
		echo "";
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

		$namex=mysqli_fetch_row(mysqli_query($link, "SELECT track_name FROM uzly WHERE track_id = '$x';"));
		$xname= $namex[0];
		$overx = mysqli_num_rows(mysqli_query($link, "SELECT stop_id FROM (SELECT DISTINCT stop_id FROM kango.stop_use) AS pouzito WHERE stop_id='$xname';"));

		if ($overx > 0) {
			$namey=mysqli_fetch_row(mysqli_query($link, "SELECT track_name FROM uzly WHERE track_id = '$y';"));
			$yname = $namey[0];
			$overy = mysqli_num_rows(mysqli_query($link, "SELECT stop_id FROM (SELECT DISTINCT stop_id FROM kango.stop_use) AS pouzito WHERE stop_id='$yname';"));
			
			if ($overy > 0) {
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
				
				if ($cas != '') {
					$current = "$xname,$yname,2,$cas\n";
					$current .= "$yname,$xname,2,$cas\n";
					file_put_contents($file, $current, FILE_APPEND);
				}
			}	
		}}
	}
}

for ($x=100; $x<110; $x++) {
	for ($y=100; $y<110; $y++) {
		if ($y <= $x) {
		echo "";
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

		$namex=mysqli_fetch_row(mysqli_query($link, "SELECT track_name FROM uzly WHERE track_id = '$x';"));
		$xname= $namex[0];
		$overx = mysqli_num_rows(mysqli_query($link, "SELECT stop_id FROM (SELECT DISTINCT stop_id FROM kango.stop_use) AS pouzito WHERE stop_id='$xname';"));
		
		if ($overx > 0) {
			$namey=mysqli_fetch_row(mysqli_query($link, "SELECT track_name FROM uzly WHERE track_id = '$y';"));
			$yname = $namey[0];
			$overy = mysqli_num_rows(mysqli_query($link, "SELECT stop_id FROM (SELECT DISTINCT stop_id FROM kango.stop_use) AS pouzito WHERE stop_id='$yname';"));
			
			if ($overy > 0) {
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
				
				if ($cas != '') {
					$current = "$xname,$yname,2,$cas\n";
					$current .= "$yname,$xname,2,$cas\n";
					file_put_contents($file, $current, FILE_APPEND);
				}
			}	
		}}
	}
}

$now = microtime(true);
$dlouho = $now-$prevnow;
echo "Transfers: $dlouho<br />";

mysqli_close($link);
?>
