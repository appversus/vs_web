<?php
header("Access-Control-Allow-Origin: *");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!function_exists('sqlite_open')) {
   die("NO SQLITE ON THIS SERVER");
}

$file = "db.sqlite";
$db = new SQLite3($file);

include 'selectAll.php';
$arr = selectAll($db);
echo json_encode($arr);
