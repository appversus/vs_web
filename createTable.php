<?php

function createTable($db) {
    $createTableSQL = 'CREATE TABLE votes ( id INTEGER PRIMARY KEY AUTOINCREMENT, cn varchar (255), v1 int(11), v2 int(11), UNIQUE (cn) )';
    try {
        $db->exec($createTableSQL);
    } catch (Esception $e) {
        die($db->error . " in $createTableSQL");
    }

    $ignore = array("US", "RU");
    
    require 'jvmCountries.php';
    $random = "INSERT INTO votes (cn, v1, v2) VALUES ";
    for ($i = 0; $i < count($jvmCountries); $i++) {
        $country = $jvmCountries[$i];
        if(in_array($country, $ignore)){
            continue;
        }
        
        $random .= "('$country', " . rand(5, 8) . ", " . rand(5, 8) . ")";        
        if (count($jvmCountries) > $i + 1) {
            $random .= ",";
        }
    }

    $random .= ", ('US', 8, 2), ('RU', 3, 7)";
    //on duplicate key insert not work in sqlite!
    
    echo $random;

    //some random
    $db->exec($random);
}
