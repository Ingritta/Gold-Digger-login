<?php

$connect = require_once 'connect.php';

try{
//tablica asocjacyjna
    $db = new PDO(
        "mysql:host={$host};dbname={$db_name};charset=utf8",
        $db_user, //tablica config, szyfladka user
        $db_password,
        [PDO::ATTR_EMULATE_PREPARES => false, //wyłącznie emulacji 
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

} catch (PDOEXCEPTION $error) {

    echo $error; //dla developera
    exit('Database error');

}

