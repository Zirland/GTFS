<?php
$t = $_GET['t'];
include 'header.php';
switch ($t) {
    case 'ag' :
        echo "<table>";
        echo "<tr>";
        echo "<th>Agency id</th><th>Agency name</th><th>Agency url</th><th>Agency timezone</th><th>Agency lang</th><th>Agency phone</th><th></th>";
        echo "</tr>";
        echo "<tr>";
        $query = "SELECT * FROM agency ORDER BY agency_id";
        if ($result = mysqli_query($link, $query)) {
            while ($row = mysqli_fetch_row($result)) {
                $agency_id = $row[0];
                $agency_name = $row[1];
                $agency_url = $row[2];
                $agency_timezone = $row[3];
                $agency_lang = $row[4];
                $agency_phone = $row[5];

                echo "<td>$agency_id</td><td>$agency_name</td><td>$agency_url</td><td>$agency_timezone</td><td>$agency_lang</td><td>$agency_phone</td><td><a href=\"edit.php?t=agency&id=$agency_id\">Editovat</a></td>";                
            }
            mysqli_free_result($result);
        }
        echo "</tr>";
        echo "<table>";
        echo "LIST AGENCY";
    break;


    case 'ro' :
        echo "<table>";
        echo "<tr>";
        echo "<th>Route id</th><th>Agency ID</th><th>Route short</th><th>Route long</th><th>Route desc</th><th>Route type</th><th>Route url</th><th>Route color</th><th>Route text color</th><th></th>";
        echo "</tr>";
        echo "<tr>";
        $query = "SELECT * FROM route ORDER BY route_id";
        if ($result = mysqli_query($link, $query)) {
            while ($row = mysqli_fetch_row($result)) {
                $route_id = $row[0];
                $agency_id = $row[1];
                $route_short = $row[2];
                $route_long = $row[3];
                $route_desc = $row[4];
                $route_type = $row[5];
                $route_url = $row[6];
                $route_color = $row[7];
                $route_text_color = $row[8];

                echo "<td>$route_id</td><td>$agency_id</td><td>$route_short</td><td>$route_long</td><td>$route_desc</td><td>$route_type</td><td>$route_url</td><td>$route_color</td><td>$route_text_color</td><td><a href=\"edit.php?t=route&id=$route_id\">Editovat</a></td>";                
            }
            mysqli_free_result($result);
        }
        echo "</tr>";
        echo "<table>";
        echo "LIST AGENCY";
    break;
}
include 'footer.php';
?>