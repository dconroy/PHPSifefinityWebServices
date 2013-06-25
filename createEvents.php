<?php
error_reporting(E_ALL);
echo "<html><head></head><body>";

$time_start = microtime(true);
include 'admin.php';
include 'functions.php';

echo "<br>Create Events<br>";
CreateEvents($sitefinityHost,$eventServiceUrl, $cookie);

echo "Print Events From Server<br>";
PrintEvents($sitefinityHost, $eventServiceUrl, $cookie);


$time_end = microtime(true);
$time = $time_end - $time_start;
echo "<br>Ran this Script in $time seconds";
echo "</body></html>";
?>