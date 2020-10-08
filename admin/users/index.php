<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ADMIN - Cadastro Auxílio Emergencial Estudantil</title>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <link rel="stylesheet" href="../../assets/css/admin.css">
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

    <script src="../../assets/js/main.js?v=1"></script>
    <body>
        <?php
            require_once dirname(__DIR__) . '/../vendor/autoload.php';      
            verifyIPtoAdmin();

            isAdmin();


            $max_tentativas_login = 3;

            if(!isset($_SESSION['ad_tentativas_login'])) $_SESSION['ad_tentativas_login'] = 0;

            $page = 'login.php';

            if(!isset($_SESSION)) session_start();

            if($_SESSION['ad_tentativas_login'] === $max_tentativas_login){
                invalidateClient($connection);
                header('location: ../');
            }
                        
            use Model\Admin;
            $ad = new Admin();
    
            $input_values = array();

            $usersList = $ad->listUsers();


            dd($usersList, true);






            
            if(isset($_REQUEST['send']) and $_REQUEST['send'] === 'yes' ){


                
                // $acceptedInputs = array(
                //     "mail" => 'Email',
                //     'pass' => 'Senha'
                // );

                // $input_values = validate($acceptedInputs, "$page");    
        
                // $hashedPass = sha1($input_values['pass']);

                // $admin = $ad->login($input_values['mail'], $hashedPass);
                  
                // if($admin){
                //     $_SESSION['ad_tentativas_login'] = 0;
                //     $successMessage = "Parabéns " . $u->name . ", você logou com sucesso.\nAguarde para ser redirecionado!";
                //     $_SESSION['auth'] = 'admin';
                //     $_SESSION['user'] = $u;
                //     setMessage('success', $successMessage, 'login.php');

                // }else {
                //     $_SESSION['ad_tentativas_login'] += 1;
                //     setMessage('err', 'Email ou Senha Incorreto(s)',"$page");
                // }
                
            }else{
        ?>
            <div class="container mt-5">
                <h3>Painel de Administração Auxílio Emergencial <small>Alunos do Ensino médio</small></h3>
                <div class="jumbotron">

                    
                    <?php //HANDLER 
                        err(3000);
                        success('users/index.php');
                    ?>

                    <br>

                    <?php 
                    ?>
                    <a href="/">Voltar para o site</a>
                </div>
            </div>
        <?php }//fim else!!
        ?>

    </body>
</html>