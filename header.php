<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=utf-8" http-equiv="content-type">
  <title>GTFS</title>
</head>
<body>

<?php
$link = mysqli_connect('localhost', 'gtfs', 'gtfs', 'GTFS2');
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    exit;
}
?>

<table style="width:100%; height:100%;">
<tr>
<td style="background-color:#cccccc;">
<a href="list.php?t=ro">&nbsp; &nbsp;</a>
</td>
<td style="height:5px; background-color:#cccccc;">
</td></tr>
<tr><td style="width:100px; background-color:yellow; vertical-align:top;">
<a href="opravy.php">Opravy</a><br />
<a href="migracestop.php">Migrace Praha</a><br />
<a href="tripedit.php">Tripedit</a><br />
<a href="newtrip.php">Newtrip</a><br />
</td>
<td>
