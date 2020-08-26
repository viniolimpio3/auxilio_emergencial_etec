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
                'pass' => 'Senha'
            );

            if(isset($_REQUEST['send']) and $_REQUEST['send'] === 'yes' ){

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
                  
                $u = $user->login($input_values['user_rm'], $hashedPass);
                
                if($u){
                    $successMessage = urlencode("Parabéns " . $u->name . ", você logou com sucesso.\nAguarde para ser redirecionado!");
                    $_SESSION['auth'] = 'logado';
                    header("location: login.php?success=$successMessage");
                }else{
                    throw new Error('RM ou senha Incorreto(s)');
                }
            }else{
        ?>
            <div class="container mt-5">
                <h3>Cadastro Auxílio Emergencial <small>Alunos do Ensino médio</small></h3>
                <div class="jumbotron">
                    <form action="login.php?send=yes" method="POST" >
                        <label for="user_rm">RM:</label>
                        <input onkeypress="return onlyNumber()" maxlength="6" autocomplete="off" type="text" name="user_rm" id="user_rm" class="form-control">

                        <label for="pass">Senha:</label>
                        <input autocomplete="off" type="password" name="pass" id="pass" class="form-control">

                        <button type="submit" class="btn mt-3 btn-dark">Enviar</button>
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


                    <!-- <div class="mt-4 alert-warning alert">
                        É necessário estar logado para criar um cadastro auxílio emergencial.
                    </div> -->

                    <a href="cadastro.php">Novo por aqui? Cadastre se aqui!</a>
                    
                </div>
            </div>
        <?php }//fim else!!
        ?>

    </body>
</html>