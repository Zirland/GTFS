<?php
$link = mysqli_connect('localhost', 'gtfs', 'gtfs', 'GTFS2');
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    exit;
}

$file = 'calendar.txt';
$current = "service_id,monday,tuesday,wednesday,thursday,friday,saturday,sunday,start_date,end_date\n";
file_put_contents($file, $current);

$file = 'stops.txt';
$current = "stop_id,stop_name,stop_lat,stop_lon,location_type,parent_station,wheelchair_boarding\n";
file_put_contents($file, $current);

$file = 'shapes.txt';
$current = "shape_id,shape_pt_lat,shape_pt_lon,shape_pt_sequence,shape_dist_traveled\n";
file_put_contents($file, $current);

$now = microtime(true);
$timestart = $now;
echo "Start: $now<br />";
$prevnow = $now;

$stopnums = 0;

$current = "";

$dnes_den = date("d", time());
$dnes_mesic = date("m", time());
$dnes_rok = date("Y", time());

$calendar_start = mktime(0,0,0,$dnes_mesic,$dnes_den,$dnes_rok);
$calendar_start_format = date("Ymd", $calendar_start);
$calendar_stop_format = date("Ymd", $calendar_start+6*86400);

$query193 = "SELECT service_id,monday,tuesday,wednesday,thursday,friday,saturday,sunday FROM calendar WHERE (service_id IN (SELECT DISTINCT kalendar FROM kango.cal_use)) ORDER BY service_id;";
if ($result193 = mysqli_query($link, $query193)) {
	while ($row193 = mysqli_fetch_row($result193)) {
                $service_id = $row193[0];
                $monday = $row193[1];
                $tuesday = $row193[2];
                $wednesday = $row193[3];
                $thursday = $row193[4];
                $friday = $row193[5];
                $saturday = $row193[6];
                $sunday = $row193[7];
				
		$current .= "$service_id,$monday,$tuesday,$wednesday,$thursday,$friday,$saturday,$sunday,$calendar_start_format,$calendar_stop_format\n";
}
}

$file = 'calendar.txt';
file_put_contents($file, $current, FILE_APPEND);
//zapsány použité kalendáře

$now = microtime(true);
$dlouho = $now-$prevnow;
echo "Calendar flush: $dlouho<br />";
$prevnow = $now;

$current = "";

$query233 = "SELECT stop_id,stop_name,stop_lat,stop_lon,location_type,parent_station,wheelchair_boarding FROM stop WHERE (stop_id IN (SELECT stop_id FROM kango.stop_use));";
        if ($result233 = mysqli_query($link, $query233)) {
            while ($row233 = mysqli_fetch_row($result233)) {
                $stop_id = $row233[0];
                $stop_name = $row233[1];
                $stop_lat = $row233[2];
                $stop_lon = $row233[3];
                $location_type = $row233[4];
                $parent_station = $row233[5];
                $wheelchair_boarding = $row233[6];
                $stopnums = mysqli_num_rows($result233);

				$current .= "$stop_id,\"$stop_name\",$stop_lat,$stop_lon,$location_type,$parent_station,$wheelchair_boarding\n";
				if ($parent_station != '') {
					$mark_parent = mysqli_query($link, "INSERT INTO kango.parent_use (stop_id) VALUES ('$parent_station');");
				}
			}
		}

$query313 = "SELECT stop_id,stop_name,stop_lat,stop_lon,location_type,parent_station,wheelchair_boarding FROM stop WHERE (stop_id IN (SELECT stop_id FROM kango.parent_use));";
        if ($result313 = mysqli_query($link, $query313)) {
            while ($row313 = mysqli_fetch_row($result313)) {
                $stop_id = $row313[0];
                $stop_name = $row313[1];
                $stop_lat = $row313[2];
                $stop_lon = $row313[3];
                $location_type = $row313[4];
                $parent_station = $row313[5];
                $wheelchair_boarding = $row313[6];
                $stopnums = $stopnums + mysqli_num_rows($result313);

				$current .= "$stop_id,\"$stop_name\",$stop_lat,$stop_lon,$location_type,$parent_station,$wheelchair_boarding\n";
			}
		}


$file = 'stops.txt';
file_put_contents($file, $current, FILE_APPEND);
//zapsány použité zastávky

$now = microtime(true);
$dlouho = $now-$prevnow;
echo "Stop flush: $dlouho<br />";
$prevnow = $now;

echo "Exported stops: $stopnums<br />";

$current = "";

