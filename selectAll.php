<?php

function selectAll($db) {
    $sql = 'SELECT * FROM votes';

    $result = $db->query($sql);
    if (!$result) {
        //prevent loop
        if (isset($GLOBALS['createTable'])) {
            die("undefined error");
        }
        $GLOBALS['createTable'] = true;

        //create table?
        require 'createTable.php';
        createTable($db);
        selectAll($db);
        return;
    }

    $arr = array();
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $arr[] = $row;
    }

    return $arr;
}
