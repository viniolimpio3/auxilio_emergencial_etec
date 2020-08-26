<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cadastro Auxílio Emergencial Estudantil</title>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

    </head>
    <script>    
        //RETIRAR QUERIES da url QUANDO DER UM REFRESH
        if(typeof window.history.pushState == 'function') {
            window.history.pushState({}, "Hide", '<?php echo $_SERVER['PHP_SELF'];?>');
        }
    </script>


    <body>
        <?php
            if(!require 'isAuthorized.php') header('location:login.php');

            require 'Models/Auxilio.php';
            require 'Models/User.php';
            use Model\User;
            use Model\Auxilio;
            
            $acceptedInputs = array(
                'name' => 'Nome',
                'city' => 'Cidade',
                'state' => 'Estado',
                'school' => 'Escola',
                'rm' => 'RM'
            );
            $input_values = array();

            if(isset($_REQUEST['send']) and $_REQUEST['send'] === 'yes' ){

                foreach($acceptedInputs as $key => $input){
                    if( !isset($_POST[$key]) or $_POST[$key] === '') {
                        $err =  "O campo $input é obrigatório!";
                        header("location: index.php?err=$err");
                        exit;
                    }else{
                        $input_values[$key] = $_POST[$key];
                    }
                }         

                $user = new User($input_values['name'], $input_values['city'], $input_values['state'], $input_values['school'], $input_values['rm']);
                $aux = new Auxilio();
                
                $userID = $user->insertAll();
                if($userID){
                    $inseriuAux = $aux->insert($userID);
                    if(!$inseriuAux) return new Error('Não possível cadastrar o usuário. Tente novamente mais tarde');

                    $successMessage = "Parabéns " . $input_values['name'] . ", você foi cadastrado com sucesso.";
                    header("location: index.php?success=$successMessage");

                }else{
                    throw new Error('Não possível cadastrar o usuário. Tente novamente mais tarde');
                }
            }else{

        ?>
            <div class="container mt-5">
                <h3>Cadastro Auxílio Emergencial <small>Alunos do Ensino médio</small></h3>
                <div class="jumbotron">
                    <form action="index.php?send=yes" method="POST" >
                        <label for="name">Nome:</label>
                        <input  type="text" name="name" id="name" class="form-control">

                        <label for="city">Cidade:</label>
                        <input  type="text" name="city" id="city" class="form-control">

                        <label for="state">Estado</label>
                        <input  type="text" name="state" id="state" class="form-control">

                        <label for="school">Escola:</label>
                        <input  type="text" name="school" id="school" class="form-control">

                        <label for="rm">RM:</label>
                        <input  type="text" name="rm" maxlength="6" id="rm" class="form-control">

                        <button type="submit" class="btn mt-3 btn-dark">Cadastrar</button>

                    </form>
                    
                                        
                    <?php if(isset($_GET['err'])): ?>
                        <div class="mt-4 alert-danger alert-dismissible alert fade show" role="alert">
                            <?=$_GET['err']?>
                        </div>
                    <?php endif ?>

                    <?php if(isset($_GET['success'])): ?>
                        <div class="mt-4 alert-success alert-dismissible alert fade show" role="alert">
                            <?=$_GET['success']?>
                        </div>
                    <?php endif ?>


                    <div class="mt-4 alert-warning alert">
                        É necessário estar logado para continuar.
                    </div>
                         
                </div>
            </div>
        <?php }//fim else!!
        ?>

    </body>
</html>