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

            $query = "INSERT INTO user_questions( user_id, rg, uf_rg, cpf, cep, qt_pc_desktop, qt_pc_notebook, qt_sm_phone, renda_per_capita, qtd_in_house, renda_ind, internet, reason, isp_configs, pc_desktop_configs, pc_notebook_configs, sm_phone_configs )
            values('$user_id', 
            '". $data['rg'] ."', 
            '". $data['uf_rg'] ."' , 
            '". $data['cpf'] ."', 
            '". $data['cep'] ."', 
            '". $data['qt_pc_desktop'] ."', 
            '". $data['qt_pc_notebook'] ."',
            '". $data['qt_sm_phone'] ."', 
            '". $data['renda_per_capita'] ."',
            '". $data['qtd_in_house'] ."',
            '". $data['renda_ind'] ."',
            '". $data['internet'] ."',
            '". $data['reason'] ."',
            '". $data['isp_configs'] ."',
            '". $data['pc_desktop_configs'] ."',
            '". $data['pc_notebook_configs'] ."',
            '". $data['sm_phone_configs'] ."')";



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