<?php
header("Access-Control-Allow-Origin: *");

$file = "temp.txt";
if(file_exists($file)){
    echo file_get_contents("temp.txt");
}
