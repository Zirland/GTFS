<?php
include 'header.php';

function getContrastYIQ ($hexcolor){
	$r = hexdec (substr ($hexcolor,0,2));
	$g = hexdec (substr ($hexcolor,2,2));
	$b = hexdec (substr ($hexcolor,4,2));
	$yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
	return ($yiq >= 128) ? '000000' : 'FFFFFF';
}

$file = 'log.txt';
$current = "";
file_put_contents($file, $current);

$query = "SELECT * FROM routelist LIMIT 30;";
if ($result = mysqli_query ($link, $query)) {
	while ($row = mysqli_fetch_row ($result)) {
		$cislo7 = $row[0];

		echo "$cislo7<br />";

		$query9 = "SELECT CISLO7, JMENVL, IDDOP from kango.HLV WHERE cislo7='$cislo7';";
		if ($result9 = mysqli_query ($link, $query9)) {
			while ($row9 = mysqli_fetch_row ($result9)) {
				$agency_id = $row9[2];
				$cislo7 = $row9[0];
				$route_short_name = substr ($cislo7, 0, -2);
				$route_id = "L".$route_short_name;
				$route_type = 2;

				$query19 = "SELECT DRUH from kango.KDV WHERE cislo7='$cislo7';";
				if ($result19 = mysqli_query ($link, $query19)) {
					while ($row19 = mysqli_fetch_row ($result19)) {
						$druh = $row19[0];

						switch($druh) {
							case "RLJ" :
							case "EC" :
							case "IC" :
							case "SC" :
							case "Ex" :
							case "Rx" :
								$route_color = "008000";
							break;

							case "R" :
							case "EN" :
								$route_color = "B51741";
							break;

							case "RJ" :
								$route_color = "ECAE01";
							break;

							case "AEx" :
								$route_color = "008983";
							break;

							case "LE" :
								$route_color = "000000";
							break;

							case "Sp" :
							case "Os" :
							case "TLX" :
							case "TL" :
								$route_color = "0094DE";
							break;

							default :
								$route_color = "0094DE";
							break;
						}
					}
				}

				if ($agency_id == '153') {$route_color = "008983";}

				$route_text_color = getContrastYIQ ($route_color);

				$query26 = "INSERT INTO route (route_id, agency_id, route_short_name, route_long_name, route_type, route_color, route_text_color,active) VALUES ('$route_id' ,'$agency_id','$route_short_name','','$route_type','$route_color','$route_text_color','1');";
				$prikaz26 = mysqli_query ($link, $query26);
			}
		}

		$suffix = "0";
		$i = 0;
		$miss = 0;

		$zststrt = 0;
		$zststp = 0;

		$query13 = "SELECT * FROM kango.KDV WHERE cislo7= '$cislo7';";
		if ($result13 = mysqli_query ($link, $query13)) {
			while ($row13 = mysqli_fetch_row ($result13)) {
				$ZELEZNZ = $row13[2];
				$ZSTZ = $row13[3];
				$OBZ = $row13[4];
				$ZELEZNDO = $row13[5];
				$ZSTDO = $row13[6];
				$OBDO = $row13[7];

				$pomz = substr ($ZELEZNZ,-2).$ZSTZ.substr ($OBZ,-1);
				$pomdo = substr ($ZELEZNDO,-2).$ZSTDO.substr ($OBDO,-1);

				if ($zststrt == 0) {$zststrt = $pomz;}
				if ($zststp == 0) {$zststp = $pomdo;}
				if ($zststp == $pomz) {$zststp = $pomdo;}
			}
		}

		$start = 0;
		$end = 0;

		$query7 = "SELECT * FROM kango.DTV WHERE (CISLO7 = '$cislo7');";
		if ($result7 = mysqli_query ($link, $query7)) {
			while ($row7 = mysqli_fetch_row ($result7)) {
				$lomeni = substr ($cislo7,-1);
				$vlak = substr ($cislo7, 0, -2);

				$route_id = "L".$vlak;

				$cyklo = 0;
				$invalida = 0;
				$query21 = "SELECT POZNAM,KODZNAC FROM kango.OBP WHERE (CISLO7='$cislo7');";
				if ($result21 = mysqli_query ($link, $query21)) {
					while ($row21 = mysqli_fetch_row ($result21)) {
						$poznamka = $row21[0];
						$znacka = $row21[1];

						switch ($znacka) {
							case 8:
							case 9:
								$cyklo = 1;
							break;

							case 7:
								$invalida = 1;
							break;
						}

						if (strpos ($poznamka, "jízdní kolo") !== false) {
							$cyklo=1;
						}
						if (strpos ($poznamka, "jízdních kol") !== false) {
							$cyklo=2;
						}
						if (strpos ($poznamka, "vozík") !== false) {
							$invalida=1;
						}
					}
				}

				$calpom = $row7[37];

				switch ($calpom) {
					case '':
						$novamatice='';
					break;
					case '1':
						for ($j = 0; $j < 365; $j++) {
							$novamatice.="1";
						}
						$suffix = $suffix + 1;
					break;
					default :
						$pom6 = mysqli_fetch_row (mysqli_query ($link, "SELECT * FROM kango.KVL WHERE (KALENDAR ='$calpom');"));
						$novamatice = $pom6[7];
						$suffix = $suffix + 1;
					break;
				}

				if ($novamatice != '') {
					$trip_id = $vlak.$lomeni.$suffix;
					$poradi = substr($trip_id, -3, 1);

					switch ($poradi % 2) {
						case "0" :
							$odd = "1";
						break;
						case "1" :
							$odd = "0";
						break;
					}

					$query60 = "INSERT INTO trip VALUES ('$route_id','$novamatice','$trip_id','','','$odd','','','$invalida','$cyklo','1');";
					$prikaz60 = mysqli_query ($link, $query60);
				}

				$ZELEZN = $row7[1];
				$ZST = $row7[2];
				$OB = $row7[3];
				$STKOLPRIJ = $row7[6];
				$DP = $row7[7];
				$HP = $row7[8];
				$MP = $row7[9];
				$SP = $row7[10];
				$DO = $row7[13];
				$HO = $row7[14];
				$MO = $row7[15];
				$SO = $row7[16];
				$STKOLODJ= $row7[17];
				$ZNAM = $row7[28];
				$PICK = $row7[30];
				$DROP = $row7[31];
				$ODJCASPRIJ = $row7[49];
				$i = $i + 1;

				$nast = 0;
				$vyst = 0;

				$aktual = substr ($ZELEZN,-2).$ZST.substr ($OB,-1);
				$ignore = 0;
				if ($start==0 && $end==0 && $aktual!=$zststrt) {
					$ignore = 1;
				}
				if ($start==1 && $end==1) {
					$ignore = 1;
				}
				if ($start==0 && $aktual==$zststrt) {
					$start = 1;
					$ignore = 0;
					$DP=$DO;
					$HP=$HO;
					$MP=$MO;
					$SP=$SO;
				}
				if ($start==1 && $aktual==$zststp) {
					$end = 1;
					$ignore = 0;
					$DO=$DP;
					$HO=$HP;
					$MO=$MP;
					$SO=$SP;
				}
				if ($ZELEZN != '0054') {
					$ignore=1;
				}

				switch ($ZNAM) {
					case '1' :
						$nast = 3;
						$vyst = 3;
					break;
					default :
						if ($PICK == '1') {
							$vyst = 1;
						}
						if ($DROP == '1') {
							$nast = 1;
						}
					break;
				}

				$hdp = ($DP * 24) + $HP;
				if ($hdp < 10) {
					$hdp="0".$hdp;
				}

				$mnp = $MP;
				if ($mnp < 10) {
					$mnp="0".$mnp;
				}

				$hdo = ($DO * 24) + $HO;
				if ($hdo < 10) {
					$hdo="0".$hdo;
				}

				$mno = $MO;
				if ($mno < 10) {
					$mno="0".$mno;
				}

				if ($DP == '') {
					$hdp=$hdo;
					$mnp=$mno;
					$SP=$SO;
					$HP='0';
				}

				if ($DO == '') {
					$hdo=$hdp;
					$mno=$mnp;
					$SO=$SP;
					$HO='0';
				}

				$arrival = $hdp.":".$mnp;

				if ($arrival == "00:0" || $arrival == "24:0" || $arrival == "48:0" || $arrival =="72:0") {
					$ignore = 1;
				}

				switch ($SP) {
					case 0 :
						$arrival=$arrival.":00";
					break;
					case 1 :
						$arrival=$arrival.":30";
					break;
				}

				$depart = $hdo.":".$mno;
				switch ($SO) {
					case 0 :
						$depart=$depart.":00";
					break;
					case 1 :
						$depart=$depart.":30";
					break;
				}

				if ($arrival == "24:0:00") {
					$arrival = "24:00:00";
				}
				if ($depart == "24:0:00") {
					$depart = "24:00:00";
				}

				$stop_id = substr ($ZELEZN,-2).$ZST.substr ($OB,-1);
				$query88 = "SELECT stop_name, active FROM stop WHERE stop_id = '$stop_id';";
				if ($result88 = mysqli_query ($link, $query88)) {
					$radky=mysqli_num_rows ($result88);
					$row88 = mysqli_fetch_row ($result88);
					$jmeno = $row88[0];
					$aktivita = $row88[1];

					if ($aktivita == "2" && $arrival != "00:0:00") {
						$nast = 2;
						$vyst = 2;
						$ignore = 0;
					}
					if ($aktivita == "0") {
						$ignore = 1;
					}

					if ($radky==0 && $ignore==0) {
						$pomdbname = mysqli_fetch_row (mysqli_query ($link, "SELECT NAZEVDB FROM kango.DB WHERE (ZELEZN='$ZELEZN' AND ZST='$ZST' AND OB='$OB');"));
						$dbname = $pomdbname[0];
						file_put_contents ($file, "Missing stop $stop_id - $dbname [$aktivita] for trip $trip_id\n" , FILE_APPEND);
					}
				}

				if ($miss==0 && $ignore==0) {
					if ($novamatice != '') {
						$pomsfx=$suffix - 1;
						$pom_trip_id = $vlak.$lomeni.$pomsfx;
						if ($pomsfx > 0) {
							$query60 = "INSERT INTO stoptime VALUES ('$pom_trip_id','$arrival','$arrival','$stop_id','$i','','$nast','$vyst','','');";
							$prikaz6O = mysqli_query ($link, $query60);
							$arrival = $depart;

							$pomendstop = mysqli_fetch_row (mysqli_query ($link, "SELECT MAX(stop_sequence) FROM stoptime WHERE (trip_id = '$pom_trip_id' AND pickup_type != '2');"));
							$endstopno = $pomendstop[0];

							$pomfinstop=mysqli_fetch_row (mysqli_query ($link, "SELECT stop_id FROM stoptime WHERE (trip_id='$pom_trip_id' AND stop_sequence='$endstopno');"));
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

							$pom163 = mysqli_fetch_row (mysqli_query ($link, "SELECT max(stop_sequence) FROM stoptime WHERE (trip_id = '$pom_trip_id');"));
							$max_trip = $pom163[0];
							$pom129 = mysqli_fetch_row (mysqli_query ($link, "SELECT min(stop_sequence) FROM stoptime WHERE (trip_id = '$pom_trip_id');"));
							$min_trip = $pom129[0];

							$tvartrasy = "";
							$ii = 0;

							$query131 = "SELECT * FROM kango.DTV WHERE (CISLO7='$cislo7');";
							if ($result131 = mysqli_query ($link, $query131)) {
								while ($row131 = mysqli_fetch_row ($result131))  {
									$stopstat = $row131[1];
									$stopzst = $row131[2];
									$stopob = $row131[3];
									$ZST = substr ($stopstat,-2).$stopzst.substr ($stopob,-1);
									$ii = $ii + 1;

									if ($ii <= $max_trip && $ii >= $min_trip) {
										$tvartrasy .= $ZST."|";
									}
								}
							}

							$query1701="UPDATE trip SET trip_headsign='$headsign', shape_id='$tvartrasy' WHERE trip_id='$pom_trip_id';";

							$prikaz1701=mysqli_query ($link, $query1701);

						}
					}

					if ($ODJCASPRIJ == 1) {$depart = $arrival;}

					$query3 = "INSERT INTO stoptime VALUES ('$trip_id','$arrival','$depart','$stop_id','$i','','$nast','$vyst','','');";

					$command = mysqli_query ($link, $query3) or die ("Stop Error description: ".mysqli_error ($link));
				}

				$miss = 0;
				$ignore = 0;
			}
		}

		$pomendstop = mysqli_fetch_row (mysqli_query ($link, "SELECT MAX(stop_sequence) FROM stoptime WHERE (trip_id = '$trip_id' AND pickup_type != '2');"));
		$endstopno = $pomendstop[0];

		$pomfinstop=mysqli_fetch_row (mysqli_query ($link, "SELECT stop_id FROM stoptime WHERE (trip_id='$trip_id' AND stop_sequence='$endstopno');"));
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

		$pom163 = mysqli_fetch_row (mysqli_query ($link, "SELECT max(stop_sequence) FROM stoptime WHERE (trip_id = '$trip_id');"));
		$max_trip = $pom163[0];

		$pom129 = mysqli_fetch_row (mysqli_query ($link, "SELECT min(stop_sequence) FROM stoptime WHERE (trip_id = '$trip_id');"));
		$min_trip = $pom129[0];

		$tvartrasy = "";
		$ii = 0;

		$query131 = "SELECT * FROM kango.DTV WHERE (CISLO7='$cislo7');";
		if ($result131 = mysqli_query ($link, $query131)) {
			while ($row131 = mysqli_fetch_row ($result131))  {
				$stopstat = $row131[1];
				$stopzst = $row131[2];
				$stopob = $row131[3];
				$ZST = substr ($stopstat,-2).$stopzst.substr ($stopob,-1);
				$ii = $ii + 1;

				if ($ii <= $max_trip && $ii >= $min_trip) {
					$tvartrasy .= $ZST."|";
				}
			}
		}

		$query1701="UPDATE trip SET trip_headsign='$headsign', shape_id='$tvartrasy' WHERE trip_id='$trip_id';";
		$prikaz1701=mysqli_query ($link, $query1701);

		$query_min = mysqli_fetch_row (mysqli_query ($link, "SELECT min(stop_sequence) FROM stoptime WHERE (pickup_type != '2' AND trip_id IN (SELECT trip_id FROM trip WHERE route_id='$route_id'));"));
		$min = $query_min[0];

		$query_min_id = mysqli_fetch_row (mysqli_query ($link, "SELECT stop_id FROM stoptime WHERE stop_sequence=$min AND trip_id IN (SELECT trip_id FROM trip WHERE route_id='$route_id');"));
		$min_id = $query_min_id[0];
		$pomminstopparent = mysqli_fetch_row (mysqli_query ($link, "SELECT parent_station FROM stop WHERE stop_id='$min_id';"));
		$minstopparent = $pomminstopparent[0];
		if ($minstopparent == '') {
			$minstopid = $min_id;
		} else {
			$minstopid = $minstopparent;
		}
		$query_min_name = mysqli_fetch_row (mysqli_query ($link, "SELECT stop_name FROM stop WHERE stop_id='$minstopid';"));
		$min_name = $query_min_name[0];

		$query_max = mysqli_fetch_row (mysqli_query ($link, "SELECT max(stop_sequence) FROM stoptime WHERE (pickup_type != '2' AND trip_id IN (SELECT trip_id FROM trip WHERE route_id='$route_id'));"));
		$max = $query_max[0];

		$query_max_id = mysqli_fetch_row (mysqli_query ($link, "SELECT stop_id FROM stoptime WHERE stop_sequence=$max AND trip_id IN (SELECT trip_id FROM trip WHERE route_id='$route_id');"));
		$max_id = $query_max_id[0];
		$pommaxstopparent = mysqli_fetch_row (mysqli_query ($link, "SELECT parent_station FROM stop WHERE stop_id='$max_id';"));
		$maxstopparent = $pommaxstopparent[0];
		if ($maxstopparent == '') {
			$maxstopid = $max_id;
		} else {
			$maxstopid = $maxstopparent;
		}
		$query_max_name = mysqli_fetch_row (mysqli_query ($link, "SELECT stop_name FROM stop WHERE stop_id='$maxstopid';"));
		$max_name = $query_max_name[0];

		if ($route_long_name != '') {
			$wholename = "$route_long_name | $min_name – $max_name";
		} else {
			$wholename = "$min_name – $max_name";
		}

		$query364 = "UPDATE route SET route_long_name='$wholename' WHERE route_id='$route_id';";
		$command364 = mysqli_query ($link, $query364);

		$query4 = "DELETE FROM routelist WHERE (cislo7 = '$cislo7');";
		$command4 = mysqli_query ($link, $query4);
	}
}

include 'footerphp';
?>