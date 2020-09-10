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
            // if(require './isAuthorized.php') header('location: painel.php');
            function dd($v, $exit=false){
                echo '<pre>';
                print_r($v);
                echo '</pre>';
                if($exit) exit();
            }
            if(!isset($_SESSION)) session_start();
    
            if(!require './Models/User.php') require './Models/User.php';
            use Model\User;
    
            $input_values = array();
            
            if(isset($_REQUEST['send']) and $_REQUEST['send'] === 'yes' ){
                $lType = $_POST['login_type'];
                $acceptedInputs = array(
                    "{$lType}_input" => 'Login',
                    'pass' => 'Senha'
                );


                foreach($acceptedInputs as $key => $input){

                    if( !isset($_POST[$key]) or $_POST[$key] === '') {
                        $err =  "O campo $input é obrigatório!";
                        header("location: login.php?err=$err");
                        exit;
                    }else{
                        $input_values[$key] = $_POST[$key];
                    }
                }         
        
                $hashedPass = sha1($input_values['pass']);

                $user = new User();
                  
                $u = $user->login($input_values["{$lType}_input"], $hashedPass, $lType);
                

                if($u){
                    $successMessage = urlencode("Parabéns " . $u->name . ", você logou com sucesso.\nAguarde para ser redirecionado!");
                    $_SESSION['auth'] = 'logado';
                    $_SESSION['user'] = $u;
                    header("location: login.php?success=$successMessage");
                }else{
                    $e = "RM ou Senha Incorreto(s)";
                    header("location: login.php?err=$e");
                }
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
                    
                    <?php if(isset($_GET['err'])): ?>
                        <div class="mt-4 alert-danger alert-dismissible alert fade show" role="alert">
                            <?=$_GET['err']?>
                        </div>
                    <?php endif ?>

                    <?php if(isset($_GET['success'])): ?>

                        <div class="mt-4 alert-success alert-dismissible alert fade show" role="alert">
                            <?= urldecode( $_GET['success']); ?>
                        </div>

                       <script type="text/javascript">
                            setTimeout(() =>{
                                window.location = 'painel.php';
                            }, 3000)
                        </script>
                    <?php endif ?>
                    <br>
                    <a href="cadastro.php">Novo por aqui? Cadastre se aqui!</a>
                    <br> <br>   
                    <a href="forgotPass.php">Esqueceu a senha?</a>
                    
                </div>
            </div>
        <?php }//fim else!!
        ?>

        <script>
            function hide(elements){
                elements.forEach( element =>{
                    element.setAttribute('hidden','')
                } )
            }
            function show(elements){
                elements.forEach(element =>{
                    element.removeAttribute('hidden')
                })
            }

            const rmLb = document.getElementById('rm_lb')
            const rmInput = document.getElementById('rm_input')

            const emailLb = document.getElementById('email_lb')
            const emailInput = document.getElementById('email_input')

            const btnMail = document.getElementById('btn_email')
            const btnRm = document.getElementById('btn_rm')

            let inputType = document.getElementById('login_type')
            

            btnMail.onclick = function(){
                hide([rmLb, rmInput, btnMail])
                show([emailLb, emailInput, btnRm])

                inputType.value = 'email'
            }

            btnRm.onclick = function(){
                show([rmLb, rmInput, btnMail])
                hide([emailLb, emailInput, btnRm])

                inputType.value = 'rm'
            }

        </script>

    </body>
</html>