<?php namespace Model;

if(!require dirname(__DIR__) . '/vendor/autoload.php' ) require dirname(__DIR__) . '/vendor/autoload.php';

use Exception;
use PDO;
use PDOException;
use Connection;

class User extends Connection{

    protected $con;
    function __construct($name="", $mail="", $pass="" , $school="", $rm=""){
        
        parent::__construct();
        parent::connect();

        $this->userName = $name;
        $this->userMail = $mail;
        $this->userPass = $pass;
        $this->school = $school;


        $this->con = $this->connection;

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
        $this->table = 'user';
    }

    function insert(){

        

        try{
            $query = "INSERT INTO {$this->table} ( name, email, school, rm, senha)values(
                '". $this->userName ."', 
                '". $this->userMail ."', 
                '". $this->school ."', 
                '". $this->rm ."',
                '". $this->userPass ."'
            )";
            
            $c = $this->con->prepare($query);

            if($c->execute() && $c->rowCount() > 0){
                $this->id = $this->con->lastInsertId();
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

        try{
            $query = "SELECT * from {$this->table} WHERE 1 = 1 ";

            foreach($this->userDefaultInputs as $field){
                if(isset($filtros[$field])) $query .= "AND $field = '$filtros[$field]' ";
            }

            $query .= "LIMIT 1";

            $c = $this->con->prepare($query);
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


        if(!isset($user_login) || !isset($pass)) return false;
        try{

            $query = "SELECT * from {$this->table} WHERE $loginType = '$user_login' AND senha = '$pass' LIMIT 1";
            $c = $this->con->prepare($query);
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

        try{
            $query = "UPDATE {$this->table} SET ";
            

            foreach($data as $field => $value){
                $type = gettype($value);
                if(is_bool($value) and $value === false) $value = 0;
                end($data); //ponteiroooo
                if(key($data) === $field) $query .= !$type == 'boolean' ? " $field = '".$value."' " : "  $field = ".$value." ";
                else $query .= !$type == 'boolean' ? " $field = '".$value."', " : "  $field = ".$value.", ";
            }
            
            $query .= " WHERE 1=1 ";

            foreach($filtros as $field => $value){                
                if($field === 'id'){
                   $query .= " AND id=$value ";
                }
                else if(isset($filtros[$field])) $query .= " AND $field='" . $filtros[$field] . "' ";               
            }
            
            $c = $this->con->prepare($query);

            return $c->execute() and $c->rowCount() > 0 ? true : false;

        }catch(PDOException $e){
            echo 'erro';
            print_r($e);
            throw new PDOException($e->getMessage());
            return false;
        }
    }

    public function getTimestampDiff($timeToCompare){
        $now = nowMysqlFormat();
        $q = "SELECT timestampdiff(SECOND, '$timeToCompare', '$now') as timediff";
        $c = $this->con->prepare($q);
        if($c->execute() and $c->rowCount() > 0){
            while($row = $c->fetch(PDO::FETCH_OBJ)) return $row->timediff;
        }else{
            return false;
        }
    }

}