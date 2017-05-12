<?php
header("Access-Control-Allow-Origin: *");

if (!function_exists('sqlite_open')) {
   die("NO SQLITE ON THIS SERVER");
}

$file = "db.sqlite";
$db = new SQLite3($file);

include 'selectAll.php';
$arr = selectAll($db);
echo json_encode($arr);
