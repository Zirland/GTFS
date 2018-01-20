<?php
include 'header.php';

$query9 = "SELECT CISLO7, JMENVL from kango.HLV WHERE JMENVL!='';";
if ($result9 = mysqli_query ($link, $query9)) {
	while ($row9 = mysqli_fetch_row ($result9)) {
		$cislo7 = $row9[0];
		$route_long_name = $row9[1];
		$route_id = "L".substr ($cislo7,0,-2);

		$query26 = "UPDATE route SET route_long_name='$route_long_name' WHERE route_id='$route_id';";
		$prikaz26 = mysqli_query($link, $query26);
	}
}

include 'footer.php';
?>