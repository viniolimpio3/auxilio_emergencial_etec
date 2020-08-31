<?php namespace Model;

use Exception;
use PDO;
use PDOException;

class User{

    function __construct($name="", $pass="" ,$city="", $state="", $school="", $rm=""){
        $this->userName = $name;
        $this->userPass = $pass;
        $this->city = $city;
        $this->state = $state;
        $this->school = $school;
        $this->rm = $rm;
        $this->id = 0;
    }

    function insert(){
        if(!require 'database/connection.php') require 'database/connection.php';
        
        try{
            $query = "INSERT INTO user ( name, city, state, school, rm, senha)values(
                '". $this->userName ."', 
                '". $this->city."', 
                '". $this->state."',
                '". $this->school ."', 
                '". $this->rm ."',
                '". $this->userPass ."'
            )";
            
            $c = $connection->prepare($query);

            if($c->execute() && $c->rowCount() > 0){
                $this->id = $connection->lastInsertId();
                return $this->id;
            }else{
                throw new PDOException('Não foi possível inserir um usuário no banco de dados');
                return false;
            }

        }catch(Exception $e){
            throw new Exception($e->getMessage());
            return false;
        }
    }

    function get($id){
        if(!require 'database/connection.php') require 'database/connection.php';

        if(!isset($id)) return false;
        try{
            $query = "SELECT * from user WHERE id = $id LIMIT 1";
            $c = $connection->prepare($query);
            if($c->execute() && $c->rowCount() > 0){

                while($row = $c->fetch(PDO::FETCH_OBJ)){
                    $id = $row->id;
                    $name = $row->name;
                    $city = $row->city;
                    $state = $row->state;
                    $school = $row->school;
                    $rm = $row->rm;
                    
                    return (Object) array(
                        'id' => $id,
                        'name' => $name,
                        'city' => $city,
                        'state' => $state,
                        'school' => $school,
                        'rm' => $rm
                    );
                }

            }else{
                
                throw new PDOException('Não foi possível Buscar um usuário no banco de dados');
                return false;
            }

        }catch(Exception $e){
            echo 'deu erro';

            throw new PDOException($e->getMessage());
            return false;
        }
    }

    public function login($user_rm, $pass){
        if(!require 'database/connection.php') require 'database/connection.php';

        if(!isset($user_rm) || !isset($pass)) return false;
        try{
            $query = "SELECT * from user WHERE rm = '$user_rm' AND senha = '$pass' LIMIT 1";
            $c = $connection->prepare($query);
            if($c->execute() && $c->rowCount() > 0){

                while($row = $c->fetch(PDO::FETCH_OBJ)){
                    $id = $row->id;
                    $name = $row->name;
                    $city = $row->city;
                    $state = $row->state;
                    $school = $row->school;
                    $rm = $row->rm;
                    
                    return (Object) array(
                        'id' => $id,
                        'name' => $name,
                        'city' => $city,
                        'state' => $state,
                        'school' => $school,
                        'rm' => $rm
                    );
                }
            }else{
                return false;
            }
        
        }catch(Exception $e){
            echo 'deu erro';

            throw new PDOException($e->getMessage());
            return false;
        }
    }    

}
?>