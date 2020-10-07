<?php
$env = base_dir().'env.php';
require_once $env;
try{
    $connection = new PDO("mysql:host=localhost; dbname=". DB, DB_USER, DB_PASS );
    $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $connection->exec('set names utf8');
}catch(Exception $e){
    echo("Exceção no banco de dados: {$e->getMessage()}");
}
?>