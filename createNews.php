<?php
error_reporting(E_ALL);
echo "<html><head></head><body>";

$time_start = microtime(true);
include 'admin.php';
include 'functions.php';

echo "<br>Create News<br>";
CreateNews($sitefinityHost,$newsServiceUrl, $cookie);

echo "Print News From Server<br>";
PrintNews($sitefinityHost, $newsServiceUrl, $cookie);


$time_end = microtime(true);
$time = $time_end - $time_start;
echo "<br>Ran this Script in $time seconds";
echo "</body></html>";
?>