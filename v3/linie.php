<?php
include 'header.php';

//$file = 'linie.txt';
//$current = "shape_id,shape_pt_lat,shape_pt_lon,shape_pt_sequence,shape_dist_traveled,route_id\n";
//file_put_contents($file, $current);

$current = "";





$now = microtime(true);
$dlouho = $now-$prevnow;
// echo "Headers: $dlouho<br />";
$prevnow = $now;

$query46 = "SELECT agency_id,agency_name,agency_url,agency_timezone,agency_phone FROM agency WHERE agency_id IN (SELECT DISTINCT agency_id FROM route WHERE (active='1'));";

if ($result46 = mysqli_query($link, $query46)) {
	while ($row46 = mysqli_fetch_row($result46)) {
		$agency_id = $row46[0];
		$agency_name = $row46[1];
		$agency_url = $row46[2];
		$agency_timezone = $row46[3];
		$agency_phone = $row46[4];
		$agencynums = mysqli_num_rows($result46);
				
		$current .= "$agency_id,\"$agency_name\",$agency_url,$agency_timezone,\"$agency_phone\"\n";
}}

$file = 'agency.txt';
file_put_contents($file, $current, FILE_APPEND);

echo "Exported agencies: $agencynums<br />";

$now = microtime(true);
$dlouho = $now-$prevnow;
// echo "Agencies: $dlouho<br />";
$prevnow = $now;


$akt_route = "SELECT route_id,agency_id,route_short_name,route_long_name,route_type,route_color,route_text_color FROM route WHERE (active='1');";

