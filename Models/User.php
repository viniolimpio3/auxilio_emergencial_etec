<?php namespace Model;


use Exception;
use PDO;
use PDOException;

class User{

    function __construct($name="", $mail="", $pass="" , $school="", $rm=""){
        $this->userName = $name;
        $this->userMail = $mail;
        $this->userPass = $pass;
        $this->school = $school;

        $this->rm = $rm;
        $this->id = 0;

        $this->userDefaultInputs = array(
            'id',
            'name',
            'email',
            'city',
            'state',
            'school',
            'rm',
            'senha',
            'forgot_pass',
            'vf_code',
            'vf_code_created_at',
            'has_bank_account',
            'answered_questions',
            'link_photo'
        );
    }

    function insert(){
        if(!require 'database/connection.php') require 'database/connection.php';
        try{
            $query = "INSERT INTO user ( name, email, school, rm, senha)values(
                '". $this->userName ."', 
                '". $this->userMail ."', 
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

    function get($filtros){
        if(!is_array($filtros)) return false;

        if(!require 'database/connection.php') require 'database/connection.php';


        try{
            $query = "SELECT * from user WHERE 1 = 1 ";

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

    public function login($user_login, $pass, $loginType){
        if(!require 'database/connection.php') require 'database/connection.php';

        if(!isset($user_login) || !isset($pass)) return false;
        try{

            $query = "SELECT * from user WHERE $loginType = '$user_login' AND senha = '$pass' LIMIT 1";
            $c = $connection->prepare($query);
            if($c->execute() && $c->rowCount() > 0){

                while($row = $c->fetch(PDO::FETCH_OBJ)) return (Object) $row;
                
            }else{
                return false;
            }
        
        }catch(Exception $e){
            echo 'deu erro';

            throw new PDOException($e->getMessage());
            return false;
        }
    }    
    public function update($filtros, $data){
        
        if(!is_array($filtros)) return false;
        if(!is_array($data)) return false;

        if(!require 'database/connection.php') require 'database/connection.php';
        try{
            $query = "UPDATE user SET ";
            

            foreach($data as $field => $value){
                if(end($data) === $value){
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
            print_r($query);

            $c = $connection->prepare($query);

            if($c->execute() and $c->rowCount() > 0){
                return true;
            }else{
                return false;
            }

        }catch(PDOException $e){
            echo 'erro';
            print_r($e);
            throw new PDOException($e->getMessage());
            return false;
        }
    }

    public function getTimestampDiff($timeToCompare){
        if(!require 'database/connection.php') require 'database/connection.php';

        $now = nowMysqlFormat();
        $q = "SELECT timestampdiff(SECOND, '$timeToCompare', '$now') as timediff";
        $c = $connection->prepare($q);
        if($c->execute() and $c->rowCount() > 0){
            while($row = $c->fetch(PDO::FETCH_OBJ)) return $row->timediff;
        }else{
            return false;
        }
    }

}