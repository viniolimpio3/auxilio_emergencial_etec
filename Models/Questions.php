<?php namespace Model;


use Exception;
use PDO;
use PDOException;

class Questions{
    function __construct(){
        
    }

    function insert($data, $user_id){
        if(!require 'database/connection.php') require 'database/connection.php';
        try{
            
            $query = "INSERT INTO user_questions
            ( user_id, rg, uf_rg, cpf, cep, qt_pc_desktop, qt_pc_notebook, qt_sm_phone, renda_per_capita, qtd_in_house, renda_ind, internet, reason, city, isp_configs, pc_desktop_configs, pc_notebook_configs, sm_phone_configs )
            values( $user_id, ";

            foreach($data as $field => $value){
                end($data);//ponteiro para último índice o array!!
                if(key($data) === $field){
                    $query .= " '$data[$field]' ";
                }else{
                    $query .= " '$data[$field]', ";
                }
            }
            $query .= " );";

            // dd($query, true);
            $c = $connection->prepare($query);

            if($c->execute() && $c->rowCount() > 0){
                return true;
            }else{
                throw new PDOException('Não foi possível inserir um usuário no banco de dados');
                return false;
            }

        }catch(Exception $e){
            throw new Exception($e->getMessage());
            return false;
        }
    }

    function get($userID){
        if(!require 'database/connection.php') require 'database/connection.php';
        try{
            
            $query = "SELECT * from user_questions where user_id=$userID";

            $c = $connection->prepare($query);
            if($c->execute() && $c->rowCount() > 0){
                while($row = $c->fetch(PDO::FETCH_OBJ)) return $row;
            
            }else{
                throw new PDOException('Não foi possível inserir um usuário no banco de dados');
                return false;
            }

        }catch(Exception $e){
            throw new Exception($e->getMessage());
            return false;
        }
    }
    function delete($userID){
        if(!require 'database/connection.php') require 'database/connection.php';
        try{
            $query = "DELETE from user_questions where user_id=$userID";
            $c = $connection->prepare($query);
            if($c->execute() && $c->rowCount() > 0)
                return true;
            else
                return false;


        }catch(Exception $e){
            throw new Exception($e->getMessage());
            return false;
        }
    }

    function issetUserID($uID){
        if(!require 'database/connection.php') require 'database/connection.php';
        try{
            $query = "SELECT user_id from user_questions where user_id=$uID";
            
            $c = $connection->prepare($query);
            if($c->execute() && $c->rowCount() > 0)
                while($row = $c->fetch(PDO::FETCH_OBJ)) $this->delete($uID);
            else
                return false;


        }catch(Exception $e){
            throw new Exception($e->getMessage());
            return false;
        }
    }

    function update($userID, $data){
        if(!require 'database/connection.php') require 'database/connection.php';
        try{
            $query = "UPDATE user_questions SET ";
            foreach($data as $field => $value){
                if(end($data) === $value){
                    $query .= "$field = '".$data[$field]."' ";
                }else{
                    $query .= "$field = '".$data[$field]."', ";
                }
            }
            $query .= "WHERE user_id = $userID ";

            
            $c = $connection->prepare($query);


            if($c->execute() and $c->rowCount() > 0)
                return true;
            else
                return false;
            

        }catch(Exception $e){
            throw new Exception($e->getMessage());
            return false;
        }
    }
}






?>