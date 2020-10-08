<?php namespace Model;

if(!require dirname(__DIR__) . '/vendor/autoload.php' ) require dirname(__DIR__) . '/vendor/autoload.php';

use Exception;
use PDO;
use PDOException;

use Connection;

class Admin extends Connection{
    protected $table;
    
    function __construct(){
        parent::__construct();        
        parent::connect();

        $this->con = $this->connection;

        $this->table = 'admin';
    }

    function listUsers(){
        try{
            $q = "SELECT a.status, a.comments, u.name, u.rm, u.school, 
            case 
                WHEN u.link_photo != '' then u.link_photo
            end as 'link_photo'
            from `pwe3`.`user` as u INNER JOIN `pwe3`.`aux_em` as a on u.id = a.user_id";
            
            $c = $this->con->prepare($q);
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
        
        try{
            $q = "SELECT * FROM admin WHERE mail='$mail' and pass='$pass' LIMIT 1";

            $c = $this->con->prepare($q);

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
       
        try{
            $query = "SELECT * from {$this->table} WHERE 1 = 1 ";

            foreach($filtros as $field){
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

    public function update($filtros, $data){   
        if(!is_array($filtros)) return false;
        if(!is_array($data)) return false;

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

            $c = $this->con->prepare($query);

            return $c->execute() and $c->rowCount() > 0 ? true : false;

        }catch(PDOException $e){
            echo 'erro';
            print_r($e);
            throw new PDOException($e->getMessage());
            return false;
        }
    }

}