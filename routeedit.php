<?php
include 'header.php';

$route = $_GET['id'];
$action = $_POST['action'];

switch ($action) {
	case "oprav" :

	$route = $_POST['route_id'];
	$dopravce = $_POST['dopravce'];
	$shortname = $_POST['shortname'];
	$longname = $_POST['longname'];
	$pozadi = $_POST['route_pozadi'];
	$foreground = $_POST['foreground'];
	$kraj = $_POST['kraj'];
	$aktif = $_POST['aktif'];

	$ready0 = "UPDATE route SET agency_id='$dopravce', route_short_name='$shortname', route_long_name='$longname', route_color='$pozadi', route_text_color='$foreground', kraj = '$kraj', active='$aktif' WHERE (route_id = '$route');";
	$aktualz0 = mysqli_query ($link, $ready0);
}

echo "<table><tr><td>";
echo "<table>";
echo "<tr>";

$hlavicka = mysqli_fetch_row (mysqli_query ($link, "SELECT * FROM route WHERE (route_id='$route');"));
$route_id = $hlavicka[0];
$agency_id = $hlavicka[1];
$route_short_name = $hlavicka[2];
$route_long_name = $hlavicka[3];
$route_color = $hlavicka[7];
$route_text_color = $hlavicka[8];
$route_kraj = $hlavicka[10];
$route_active = $hlavicka[9];

echo "<form method=\"post\" action=\"routeedit.php\" name=\"oprav\"><input name=\"action\" value=\"oprav\" type=\"hidden\"><input name=\"route_id\" value=\"$route_id\" type=\"hidden\">";
echo "<td>Dopravce: <select name=\"dopravce\">";

$query24 = "SELECT agency_id, agency_name FROM agency ORDER BY agency_id;";
if ($result24 = mysqli_query ($link, $query24)) {
	while ($row24 = mysqli_fetch_row ($result24)) {
		$agid = $row24[0];
		$agname = $row24[1];

		echo "<option value=\"$agid\"";
		if ($agid == $agency_id) {
			echo " SELECTED";
		}
		echo ">$agname</option>";
	}
}
echo "</select></td><td style=\"background-color : #$route_color;\">Linka: <input type=\"text\" name=\"shortname\" size=\"10\" value=\"$route_short_name\">";

echo "<input type=\"text\" name=\"kraj\" size=\"1\" value=\"$route_kraj\"><br />";
echo "<input type=\"text\" name=\"longname\" value=\"$route_long_name\"></td>";

echo "<td>Pozadí: <select name=\"route_pozadi\">";

$query37 = "SELECT color, popis FROM kango.colors;";
if ($result37 = mysqli_query ($link, $query37)) {
	while ($row37 = mysqli_fetch_row ($result37)) {
		$rtclr = $row37[0];
		$clrnm = $row37[1];

		echo "<option value=\"$rtclr\"";
		if ($rtclr == $route_color) {
			echo " SELECTED";
		}
		echo ">$clrnm</option>";
	}
}

echo "</select><br />";

echo "<input type=\"text\" name=\"foreground\" value=\"$route_text_color\"></td>";
echo "<td>Aktivní <input type=\"checkbox\" name=\"aktif\" value=\"1\"";
if ($route_active == '1') {
	echo " CHECKED";
}
echo "></td><td><input type=\"submit\"></td></tr></form></table>";

echo "<table>";
echo "<tr><th>Linky odchozí</th><th>Linky příchozí</th></tr>";
echo "<tr><td>";

$query80 = "SELECT * FROM trip WHERE ((route_id = '$route_id') AND (direction_id = '0')) ORDER BY trip_id;";
if ($result80 = mysqli_query ($link, $query80)) {
	while ($row80 = mysqli_fetch_row ($result80)) {
		$trip_id = $row80[2];
		$trip_headsign = $row80[3];
		$trip_aktif = $row80[10];
		$vlak = $trip_id;
		if ($trip_aktif == '1') {
			echo "<span style=\"background-color:#54FF00;\">";
		}
		echo "$vlak - $trip_headsign - <a href=\"tripedit.php?id=$trip_id\">Upravit</a>";
		if ($trip_aktif == '1') {
			echo "</span>";
		}
		$cislo7 = substr ($trip_id, 0, -2)."/".substr ($trip_id,-2,1);
		$query114 = "SELECT POZNAM FROM kango.OBP WHERE CISLO7='$cislo7';";
		if ($result114 = mysqli_query ($link, $query114)) {
			while ($row114 = mysqli_fetch_row ($result114)) {
				$poznamka = $row114[0];
				if (strpos ($poznamka, "linka") !== false) {
					echo "$poznamka";
				}
			}
		}
		echo "<br/>";
	}
}
echo "</td><td>";
$query96 = "SELECT * FROM trip WHERE ((route_id = '$route_id') AND (direction_id = '1')) ORDER BY trip_id;";
if ($result96 = mysqli_query ($link, $query96)) {
	while ($row96 = mysqli_fetch_row ($result96)) {
		$trip_id = $row96[2];
		$trip_headsign = $row96[3];
		$trip_aktif = $row96[10];
		$vlak = $trip_id;
		if ($trip_aktif == '1') {
			echo "<span style=\"background-color:#54FF00;\">";
		}
		echo "$vlak - $trip_headsign - <a href=\"tripedit.php?id=$trip_id\">Upravit</a>";
		if ($trip_aktif == '1') {
			echo "</span>";
		}
		$cislo7 = substr ($trip_id, 0, -2)."/".substr ($trip_id,-2,1);
		$query114 = "SELECT POZNAM FROM kango.OBP WHERE CISLO7='$cislo7';";
		if ($result114 = mysqli_query ($link, $query114)) {
			while ($row114 = mysqli_fetch_row ($result114)) {
				$poznamka = $row114[0];
				if (strpos ($poznamka, "linka") !== false) {
					echo "$poznamka";
				}
			}
		}
		echo "<br/>";
	}
}
echo "</td></tr></table>";

include 'footer.php';
?>