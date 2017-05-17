<?php

header("Access-Control-Allow-Origin: *");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

    $sql = "UPDATE votes SET v{$vote} = v{$vote} + 1 WHERE cn = ?";
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
        $result = $db->query("SELECT 1 FROM votes LIMIT 1;");
        if (!count($result)) {
            require 'createTable.php';
            createTable($db);
            vote($db);
        } else {
            //can be not defined country: (not error)
            echoAll($db);
        }
    }

    $whitelist = array(
        '127.0.0.1',
        '::1'
    );
    if (in_array($_SERVER['REMOTE_ADDR'], $whitelist) && 0 == $db->changes()) {
        echo "nothing changed in $sql";
        die();
    }
}

//temp file
if (!empty($_POST['pos']) && "null" != $_POST['pos']) {
    $pos = $_POST['pos'];
    $filename = "temp.txt";
    $time = time();
    
    //TODO: activarte this when app works!
//    if (file_exists($filename) && $time - filemtime($filename) > 60) {
//        $handle = fopen("temp.txt", "w"); //empty
//    } else {
        $handle = fopen("temp.txt", "a"); //append
//    }
    $seconds = intval($time / 1000);
    fwrite($handle, "$seconds;$pos;$vote|");
}

echoAll($db);

function echoAll($db) {
    require 'selectAll.php';
    $arr = selectAll($db);
    echo json_encode($arr);
    die();
}
