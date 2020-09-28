<?php namespace Model;

use Exception;
use PDO;
use PDOException;

class Admin{

    function __construct(){
        
    }

    function listUsers(){
        if(!require 'database/connection.php') require 'database/connection.php';
        
        try{
            $q = "SELECT * FROM users";
            $c = $connection->prepare($q);
            if($c->execute() and $c->rowCount() > 0){
                $users = array();
                while($r = $c->fetch(PDO::FETCH_OBJ)) array_push($users, (Object) $r);
                if(count($users) > 0) return $users;
            }else{
                throw new Exception("Não encontrado!");
            }

        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    function login($mail, $pass){
        if(!require '../database/connection.php') require '../database/connection.php';
        try{
            $q = "SELECT * FROM admin WHERE mail='$mail' and pass='$pass' LIMIT 1";

            
            $c = $connection->prepare($q);
            if($c->execute() and $c->rowCount() > 0)
                while($row = $c->fetch(PDO::FETCH_OBJ)) return (Object) $row;
            else
                return false;
            
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    function get($filtros){
        if(!is_array($filtros)) return false;

        if(!require 'database/connection.php') require 'database/connection.php';


        try{
            $query = "SELECT * from {$this->table} WHERE 1 = 1 ";

            foreach($this->userDefaultInputs as $field){
                if(isset($filtros[$field])) $query .= "AND $field = '$filtros[$field]' ";
            }

            $query .= "LIMIT 1";

            $c = $connection->prepare($query);
            if($c->execute() && $c->rowCount() > 0)
                while($row = $c->fetch(PDO::FETCH_OBJ)) return (Object) $row;
            else
                return false;
            

        }catch(Exception $e){
            throw new PDOException($e->getMessage());
            return false;
        }
    }

    public function update($filtros, $data){   
        if(!is_array($filtros)) return false;
        if(!is_array($data)) return false;

        if(!require 'database/connection.php') require 'database/connection.php';
        try{
            $query = "UPDATE {$this->table} SET ";
            

            foreach($data as $field => $value){
                end($data);
                if(key($data) === $field){
                    $query .= "$field = '".$data[$field]."' ";
                }else{
                    $query .= "$field = '".$data[$field]."', ";
                }
            }
            
            $query .= " WHERE 1=1 ";

            foreach($this->userDefaultInputs as $field){
                if($field == 'id'){
                    if(isset($filtros['id'])) $query .= " AND id = '".$filtros['id']."' ";
                } else{
                    if(isset($filtros[$field])) $query .= " AND $field='" . $filtros[$field] . "' ";
                }
            }

            $c = $connection->prepare($query);

            return $c->execute() and $c->rowCount() > 0 ? true : false;

        }catch(PDOException $e){
            echo 'erro';
            print_r($e);
            throw new PDOException($e->getMessage());
            return false;
        }
    }

}