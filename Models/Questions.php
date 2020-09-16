<?php namespace Model;


use Exception;
use PDO;
use PDOException;

class Questions{
    function __construct(){
        
    }
    public function getPhotoUrl($userID){
        if(!require 'database/connection.php') require 'database/connection.php';
        try{
            $query = "SELECT * FROM user_questions WHERE user_id = '$userID'";

            $c = $connection->prepare($query);

            if($c->execute() && $c->rowCount() > 0){
                while($row = $c->fetch(PDO::FETCH_OBJ)) return $row->link_photo;                
            }else{
                throw new PDOException('Não foi possível inserir um usuário no banco de dados');
                return false;
            }

        }catch(Exception $e){
            throw new Exception($e->getMessage());
            return false;
        }
    }

    function insert($data, $user_id){
        if(!require 'database/connection.php') require 'database/connection.php';
        try{
            
            $query = "INSERT INTO user_questions
            ( user_id, rg, uf_rg, cpf, cep, qt_pc_desktop, qt_pc_notebook, qt_sm_phone, renda_per_capita, qtd_in_house, renda_ind, internet, reason, isp_configs, pc_desktop_configs, pc_notebook_configs, sm_phone_configs )
            values( $user_id, ";

            foreach($data as $field => $value){
                if(end($data) === $value){
                    $query .= " '$data[$field]' ";
                }else{
                    $query .= " '$data[$field]', ";
                }
            }
            $query .= " );";

            dd($query, true);
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
}






?>