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
        <link rel="stylesheet" href="assets/css/main.css">
    </head>
    <script>    
        //RETIRAR QUERIES da url QUANDO DER UM REFRESH
        if(typeof window.history.pushState == 'function') {
            window.history.pushState({}, "Hide", '<?php echo $_SERVER['PHP_SELF'];?>');
        }

    </script>

    <script src="assets/js/main.js?v=1"></script>

    <body>
        <?php
            require_once __DIR__  . '/vendor/autoload.php';

            if(!isset($_SESSION)) session_start();

            use Model\User;
                        
            $acceptedInputs = array(
                'user_rm' => 'RM',
                'user_name' => 'Nome',
                'user_mail' => 'Email',
                'user_school' => 'Escola',
                'pass' => 'Senha'
            );

            if(isset($_REQUEST['send']) and $_REQUEST['send'] === 'ok' ){

                $input_values = validate($acceptedInputs, 'cadastro.php');

                $hashedPass = sha1($input_values['pass']);
                print_r($input_values);
                $user = new User($input_values['user_name'], $input_values['user_mail'], $hashedPass, $input_values['user_school'],$input_values['user_rm']);                
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

                        <label for="user_school">Escola:</label>
                        <input autocomplete="off" type="text" name="user_school" id="user_school" class="form-control">

                        <label for="pass">Senha:</label>
                        <input autocomplete="off" type="password" name="pass" id="pass" class="form-control">

                        <button type="submit" class="btn mt-3 btn-dark">Enviar</button>
                    </form>
                    
                    <?php 
                        require_once 'includes/handler.php';
                        err(4000);
                        success('login.php');
                    ?>
                    <br>
                    <a href="login.php">Possui uma conta? Faça o Login!</a>                    
                </div>
            </div>
        <?php }//fim else!!
        ?>
    </body>
</html>