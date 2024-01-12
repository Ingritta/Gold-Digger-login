<?php

$config = require_once 'connect.php';

try{
//tablica asocjacyjna
    $db = new PDO(
        "mysql:host={$config['host']};dbname={$config['database']};charset=utf8",
        $config['user'], //tablica config, szyfladka user
        $config['password'],
        [PDO::ATTR_EMULATE_PREPARES => false, //wyłącznie emulacji 
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

} catch (PDOEXCEPTION $error) {

    echo $error; //dla developera
    exit('Database error');

}

