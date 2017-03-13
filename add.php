<?php
include 'header.php';

$t = @$_GET['t'];
$action = @$_POST['action'];
$t2 = @$_POST['table'];

switch ($action) {
	case 'generuj' :
	switch ($t2) {
		case 'agency' :
			$agency_id = $_POST['agency_id'];
			$agency_name = $_POST['agency_name'];
			$agency_url = $_POST['agency_url'];
			$agency_timezone = $_POST['agency_timezone'];
			$agency_lang = $_POST['agency_lang'];
			$agency_phone = $_POST['agency_phone'];
			$agency_fare_url = $_POST['agency_fare_url'];
			$agency_email = $_POST['agency_email'];

			$query = "INSERT INTO agency VALUES (
			'$agency_id',
			'$agency_name',
			'$agency_url',
			'$agency_timezone',
			'$agency_lang',
			'$agency_phone',
			'$agency_fare_url',
			'$agency_email',
			'0'
			);";
			$command = mysqli_query($link, $query);
			$t = "ag";
		break;

		case 'route' :
			$route_id = $_POST['route_id'];
			$agency_id = $_POST['agency_id'];
			$route_short = $_POST['route_short'];
			$route_long = $_POST['route_long'];
			$route_desc = $_POST['route_desc'];
			$route_type = $_POST['route_type'];
			$route_url = $_POST['route_url'];
			$route_color = $_POST['route_color'];
			$route_text_color = $_POST['route_text_color'];

			$query = "INSERT INTO route VALUES (
			'$route_id',
			'$agency_id',
			'$route_short',
			'$route_long',
			'$route_desc',
			'$route_type',
			'$route_url',
			'$route_color',
			'$route_text_color',
			'0'
			);";
			$command = mysqli_query($link, $query);
			$t = "ro";
		break;
	}      
	break;
}

switch ($t) {
	case 'ag' :
		echo "<form method=\"post\" action=\"add.php\" name=\"generuj\">
		<input name=\"action\" value=\"generuj\" type=\"hidden\">
		<input name=\"table\" value=\"agency\" type=\"hidden\">";
     	
		$ag_max_pom = mysqli_fetch_row(mysqli_query($link, "SELECT MAX(agency_id) FROM agency;"));
		$ag_max = $ag_max_pom['0'] + 1;

		echo "<table>";
		echo "<tr>";
		echo "<th>ID</th>
		<th>Název</th>
		<th>URL</th>
		<th>Časové pásmo</th></tr>
		<tr><th>Jazyk</th>
		<th>Telefon</th>
		<th>Tarifní podmínky</th>
		<th>E-mail</th>
		<th></th>";
		echo "</tr>";
		echo "<tr>";
		echo "<td><input name=\"agency_id\" value=\"".$ag_max."\" type=\"text\"></td>";
		echo "<td><input name=\"agency_name\" value=\"\" type=\"text\"></td>";
		echo "<td><input name=\"agency_url\" value=\"\" type=\"text\"></td>";
		echo "<td><input name=\"agency_timezone\" value=\"Europe/Prague\" type=\"text\"></td></tr>";
		echo "<tr><td><input name=\"agency_lang\" value=\"cs\" type=\"text\"></td>";
		echo "<td><input name=\"agency_phone\" value=\"\" type=\"text\"></td>";
		echo "<td><input name=\"agency_fare_url\" value=\"\" type=\"text\"></td>";
		echo "<td><input name=\"agency_email\" value=\"\" type=\"text\"></td>";
		echo "<td><input type=\"submit\"></td>";
		echo "</tr></table></form>";
	break;

	case 'ro' :
		echo "<form method=\"post\" action=\"add.php\" name=\"generuj\">
		<input name=\"action\" value=\"generuj\" type=\"hidden\">
		<input name=\"table\" value=\"route\" type=\"hidden\">";
		
		$ro_max_pom = mysqli_fetch_row(mysqli_query($link, "SELECT MAX(route_id) FROM route;"));
		$ro_max = $ro_max_pom['0'] + 1;
        
		echo "<table>";
		echo "<tr>";
		echo "<th>ID</th>
		<th>Přepravce</th>
		<th>Linka</th>
		<th>Trasa</th></tr>
		<tr><th>Popis</th>
		<th>Typ</th>
		<th>URL trasy</th></tr>
		<tr><th>Pozadí linky</th>
		<th>Barva textu</th>
		<th></th>";
		echo "</tr>";
		echo "<tr>";
		echo "<td><input name=\"route_id\" value=\"".$ro_max."\" type=\"text\"></td>";
		echo "<td><select name=\"agency_id\">";

		$query0 = "SELECT agency_id, agency_name FROM agency ORDER BY agency_id;";
		if ($result0 = mysqli_query($link, $query0)) {
			while ($row0 = mysqli_fetch_row($result0)) {
				$kod = $row0[0];
				$nazev = $row0[1];

				echo "<option value=\"$kod\"";
				if ($kod == 1) {echo " SELECTED";}
				echo ">$nazev</option>";
			}
			mysqli_free_result($result0);
		} else echo("Error description: " . mysqli_error($link));
		echo "</select>";

		echo "<td><input name=\"route_short\" value=\"\" type=\"text\"></td>";
		echo "<td><input name=\"route_long\" value=\"\" type=\"text\"></td></tr>";
		echo "<tr><td><input name=\"route_desc\" value=\"\" type=\"text\"></td>";
		echo "<td><select name=\"route_type\">
		<option value=\"0\">tramvaj</option>
		<option value=\"1\">metro</option>
		<option value=\"2\" SELECTED>vlak</option>
		<option value=\"3\">autobus</option>
		<option value=\"4\">přívoz</option>
		<option value=\"5\">trolejbus</option>
		<option value=\"6\">visutá lanovka</option>
		<option value=\"7\">kolejová lanovka</option>
		</select>
		</td>";

		echo "<td><input name=\"route_url\" value=\"\" type=\"text\"></td></tr>";
		echo "<tr><td><input name=\"route_color\" value=\"\" type=\"text\"></td>";
		echo "<td><input name=\"route_text_color\" value=\"\" type=\"text\"></td>";
        echo "<td><input type=\"submit\"></td>";
        echo "</tr></table></form>";
	break;
}

include 'footer.php';
?>
