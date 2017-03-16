<?php
include 'header.php';

$query = "SELECT * FROM kango.vlaktrips WHERE (trips=1) LIMIT 100;";
if ($result = mysqli_query($link, $query)) {
	while ($row = mysqli_fetch_row($result)) {
		$cislo7 = $row[0];
		echo "$cislo7<br />";
		
		$lomeni = substr($cislo7,-1);
		$vlak = substr($cislo7, 0, -2);
		$trip_id = $vlak.$lomeni."A";
		
		$hlavicka = mysqli_fetch_row(mysqli_query($link, "SELECT JMENVL FROM kango.HLV WHERE (CISLO7='$cislo7');"));
		$jmeno = $hlavicka[0];
		
		$pom5 = mysqli_fetch_row(mysqli_query($link, "SELECT KALENDAR FROM kango.DTV WHERE (CISLO7='$cislo7');"));
		$calpom = $pom5[0];

		switch ($calpom) {
			case '1': 
				$matice="1111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111";
			break;
			default : 
				$pom6 = mysqli_fetch_row(mysqli_query($link, "SELECT * FROM kango.KVL WHERE (KALENDAR ='$calpom');"));
				$matice = $pom6[7];
			break;
		}

		$query1 = "INSERT INTO trip VALUES (
			'0',
			'$matice',
			'$trip_id',
			'',
			'$jmeno',
			'0',
			'',
			'$trip_id',
			'0',
			'0',
			'0'
		);";
		$command = mysqli_query($link, $query1) or die("Trip Error description: " . mysqli_error($link));

		$i = 0;
		$query2 = "SELECT * FROM kango.DTV WHERE (CISLO7='$cislo7');";
		if ($result2 = mysqli_query($link, $query2)) {
			while ($row2 = mysqli_fetch_row($result2)) {
				$ZST = $row2[2];
				$DP = $row2[7];
				$HP = $row2[8];
				$MP = $row2[9];
				$SP = $row2[10];
				$DO = $row2[13];
				$HO = $row2[14];
				$MO = $row2[15];
				$SO = $row2[16];
				$PICK = $row2[30];
				$DROP = $row2[31];
				$ZNAM = $row2[28];
				$i = $i + 1;
				
				$nast = 0;
				$vyst = 0;
				
				switch ($ZNAM) {
					case '1' : $nast = 3; $vyst = 3; break;
					default : 
						if ($PICK == '1') {$vyst = 1;}
						if ($DROP == '1') {$nast = 1;}
				}
				
				$hdp = ($DP*24)+$HP;
				if ($hdp<10) $hdp="0".$hdp;
				$mnp = $MP;
				if ($mnp<10) $mnp="0".$mnp;
				$hdo = ($DO*24)+$HO;
				if ($hdo<10) $hdo="0".$hdo;
				$mno = $MO;
				if ($mno<10) $mno="0".$mno;
				
				if ($DP == '') {$hdp=$hdo; $mnp=$mno; $SP=$SO; $HP='0';}
				if ($DO == '') {$hdo=$hdp; $mno=$mnp; $SO=$SP; $HO='0';}
				
				if ($mnp != '0' && $mno != '0') {
			
					$arrival = $hdp.":".$mnp;
					switch ($SP) {
						case 0 : $arrival=$arrival.":00"; break;
						case 1 : $arrival=$arrival.":30"; break;
					}
					$depart = $hdo.":".$mno;
					switch ($SO) {
						case 0 : $depart=$depart.":00"; break;
						case 1 : $depart=$depart.":30"; break;
					}
					
					$query3 = "INSERT INTO stoptime VALUES (
					'$trip_id',
					'$arrival',
					'$depart',
					'$ZST',
					'$i',
					'',
					'$nast',
					'$vyst',
					'',
					''
					);";
				$command = mysqli_query($link, $query3) or die("Stop Error description: " . mysqli_error($link));
				
				$query4 = "DELETE FROM kango.vlaktrips WHERE (cislo7 = '$cislo7');";
				$command = mysqli_query($link, $query4) or die("Stop Error description: " . mysqli_error($link));
				}
			}
		}
	}
}


include 'footer.php';
?>