$shps = "SELECT DISTINCT shapecheck.shape_id FROM kango.shapecheck;";
if ($result349 = mysqli_query($link, $shps)) {
	while ($row349 = mysqli_fetch_row($result349)) {
		$shape_id = $row349[0];

		$query260 = "SELECT shape_id,shape_pt_lat,shape_pt_lon,shape_pt_sequence,shape_dist_traveled FROM shape WHERE (shape_id = '$shape_id');";
		if ($result260 = mysqli_query($link, $query260)) {
			while ($row260 = mysqli_fetch_row($result260)) {
				$shape_id = $row260[0];
				$shape_pt_lat = $row260[1];
				$shape_pt_lon = $row260[2];
				$shape_pt_sequence = $row260[3];
				$shape_dist_traveled = $row260[4];
        
				$current .= "$shape_id,$shape_pt_lat,$shape_pt_lon,$shape_pt_sequence,$shape_dist_traveled\n";
			}
		}
	}
}

$file = 'shapes.txt';
file_put_contents($file, $current, FILE_APPEND);
//zapsány použité tvary tras

$now = microtime(true);
$dlouho = $now-$prevnow;
echo "Shape flush: $dlouho<br />";

mysqli_close($link);

$link = mysqli_connect('localhost', 'gtfs', 'gtfs', 'JDF');
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    exit;
}

$now = microtime(true);
$timestart = $now;
echo "Start: $now<br />";
$prevnow = $now;

$stopnums = 0;

$current = "";

$query233 = "SELECT stop_id,stop_name,stop_lat,stop_lon,location_type,parent_station,wheelchair_boarding FROM stop WHERE (stop_id IN (SELECT stop_id FROM stop_use));";
        if ($result233 = mysqli_query($link, $query233)) {
            while ($row233 = mysqli_fetch_row($result233)) {
                $stop_id = $row233[0];
                $stop_name = $row233[1];
                $stop_lat = $row233[2];
                $stop_lon = $row233[3];
                $location_type = $row233[4];
                $parent_station = $row233[5];
                $wheelchair_boarding = $row233[6];
                $stopnums = mysqli_num_rows($result233);

				$current .= "$stop_id,\"$stop_name\",$stop_lat,$stop_lon,$location_type,$parent_station,$wheelchair_boarding\n";
				if ($parent_station != '') {
					$mark_parent = mysqli_query($link, "INSERT INTO parent_use (stop_id) VALUES ('$parent_station');");
				}
			}
		}

$query313 = "SELECT stop_id,stop_name,stop_lat,stop_lon,location_type,parent_station,wheelchair_boarding FROM stop WHERE (stop_id IN (SELECT stop_id FROM parent_use));";
        if ($result313 = mysqli_query($link, $query313)) {
            while ($row313 = mysqli_fetch_row($result313)) {
                $stop_id = $row313[0];
                $stop_name = $row313[1];
                $stop_lat = $row313[2];
                $stop_lon = $row313[3];
                $location_type = $row313[4];
                $parent_station = $row313[5];
                $wheelchair_boarding = $row313[6];
                $stopnums = $stopnums + mysqli_num_rows($result313);

				$current .= "$stop_id,\"$stop_name\",$stop_lat,$stop_lon,$location_type,$parent_station,$wheelchair_boarding\n";
			}
		}


$file = 'stops.txt';
file_put_contents($file, $current, FILE_APPEND);
//zapsány použité zastávky

$now = microtime(true);
$dlouho = $now-$prevnow;
echo "Stop flush: $dlouho<br />";
$prevnow = $now;

echo "Exported stops: $stopnums<br />";

$current = "";

$shps = "SELECT DISTINCT shapecheck.shape_id FROM shapecheck;";
if ($result349 = mysqli_query($link, $shps)) {
	while ($row349 = mysqli_fetch_row($result349)) {
		$shape_id = $row349[0];

		$query260 = "SELECT shape_id,shape_pt_lat,shape_pt_lon,shape_pt_sequence,shape_dist_traveled FROM shape WHERE (shape_id = '$shape_id');";
		if ($result260 = mysqli_query($link, $query260)) {
			while ($row260 = mysqli_fetch_row($result260)) {
				$shape_id = $row260[0];
				$shape_pt_lat = $row260[1];
				$shape_pt_lon = $row260[2];
				$shape_pt_sequence = $row260[3];
				$shape_dist_traveled = $row260[4];
        
				$current .= "J$shape_id,$shape_pt_lat,$shape_pt_lon,$shape_pt_sequence,$shape_dist_traveled\n";
			}
		}
	}
}

$file = 'shapes.txt';
file_put_contents($file, $current, FILE_APPEND);
//zapsány použité tvary tras

$now = microtime(true);
$dlouho = $now-$prevnow;
echo "Shape flush: $dlouho<br />";

mysqli_close($link);
?>
