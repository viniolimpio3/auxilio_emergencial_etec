<?php

if(!require dirname(__DIR__) . '/env.php'); require dirname(__DIR__) . '/env.php';

try{
    $connection = new PDO("mysql:host=localhost; dbname=". DB, DB_USER, DB_PASS );
    $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $connection->exec('set names utf8');
    
}catch(Exception $e){
    echo("Exceção no banco de dados: {$e->getMessage()}");
}
class Connection{
    public $connection;
    public function __construct(){
        $this->DB = DB;
        $this->DB_USER = DB_USER;     
        $this->DB_PASS = DB_PASS;
        
    }

    public function connect(){
        try{
            $this->connection = new PDO("mysql:host=localhost; dbname=". $this->DB, $this->DB_USER, $this->DB_PASS );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->exec('set names utf8');

            return $this->connection;
            
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
}