<?php namespace Model;

if(!require dirname(__DIR__) . '/vendor/autoload.php' ) require dirname(__DIR__) . '/vendor/autoload.php';

use Connection;
use Error;
use Exception;
use PDO;
use PDOException;


class Auxilio extends Connection{
    function __construct($userId=''){
        parent::__construct();

        parent::connect();
        $this->con = $this->connection;
        $this->con;
        $this->userID = $userId;
        $this->auxID = '';
    }

    public function get($filterItems){
        
        try{
            $query = "SELECT * from aux_em where 1=1 ";

            foreach($filterItems as $field => $value){
                $query .= " AND $field=$value ";
            }

            $c = $this->con->prepare($query);
            if($c->execute() and $c->rowCount()>0){
                while($r = $c->fetch(PDO::FETCH_OBJ)) return $r;
            }else{
                return false;
            }
        }catch(PDOException $e){
            throw new PDOException($e->getMessage());
        }
    }

    public function insert($userID){
        
        try{
            $query = "insert into aux_em(user_id)values('$userID')";
            $c = $this->con->prepare($query);

            if($c->execute() and $c->rowCount() > 0){
                $this->auxID = $this->con->lastInsertId();
                return $this->auxID;
            }else{
                throw new Error('Erro ao cadastrar Auxílio Emergencial');
            }

        }catch(PDOException $e){
            throw new PDOException($e->getMessage());
        }
    }

    public function exists($userID){
        
        try{
            $query = "select id from aux_em where user_id = $userID LIMIT 1";
            $c = $this->con->prepare($query);
            if($c->execute() and $c->rowCount()>0){
                while($r = $c->fetch(PDO::FETCH_OBJ)){
                    return $r->id;
                }
            }else{
                return false;
            }
        }catch(PDOException $e){
            throw new PDOException($e->getMessage());
        }
    }

    public function update($filterItems, $data){
        
        try{    
            $q = "UPDATE aux_em set";
            foreach($data as $field => $value){
                $type = gettype($value);
                if(is_bool($value) and $value === false) $value = 0;

                end($data); //ponteiroooo

                if(key($data) === $field) {
                    if($type != 'boolean') $q .= " $field = '".$value."' ";
                    else $q .= "  $field = ".$value." ";
                }
                else {
                    if($type != 'boolean') $q .= " $field = '".$value."', ";
                    else $q .= "  $field = ".$value.", ";
                }
            }
            
            
            $q .= " WHERE 1 = 1 ";
            foreach($filterItems as $field => $value){
                $q .= " AND " . $field . " = '$value' ";
            }

            if($c = $this->con->prepare($q) and $c->execute() and $c->rowCount() > 0) return true;
            return false;

        }catch(Exception $e){
            throw new Exception($e->getMessage());
            return false;
        }
    }
}

?>