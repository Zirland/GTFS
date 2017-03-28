<?php

include 'header.php';

$file = 'agency.txt';
$current = "agency_id,agency_name,agency_url,agency_timezone,agency_phone\n";
file_put_contents($file, $current);

$file = 'routes.txt';
$current = "route_id,agency_id,route_short_name,route_long_name,route_type,route_color,route_text_color\n";
file_put_contents($file, $current);

$file = 'trips.txt';
$current = "route_id,service_id,trip_id,trip_headsign,direction_id,shape_id,wheelchair_accessible,bikes_allowed\n";
file_put_contents($file, $current);

$file = 'stop_times.txt';
$current = "trip_id,arrival_time,departure_time,stop_id,stop_sequence,pickup_type,drop_off_type\n";
file_put_contents($file, $current);

$file = 'calendar.txt';
$current = "service_id,monday,tuesday,wednesday,thursday,friday,saturday,sunday,start_date,end_date\n";
file_put_contents($file, $current);

$file = 'stops.txt';
$current = "stop_id,stop_name,stop_lat,stop_lon,location_type,parent_station,wheelchair_boarding\n";
file_put_contents($file, $current);

$file = 'shapes.txt';
$current = "shape_id,shape_pt_lat,shape_pt_lon,shape_pt_sequence,shape_dist_traveled\n";
file_put_contents($file, $current);

$shape_trunc = mysqli_query($link, "TRUNCATE TABLE shape;");
$napln = mysqli_query($link, "INSERT INTO shape SELECT * FROM force_shape;");

$calendar_trunc = mysqli_query($link, "TRUNCATE TABLE kango.cal_use;");

$agencynums = 0;
$routenums = 0;
$tripnums = 0;
$stopnums = 0;

$current = "";

$act_agency = "SELECT agency_id FROM route WHERE (active='1') GROUP BY agency_id;";
if ($result42 = mysqli_query($link, $act_agency)) {
    while ($row42 = mysqli_fetch_row($result42)) {
        $agency_id_42 = $row42[0];
		$agencynums = mysqli_num_rows($result42);
		
	$query46 = "SELECT agency_id,agency_name,agency_url,agency_timezone,agency_phone FROM agency WHERE (agency_id = '$agency_id_42');";

        if ($result46 = mysqli_query($link, $query46)) {
            while ($row46 = mysqli_fetch_row($result46)) {
                $agency_id = $row46[0];
		$agency_name = $row46[1];
		$agency_url = $row46[2];
		$agency_timezone = $row46[3];
		$agency_phone = $row46[4];
		
		$current .= "$agency_id,\"$agency_name\",$agency_url,$agency_timezone,\"$agency_phone\"\n";
            }
	}
    }
}	
    
$file = 'agency.txt';
file_put_contents($file, $current, FILE_APPEND);

