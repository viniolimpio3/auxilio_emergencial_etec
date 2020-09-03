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

    <script src="assets/js/main.js"></script>
    <body>
        <?php
            if(!isset($_SESSION)) session_start();
            
            require 'Models/User.php';
            use Model\User;
            
            $input_values = array();
            
            $acceptedInputs = array(
                'user_rm' => 'RM',
                'user_name' => 'Nome',
                'user_mail' => 'Email',
                'user_city' => 'Cidade',
                'user_state' => 'Estado',
                'user_school' => 'Escola',
                'pass' => 'Senha'
            );

            if(isset($_REQUEST['send']) and $_REQUEST['send'] === 'ok' ){

                foreach($acceptedInputs as $key => $input){

                    if( !isset($_POST[$key]) or $_POST[$key] === '') {
                        $err =  urlencode("O campo $input é obrigatório!");
                        header("location: cadastro.php?err=$err");
                        exit;
                    }else{
                        $input_values[$key] = $_POST[$key];
                    }
                }         
                $hashedPass = sha1($input_values['pass']);
                print_r($input_values);
                $user = new User($input_values['user_name'], $input_values['user_mail'], $hashedPass, $input_values['user_city'], $input_values['user_state'], $input_values['user_school'],$input_values['user_rm']);                
                $u = $user->insert();
                if($u){
                    $successMessage = urlencode("Parabéns " . $input_values['user_name'] . ", você foi cadastrado com sucesso.\nAguarde para ser redirecionado!");
                    $_SESSION['auth'] = 'logado';
                    $_SESSION['user'] = $u;
                    header("location: cadastro.php?success=$successMessage");
                }else{
                    $e = urlencode( "Não foi possível concluir o cadastro :/");
                    header("location: cadastro.php?err=$e");
                }
            }else{
        ?>
            <div class="container mt-5">
                <h3>Cadastro Auxílio Emergencial <small>Alunos do Ensino médio</small></h3>
                <div class="jumbotron">
                    <form action="cadastro.php?send=ok" method="POST" >
                        <label for="user_rm">RM:</label>
                        <input onkeypress="return onlyNumber()" maxlength="6" autocomplete="off" type="text" name="user_rm" id="user_rm" class="form-control">

                        <label for="user_name">Nome:</label>
                        <input autocomplete="off" type="text" name="user_name" id="user_name" class="form-control">

                        <label for="user_mail">Email:</label>
                        <input autocomplete="off" type="text" name="user_mail" id="user_mail" class="form-control">

                        <label for="user_city">Cidade:</label>
                        <input autocomplete="off" type="text" name="user_city" id="user_city" class="form-control">

                        <label for="user_state">Estado:</label>
                        <input autocomplete="off" type="text" name="user_state" id="user_state" class="form-control">

                        <label for="user_school">Escola:</label>
                        <input autocomplete="off" type="text" name="user_school" id="user_school" class="form-control">

                        <label for="pass">Senha:</label>
                        <input autocomplete="off" type="password" name="pass" id="pass" class="form-control">

                        <button type="submit" class="btn mt-3 btn-dark">Enviar</button>
                    </form>
                    
                    <?php if(isset($_GET['err'])): ?>
                        <div class="mt-4 alert-danger alert fade show" role="alert">
                            <?=$_GET['err']?>
                        </div>
                    <?php endif ?>

                    <?php if(isset($_GET['success'])): ?>

                        <div class="mt-4 alert-success alert fade show" role="alert">
                            <?= urldecode( $_GET['success']); ?>
                        </div>

                        <script type="text/javascript">
                            setTimeout(() =>{
                                window.location = 'login.php';
                            }, 3000)
                        </script>
                    <?php endif ?>

                    <a href="login.php">Possui uma conta? Entre com seu RM!</a>                    
                </div>
            </div>
        <?php }//fim else!!
        ?>

    </body>
</html>