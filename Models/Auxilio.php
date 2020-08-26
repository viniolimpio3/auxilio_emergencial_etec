<?php namespace Model;

use Error;
use PDO;
use PDOException;


class Auxilio{
    function __construct($userId=''){
        $this->userID = $userId;
        $this->auxID = '';
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
    public function get($auxID){

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
}

?>