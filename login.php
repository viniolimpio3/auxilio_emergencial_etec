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

         <!-- Font-Aweasome -->
         <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
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
            use Model\Questions;
            $q_model = new Questions();
    
            $input_values = array();
            
            if(isset($_REQUEST['send']) and $_REQUEST['send'] === 'yes' ){
                
                $lType = $_POST['login_type'];
                $acceptedInputs = array(
                    "{$lType}_input" => 'Login',
                    'pass' => 'Senha'
                );

                $input_values = validate($acceptedInputs, 'login.php');    
        
                $hashedPass = sha1($input_values['pass']);

                $user = new User();
                  
                $u = $user->login($input_values["{$lType}_input"], $hashedPass, $lType);

                if($u){
                    $successMessage = "Parabéns " . $u->name . ", você logou com sucesso.\nAguarde para ser redirecionado!";
                    $_SESSION['auth'] = 'logado';
                    $_SESSION['user'] = $u;
                    setMessage('success',$successMessage, 'login.php');

                }else setMessage('err', 'RM ou Senha Incorreto(s)','login.php');
                
            }else{
        ?>
            <div class="container mt-5">
                <h3>Cadastro Auxílio Emergencial <small>Alunos do Ensino médio</small></h3>
                <div class="jumbotron">
                    <form action="login.php?send=yes" method="POST" >

                        <label id="rm_lb" for="user_rm">RM:</label>
                        <input id="rm_input" name="rm_input" onkeypress="return onlyNumber()" maxlength="6" autocomplete="off" type="text" name="user_rm" id="user_rm" class="form-control">

                        <label hidden id="email_lb" for="user_mail">Email:</label>
                        <input hidden id="email_input" name="email_input" autocomplete="off" type="text" name="user_mail" id="user_mail" class="form-control">

                        <input name="login_type" id="login_type" value="rm" type="hidden">  

                        <label for="pass">Senha:</label>
                        <input autocomplete="off" type="password" name="pass" id="pass" class="form-control">

                        <button type="submit" class="btn mt-3 mr-3 btn-dark">Enviar</button> 
                        <button type="button" id="btn_email" class="btn mt-3  btn-light">Logar com Email</button> 
                        <button type="button" id="btn_rm" hidden class="btn mt-3 btn-light">Logar com RM</button> 

                    </form>
                    
                    <?php //HANDLER 
                        err(3000);
                        success('painel.php');
                    ?>

                    <br>
                    <a href="cadastro.php">Novo por aqui? Cadastre se aqui!</a>
                    <br> <br>   
                    <a href="forgotPass.php">Esqueceu a senha?</a>
                    
                </div>
            </div>
        <?php }//fim else!!
        ?>

        <script>
            const inputType = id('login_type')
            const btnMail = id('btn_email')
            btnMail.onclick = function(){
                hide([ id('rm_lb'), id('rm_input'),id('btn_email') ])
                show([ id('email_lb'), id('email_input'), id('btn_rm') ])

                inputType.value = 'email'
            }
            const btnRm = id('btn_rm')
            btnRm.onclick = function(){
                show([id('rm_lb'), id('rm_input'), id('btn_email')])
                hide([id('email_lb'),id('email_input'), id('btn_rm')])
                inputType.value = 'rm'
            }

        </script>

    </body>
</html>