if ($result69 = mysqli_query($link, $akt_route)) {
    while ($row69 = mysqli_fetch_row($result69)) {
		$route_id = $row69[0];
		$agency_id = $row69[1];
		$route_short_name = $row69[2];
		$route_long_name = $row69[3];
		$route_type = $row69[4];
		$route_color = $row69[5];
		$route_text_color = $row69[6];
		$routenums = mysqli_num_rows($result69);
	
		$current = "$route_id,$agency_id,\"$route_short_name\",\"$route_long_name\",$route_type,$route_color,$route_text_color\n";

		$file = 'routes.txt';
		file_put_contents($file, $current, FILE_APPEND);
// zapsána aktivní linka

$now = microtime(true);
$dlouho = $now-$prevnow;
// echo "Route: $dlouho<br />";
$prevnow = $now;


		$akt_trip = "SELECT route_id,matice,trip_id,trip_headsign,direction_id,shape_id,wheelchair_accessible,bikes_allowed FROM trip WHERE ((route_id = '$route_id') AND (active='1'));";
		if ($result85 = mysqli_query($link, $akt_trip)) {
			while ($row85 = mysqli_fetch_row($result85)) {
				$route_id = $row85[0];
				$matice = $row85[1];
				$trip_id = $row85[2];
				$trip_headsign = $row85[3];
				$direction_id = $row85[4];
				$shape_tvar = $row85[5];
				$wheelchair_accessible = $row85[6];
				$bikes_allowed = $row85[7];

				$matice_start = mktime(0,0,0,12,11,2016);
				$dnes_den = date("d", time());
				$dnes_mesic = date("m", time());
				$dnes_rok = date("Y", time());

				$calendar_start = mktime(0,0,0,$dnes_mesic,$dnes_den,$dnes_rok);
				$calendar_start_format = date("Ymd", $calendar_start);
				$calendar_stop_format = date("Ymd", $calendar_start+6*86400);
				$vtydnu = date('w',$calendar_start);
		
				$sek=$calendar_start-$matice_start;
				$min=floor($sek/60);
				$sek=$sek%60;
				$hod=floor($min/60);
				$min=$min%60;
				$dni=floor($hod/24);
				$hod=$hod%24;
				$aktual = substr($matice,$dni+1,7);

				$adjust = substr($aktual,-$vtydnu+1).substr($aktual,0,-$vtydnu+1);
				$dec=bindec($adjust)+1;

$now = microtime(true);
$dlouho = $now-$prevnow;
// echo "Calendar $trip_id: $dlouho<br />";
$prevnow = $now;


				if ($dec != 1)  {
					$service_id = $dec;
				
					$mark_cal = mysqli_query($link, "INSERT INTO kango.cal_use (trip_id, kalendar) VALUES ('$trip_id', '$service_id');");
// zápis kalendáře spoje pro tento týden do databáze

					$query152 = "SELECT shape_id FROM shapetvary WHERE tvartrasy = '$shape_tvar';";
					if ($result152 = mysqli_query($link, $query152)) {
						$radku = mysqli_num_rows($result152);
							if ($radku == 0) {
								$vloztrasu = mysqli_query($link, "INSERT INTO shapetvary (tvartrasy, complete) VALUES ('$shape_tvar', '0');");
								$shape_id = mysqli_insert_id($link);
							} else
							while ($row152 = mysqli_fetch_row($result152)) {
								$shape_id = $row152[0];
							}
					}

					$current = "$route_id,$service_id,$trip_id,\"$trip_headsign\",$direction_id,$shape_id,$wheelchair_accessible,$bikes_allowed\n";
					$file = 'trips.txt';
					file_put_contents($file, $current, FILE_APPEND);
					$tripnums = $tripnums + 1;
// zapsán aktivní spoj

					$query171 = "INSERT INTO kango.shapecheck (trip_id, shape_id) VALUES ('$trip_id', '$shape_id');";
					$zapistrasy = mysqli_query($link, $query171);

$now = microtime(true);
$dlouho = $now-$prevnow;
// echo "Trip $trip_id: $dlouho<br />";
$prevnow = $now;

					$query162 = "SELECT tvartrasy, complete FROM shapetvary WHERE shape_id = '$shape_id';";
					if ($result162 = mysqli_query($link, $query162)) {
						while ($row162 = mysqli_fetch_row($result162)) {
						$tvartrasy = $row162[0];
						$kompltrasa = $row162[1];
						if ($kompltrasa != 1) {
							$smaz182 = "DELETE FROM shape WHERE shape_id = '$shape_id';";
							$smazanitrasy = mysqli_query($link,$smaz182);
				
							$i = 0;
							$prevstop = "";
							$vzdal = 0;
							$komplet = 1;


							foreach ($output as $prujbod) {
								$pom139 = mysqli_fetch_row(mysqli_query($link, "SELECT stop_name,stop_lat,stop_lon FROM stop WHERE (stop_id='$prujbod');"));
								$name = $pom139[0];
								$lat = $pom139[1];
								$lon = $pom139[2];
								$i = $i + 1;
						
								$result235 = mysqli_query($link, "SELECT DELKA FROM kango.DU_pom WHERE (STOP1 = '$prevstop') AND (STOP2 = '$prujbod');");
								$pom235 = mysqli_fetch_row($result235);
								$ujeto = $pom235[0];
								$radky = mysqli_num_rows($result235);
								$vzdal = $vzdal + $ujeto;
								$prevstop = $prujbod;
						
								if ($lat != '' && $lon != '') {
									if ($i == 1) {$vzdal = 0;} 
									$query144 = "INSERT INTO shape VALUES (
										'$shape_id',
										'$lat',
										'$lon',
										'$i',
										'$vzdal'
									);";
									$command = mysqli_query($link, $query144);
								} 
//								else {$komplet = 0;}
// zápis nové trasy do databáze
							}
						}				
						$query217 = "UPDATE shapetvary SET complete = '$komplet' WHERE shape_id = '$shape_id';";
						$command217 = mysqli_query($link, $query217);
						}
					}
	
/*					$current = "";

					$query260 = "SELECT shape_id,shape_pt_lat,shape_pt_lon,shape_pt_sequence,shape_dist_traveled FROM shape WHERE shape_id = '$trip_id';";
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
					
					$file = 'shapes.txt';
					file_put_contents($file, $current, FILE_APPEND);
//zapsány použité tvary tras */
					
					
					
$now = microtime(true);
$dlouho = $now-$prevnow;
// echo "Trasa $trip_id: $dlouho ~ Kompletní $komplet<br />";
$prevnow = $now;

				
					$tripstops = "SELECT trip_id,arrival_time,departure_time,stop_id,stop_sequence,pickup_type,drop_off_type FROM stoptime WHERE (trip_id = '$trip_id');";
					if ($result166 = mysqli_query($link, $tripstops)) {
						while ($row166 = mysqli_fetch_row($result166))  {
							$trip_id = $row166[0];
							$arrival_time = $row166[1];
							$departure_time = $row166[2];
							$stop_id = $row166[3];
							$stop_sequence = $row166[4];
							$pickup_type = $row166[5];
							$drop_off_type = $row166[6];
				
							$current = "$trip_id,$arrival_time,$departure_time,$stop_id,$stop_sequence,$pickup_type,$drop_off_type\n";
							$file = 'stop_times.txt';
							file_put_contents($file, $current, FILE_APPEND);
			
							$mark_stop = mysqli_query($link, "INSERT INTO kango.stop_use (trip_id, stop_id) VALUES ('$trip_id', '$stop_id');");
// zapsán jízdní řád trasy a stanice do pomocné databáze
						}					
					}

$now = microtime(true);
$dlouho = $now-$prevnow;
// echo "Schedule $trip_id: $dlouho<br />";
$prevnow = $now;

				}
			}
		}
	}
}

echo "Exported lines: $routenums<br />";
echo "Exported trips: $tripnums<br />";

$current = "";

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
// echo "Calendar flush: $dlouho<br />";
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
// echo "Stop flush: $dlouho<br />";
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
// echo "Shape flush: $dlouho<br />";

$timecelkem = $now - $timestart;

echo "Celkem zpracování: $timecelkem";
include 'footer.php';
?>
