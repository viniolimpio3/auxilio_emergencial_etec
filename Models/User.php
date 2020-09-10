<?php namespace Model{


    use Exception;
    use PDO;
    use PDOException;

    class User{

        function __construct($name="", $mail="", $pass="" ,$city="", $state="", $school="", $rm=""){
            $this->userName = $name;
            $this->userMail = $mail;
            $this->userPass = $pass;
            $this->city = $city;
            $this->state = $state;
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
                'forgot_pass'
            );
        }

        function insert(){
            if(!require 'database/connection.php') require 'database/connection.php';
            try{
                $query = "INSERT INTO user ( name, email, city, state, school, rm, senha)values(
                    '". $this->userName ."', 
                    '". $this->userMail ."', 
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

        function get($filtros){
            if(!is_array($filtros)) return false;

            if(!require 'database/connection.php') require 'database/connection.php';


            try{
                $query = "SELECT * from user WHERE 1 = 1 ";

                if(isset($filtros['id'])) $query .= "AND id = '". $filtros['id'] ."' ";
                if(isset($filtros['email'])) $query .= "AND email= '".$filtros['email']."' ";
                if(isset($filtros['city'])) $query .= "AND city= '".$filtros['city']."' ";
                if(isset($filtros['state'])) $query .= "AND state= '".$filtros['state']."' ";
                if(isset($filtros['school'])) $query .= "AND school= '".$filtros['school']."' ";
                if(isset($filtros['rm'])) $query .= "AND rm= '".$filtros['rm']."' ";
                if(isset($filtros['url_hash'])) $query .= "AND url_hash= '".$filtros['url_hash']."' ";


                $query .= "LIMIT 1";

                $c = $connection->prepare($query);
                if($c->execute() && $c->rowCount() > 0){

                    while($row = $c->fetch(PDO::FETCH_OBJ)){
                        $id = $row->id;
                        $name = $row->name;
                        $mail = $row->email;
                        $city = $row->city;
                        $state = $row->state;
                        $school = $row->school;
                        $rm = $row->rm;
                        $forgetPass = $row->forgot_pass;

                        
                        return (Object) array(
                            'id' => $id,
                            'name' => $name,
                            'email'=> $mail,
                            'city' => $city,
                            'state' => $state,
                            'school' => $school,
                            'rm' => $rm,
                            'forgot_pass' => $forgetPass
                        );
                    }

                }else{
                    
                    // throw new PDOException('Não foi possível Buscar um usuário no banco de dados');
                    return false;
                }

            }catch(Exception $e){
                echo 'deu erro';

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

                    while($row = $c->fetch(PDO::FETCH_OBJ)){
                        $id = $row->id;
                        $name = $row->name;
                        $mail = $row->email;
                        $city = $row->city;
                        $state = $row->state;
                        $school = $row->school;
                        $rm = $row->rm;
                        $forgetPass = $row->forgot_pass;
                        
                        return (Object) array(
                            'id' => $id,
                            'name' => $name,
                            'email'=> $mail,
                            'city' => $city,
                            'state' => $state,
                            'school' => $school,
                            'rm' => $rm,
                            'forgot_pass' => $forgetPass
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
        public function update($filtros, $data){
            
            if(!is_array($filtros)) return false;
            if(!is_array($data)) return false;

            if(!require 'database/connection.php') require 'database/connection.php';
            try{
                
                $query = "UPDATE user SET ";
                
                // foreach($this->userDefaultInputs as $index => $field){         
                //     if(isset($data[$field])) $query .= "$field= '".$data[$field]."', ";
                // }

                if(isset($data['email'])) $query .= " email = '".$data['email']."' ";
                if(isset($data['city'])) $query .= " city = '".$data['city']."' ";
                if(isset($data['state'])) $query .= " state = '".$data['state']."' ";
                if(isset($data['school'])) $query .= " school = '".$data['school']."' ";
                if(isset($data['rm'])) $query .= " rm = '".$data['rm']."' ";
                if(isset($data['forgot_pass'])) $query .= " forgot_pass = ".$data['forgot_pass']." , ";
                if(isset($data['url_hash'])) $query .= " url_hash = '".$data['url_hash']."' ";

                
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
    }



}


?>