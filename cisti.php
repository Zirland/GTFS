<?php
$query = "SELECT matice,trip_id FROM trip WHERE active=1;";
if ($result = mysqli_query ($link, $query)) {
	while ($row = mysqli_fetch_row ($result)) {
		$matice = $row[0];
		$trip_id = $row[1];

		$matice = "0000000000".$matice."000000000000000"; // jedna nula na začátku musí navíc
		$matice_start = mktime (0,0,0,12,1,2017);
		$matice_end = mktime (0,0,0,12,8,2018);

		$dnes_den = date ("j", time ());
		$dnes_mesic = date ("n", time ());
		$dnes_rok = date ("Y", time ());

		$calendar_start = mktime (0,0,0,$dnes_mesic,$dnes_den,$dnes_rok);

		$sek = $calendar_start - $matice_start;
		$min = floor ($sek / 60);
		$sek = $sek % 60;
		$hod = floor ($min / 60);
		$min = $min % 60;
		$dni = floor ($hod / 24);
		$hod = $hod % 24;

		$sek2 = $matice_end - $matice_start;
		$min2 = floor ($sek2 / 60);
		$sek2 = $sek2 % 60;
		$hod2 = floor ($min2 / 60);
		$min2 = $min2 % 60;
		$dni2 = floor ($hod2 / 24);
		$hod2 = $hod2 % 24;

		$zbyva = $dni2 - $dni;
		$aktual = substr ($matice,$dni + 1,$zbyva);

		$soucet = 0;
		$rozklad = str_split ($aktual);
		foreach ($rozklad as $den) {
			$soucet = $soucet + $den;
		}

		if ($soucet == 0) {
			$prikaz = mysqli_query ($link, "UPDATE trip SET active=0 WHERE trip_id = '$trip_id';");
		}
	}
}

$query1 = "SELECT route_id FROM route WHERE route_id NOT IN (SELECT DISTINCT route_id FROM trip WHERE active=1);";
if ($result1 = mysqli_query ($link, $query1)) {
	while ($row1 = mysqli_fetch_row ($result1)) {
		$route_id = $row1[0];

		$prikaz3 = mysqli_query ($link, "UPDATE route SET active=0 WHERE route_id = '$route_id';");
	}
}
?>