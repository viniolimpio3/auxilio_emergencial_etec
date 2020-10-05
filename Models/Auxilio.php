<?php namespace Model;

use Error;
use Exception;
use PDO;
use PDOException;


class Auxilio{
    function __construct($userId=''){
        $this->userID = $userId;
        $this->auxID = '';
    }

    public function get($filterItems){
        if(!require 'database/connection.php') require 'database/connection.php';
        try{
            $query = "SELECT * from aux_em where 1=1 ";

            foreach($filterItems as $field => $value){
                $query .= " AND $field=$value ";
            }

            $c = $connection->prepare($query);
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
        if(!require 'database/connection.php') require 'database/connection.php';
        try{
            $query = "insert into aux_em(user_id)values('$userID')";
            $c = $connection->prepare($query);

            if($c->execute() and $c->rowCount() > 0){
                $this->auxID = $connection->lastInsertId();
                return $this->auxID;
            }else{
                throw new Error('Erro ao cadastrar Auxílio Emergencial');
            }

        }catch(PDOException $e){
            throw new PDOException($e->getMessage());
        }
    }

    public function exists($userID){
        if(!require 'database/connection.php') require 'database/connection.php';
        try{
            $query = "select id from aux_em where user_id = $userID LIMIT 1";
            $c = $connection->prepare($query);
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
        if(!require 'database/connection.php') require 'database/connection.php';
        try{    
            $q = "UPDATE aux_em set ";
            foreach($data as $field => $value){
                end($data);
                if(key($data) === $field){
                    $q .= "$field = '".$data[$field]."' ";
                }else{
                    $q .= "$field = '".$data[$field]."', ";
                }
            }
            
            $q .= " WHERE 1 = 1 ";
            foreach($filterItems as $field => $value){
                $q .= " AND " . $field . " = '$value' ";
            }

            if($c = $connection->prepare($q) and $c->execute() and $c->rowCount() > 0) return true;
            return false;

        }catch(Exception $e){
            throw new Exception($e->getMessage());
            return false;
        }
    }
}

?>