echo "Exported agencies: $agencynums<br />";

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

	$akt_trip = "SELECT route_id,matice,trip_id,trip_headsign,direction_id,shape_id,wheelchair_accessible,bikes_allowed FROM trip WHERE ((route_id = '$route_id') AND (active='1'));";
	if ($result85 = mysqli_query($link, $akt_trip)) {
            while ($row85 = mysqli_fetch_row($result85)) {
		$route_id = $row85[0];
		$matice = $row85[1];
		$trip_id = $row85[2];
		$trip_headsign = $row85[3];
		$direction_id = $row85[4];
		$shape_id = $row85[5];
		$wheelchair_accessible = $row85[6];
		$bikes_allowed = $row85[7];

		$matice_start = mktime(0,0,0,12,11,2016);
//		$zitra_den = date("d", time()+86400);
//		$zitra_mesic = date("m", time()+86400);
//		$zitra_rok = date("Y", time()+86400);
		$dnes_den = date("d", time());
		$dnes_mesic = date("m", time());
		$dnes_rok = date("Y", time());

		$calendar_start = mktime(0,0,0,$dnes_mesic,$dnes_den,$dnes_rok);
		$calendar_start_format = date("Ymd", time());
		$calendar_stop_format = date("Ymd", time()+7*86400);
		$vtydnu = date('w',$calendar_start);

		$sek=$calendar_start-$matice_start;
		$min=floor($sek/60);
		$sek=$sek%60;
		$hod=floor($min/60);
		$min=$min%60;
		$dni=floor($hod/24);
		$hod=$hod%24;
		$aktual = substr($matice,$dni,7);

 	       	$adjust = substr($aktual,-$vtydnu).substr($aktual,0,-$vtydnu);
		$dec=bindec($adjust)+1;

		$service_id = $dec;
				
		$mark_cal = mysqli_query($link, "INSERT INTO kango.cal_use (trip_id, kalendar) VALUES ('$trip_id', '$service_id');");
// zápis kalendáře spoje pro tento týden do databáze
			
		$current = "$route_id,$service_id,$trip_id,\"$trip_headsign\",$direction_id,$shape_id,$wheelchair_accessible,$bikes_allowed\n";
		$file = 'trips.txt';
		file_put_contents($file, $current, FILE_APPEND);
		$tripnums = $tripnums + 1;
// zapsán aktivní spoj
				
		$pom125 = mysqli_fetch_row(mysqli_query($link, "SELECT max(stop_sequence) FROM stoptime WHERE (trip_id = '$trip_id');"));
		$max_trip = $pom125[0];

		$pom129 = mysqli_fetch_row(mysqli_query($link, "SELECT min(stop_sequence) FROM stoptime WHERE (trip_id = '$trip_id');"));
		$min_trip = $pom129[0];
//vymezení výchozího a konečného bodu
				
		$lomeni = substr($trip_id,-2,1);
		$vlak = substr($trip_id, 0, -2);
		$cislo7 = $vlak."/".$lomeni;

		$i = 0;
		$prevstat = "";
		$prevzst = "";
		$prevob = "";
		$vzdal = 0;

		$query131 = "SELECT * FROM kango.DTV WHERE (CISLO7='$cislo7');";
		if ($result131 = mysqli_query($link, $query131)) {
                    while ($row131 = mysqli_fetch_row($result131))  {
			$ZELEZN = $row131[1];
			$ZST = $row131[2];
			$OB = $row131[3];
			$i = $i + 1;
			$stop_id = substr($ZELEZN,-2).$ZST.substr($OB,-1);
		
			$pom139 = mysqli_fetch_row(mysqli_query($link, "SELECT stop_lat,stop_lon FROM stop WHERE (stop_id='$stop_id');"));
			$lat = $pom139[0];
			$lon = $pom139[1];

			$result235 = mysqli_query($link, "SELECT DELKA FROM kango.DU WHERE ((ZELEZN1 = '$prevstat') AND (ZST1 = '$prevzst') AND (OB1 = '$prevob') AND (ZELEZN2 = '$ZELEZN') AND (ZST2 = '$ZST') AND (OB2 = '$OB'));");
			$pom235 = mysqli_fetch_row($result235);
			$ujeto = $pom235[0];
			$radky = mysqli_num_rows($result235);
			if ($radky == 0) {
				$result240 = mysqli_query($link, "SELECT DELKA FROM kango.DU WHERE ((ZELEZN2 = '$prevstat') AND (ZST2 = '$prevzst') AND (OB2 = '$prevob') AND (ZELEZN1 = '$ZELEZN') AND (ZST1 = '$ZST') AND (OB1 = '$OB'));");
				$pom240 = mysqli_fetch_row($result240);
				$ujeto = $pom240[0];
			} 
			$vzdal = $vzdal + $ujeto;
			$prevstat = $ZELEZN;
			$prevzst = $ZST;
			$prevob = $OB;
			if ($lat != '' && $lon != '' && $i <= $max_trip && $i >= $min_trip) {
				if ($i == $min_trip) {$vzdal = 0;} 
			    $query144 = "INSERT INTO shape VALUES (
                            '$trip_id',
                            '$lat',
                            '$lon',
                            '$i',
                            '$vzdal'
                            );";
				$command = mysqli_query($link, $query144);
// zápis nové trasy do databáze
			}
		}
	}				
				
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
            }
	}
    }
}

echo "Exported lines: $routenums<br />";
echo "Exported trips: $tripnums<br />";

$current = "";

$act_calendar = "SELECT kalendar FROM kango.cal_use GROUP BY kalendar;";
if ($result189 = mysqli_query($link, $act_calendar)) {
    while ($row189 = mysqli_fetch_row($result189)) {
        $service_id_189 = $row189[0];

        $query193 = "SELECT service_id,monday,tuesday,wednesday,thursday,friday,saturday,sunday FROM calendar WHERE (service_id ='$service_id_189');";
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
    }
}	

$file = 'calendar.txt';
file_put_contents($file, $current, FILE_APPEND);
//zapsány použité kalendáře

$current = "";

$act_stop = "SELECT stop_id FROM kango.stop_use GROUP BY stop_id;";
if ($result228 = mysqli_query($link, $act_stop)) {
    while ($row228 = mysqli_fetch_row($result228)) {
        $stop_id_228 = $row228[0];
		$stopnums = mysqli_num_rows($result228);
		
		$query233 = "SELECT stop_id,stop_name,stop_lat,stop_lon,location_type,parent_station,wheelchair_boarding FROM stop WHERE (stop_id = '$stop_id_228');";
        if ($result233 = mysqli_query($link, $query233)) {
            while ($row233 = mysqli_fetch_row($result233)) {
                $stop_id = $row233[0];
                $stop_name = $row233[1];
                $stop_lat = $row233[2];
                $stop_lon = $row233[3];
                $location_type = $row233[4];
                $parent_station = $row233[5];
                $wheelchair_boarding = $row233[6];

				$current .= "$stop_id,\"$stop_name\",$stop_lat,$stop_lon,$location_type,$parent_station,$wheelchair_boarding\n";
			}
		}
	}
}

$file = 'stops.txt';
file_put_contents($file, $current, FILE_APPEND);
//zapsány použité zastávky

echo "Exported stops: $stopnums<br />";

$current = "";

$query260 = "SELECT shape_id,shape_pt_lat,shape_pt_lon,shape_pt_sequence,shape_dist_traveled FROM shape;";
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
//zapsány použité tvary tras


include 'footer.php';
?>
