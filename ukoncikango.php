<?php
include 'header.php';

$query4 = "SELECT trip_id, matice FROM trip WHERE trip_id NOT LIKE 'F%';";
if ($result4 = mysqli_query ($link, $query4)) {
	while ($row4 = mysqli_fetch_row ($result4)) {
		$trip_id = $row4[0];
		$matice = $row4[1];

		$maticestart = mktime (0,0,0,12,10,2017);
		$datumod = "05022018";
		$datumdo = "08122018";

		$Dod = substr ($datumod,0,2); $Mod = substr ($datumod,2,2); $Yod = substr ($datumod,-4); $timeod = mktime (0,0,0,$Mod, $Dod, $Yod);
		$zacdnu = round(($timeod - $maticestart) / 86400); 
		$Ddo = substr ($datumdo,0,2); $Mdo = substr ($datumdo,2,2); $Ydo = substr ($datumdo,-4); $timedo = mktime (0,0,0,$Mdo, $Ddo, $Ydo);

		$kondnu = round(($timedo - $maticestart) / 86400); 

		for ($g = 0; $g < 365; $g++) {
			if ($g>=$zacdnu && $g <=$kondnu) {$matice[$g] = 0;}
		}

		$query24 = "UPDATE trip SET matice = '$matice' WHERE trip_id = '$trip_id';";
		$prikaz24 = mysqli_query ($link, $query24);
	}
}

include 'footer.php';
?>