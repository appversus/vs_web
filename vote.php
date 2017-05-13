<?php

header("Access-Control-Allow-Origin: *");

$vote = $_POST['vote'];
$country = $_POST['country'];

if (1 != $vote && 2 != $vote) {
    die('wrong post vote');
}

$file = "db.sqlite";
$db = new SQLite3($file);
vote($db);

function vote($db) {
    global $vote, $country;

    $sql = "UPDATE votes SET (v{$vote}) VALUES (v{$vote} + 1) WHERE cn = ?";
    $stmt = $db->prepare($sql);
    if (!$db) {
        die($db->error . " in $sql");
    }
    $stmt->bindParam(1, $country, SQLITE3_TEXT);

    $result = $stmt->execute();
    if (!$result) {
        //prevent loop
        if (isset($GLOBALS['createTable'])) {
            die("undefined error");
        }
        $GLOBALS['createTable'] = true;

        //if table not exists
        $result = $db->query("SELECT 1 FROM testtable LIMIT 1;");
        if (!count($result)) {
            require 'createTable.php';
            createTable($db);
            vote($db);
        } else {
            //can be not defined country: (not error)
            echoAll($db);
        }
    }
}

//temp file
if (!empty($_POST['pos'])) {
    $pos = $_POST['pos'];
    $filename = "temp.txt";
    $time = time();
    if ($time - filemtime($filename) > 60) {
        $handle = fopen("temp.txt", "w"); //empty
    } else {
        $handle = fopen("temp.txt", "a"); //append
    }
    fwrite($handle, "$time;$pos|");
}

echoAll($db);

function echoAll($db) {
    require 'selectAll.php';
    $arr = selectAll($db);
    echo json_encode($arr);
    die();
}
