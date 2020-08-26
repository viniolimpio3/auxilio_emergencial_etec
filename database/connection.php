<?php
require('env.php');
try{
    $connection = new PDO("mysql:host=localhost; dbname=". DB, DB_USER, DB_PASS );
    $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $connection->exec('set names utf8');
}catch(Exception $e){
    echo("Exceção no banco de dados: {$e->getMessage()}");
}
?>