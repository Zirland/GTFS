<?php
include 'header.php';

$t = @$_GET['t'];
$action = @$_POST['action'];
$t2 = @$_POST['table'];

echo "$action $t2";

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
                     
            $query = "INSERT INTO agency VALUES ('$agency_id', '$agency_name', '$agency_url', '$agency_timezone', '$agency_lang', '$agency_phone');";
            $command = mysqli_query($link, $query);
            $time = time();
            $log = mysqli_query($link, "INSERT INTO log (timestamp,entry) VALUES ('$time', '$query');");
            
        break;
    }      
    break;
}

switch ($t) {
    case 'ag' :
        echo "<form method=\"post\" action=\"add.php\" name=\"generuj\"><input name=\"action\" value=\"generuj\" type=\"hidden\"><input name=\"table\" value=\"agency\" type=\"hidden\">";
        echo "<table>";
        echo "<tr>";
        echo "<th>Agency id</th><th>Agency name</th><th>Agency url</th><th>Agency timezone</th><th>Agency lang</th><th>Agency phone</th>";
        echo "</tr>";
        echo "<tr>";
        echo "<td><input name=\"agency_id\" value=\"\" type=\"text\"></td>";
        echo "<td><input name=\"agency_name\" value=\"\" type=\"text\"></td>";
        echo "<td><input name=\"agency_url\" value=\"\" type=\"text\"></td>";
        echo "<td><input name=\"agency_timezone\" value=\"Europe/Prague\" type=\"text\"></td>";
        echo "<td><input name=\"agency_lang\" value=\"cs\" type=\"text\"></td>";
        echo "<td><input name=\"agency_phone\" value=\"\" type=\"text\"></td>";
        echo "<td><input type=\"submit\"></td>";
        echo "</tr></table></form>";
    break;


    case 'route' :
        echo "<table>";
        echo "<tr>";
        echo "<th>Route id</th><th>Agency ID</th><th>Route short</th><th>Route long</th><th>Route desc</th><th>Route type</th><th>Route url</th><th>Route color</th><th>Route text color</th>";
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