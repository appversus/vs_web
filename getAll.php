<?php
header("Access-Control-Allow-Origin: *");

$file = "db.sqlite";
$db = new SQLite3($file);

require 'selectAll.php';
$arr = selectAll($db);
echo json_encode($arr);