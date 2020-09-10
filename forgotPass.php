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
        // if(typeof window.history.pushState == 'function') {
        //     window.history.pushState({}, "Hide", '<?php echo $_SERVER['PHP_SELF'];?>');
        // }

    </script>

    <?php         
    ?>

    <script src="assets/js/main.js"></script>
    <body>
        <?php
            $base_url = $_SERVER['SERVER_NAME'];
            if(!isset($_SESSION)) session_start();
            if(!require './Models/User.php') require './Models/User.php';
            if(!require './Models/Mailer.php') require './Models/Mailer.php';
            
            use Model\User;
            $u = new User();

            function validateHash($hash){
                try{
                    $u = new User();                    
                    $user = $u->get(['url_hash' => $hash]);
                    if(!$user || $user === null || !count( (array) $user) > 0 ) return false;

                    return $user;
                }catch(Exception $e){
                    $e = urlencode("Erro: {$e->getMessage()}");
                    header("location: forgotPass.php?err=$e&h=$hash");
                }
            }

            //-------------------------------------------------------------------------------------------------------------
            //TROCA DE SENHA
            if(isset($_REQUEST['h']) and $_REQUEST['h'] !== '' and $_REQUEST['h'] !== null):
                $hash = $_REQUEST['h'];
                $input_values = array();

                $user = validateHash($hash);
                
                if(!$user) {
                    $e = urlencode('Erro. Tente novamente mais tarde');
                    header("location:forgotPass.php?err=$e");
                }
                if(isset($_REQUEST['cp']) and $_REQUEST['cp'] === 'ok' ){
                    $acceptedInputs = array(
                        'pass' => 'Senha',
                        'conf_pass' => 'Confirmação de senha'
                    );
                    foreach($acceptedInputs as $key => $input){
                        if( !isset($_POST[$key]) or $_POST[$key] === '') {
                            $err =  "O campo $input é obrigatório!";
                            header("location: forgotPass.php?err=$err&h=$hash");
                            exit;
                        }else{
                            $input_values[$key] = $_POST[$key];
                        }
                    }

                    if($input_values['pass'] !== $input_values['conf_pass']){
                        $e = urlencode('As senhas devem ser exatamente iguais');
                    }
                    $hashP = sha1($input_values['conf_pass']);
                         
                    $update = $u->update(['id' => $user->id],['senha'=>$hashP]);

                    if($update){
                        $s = urlencode("{$user->name}, você atualizou sua senha com sucesso. Você será redirecionado para o login.");
                        header("location: forgotPass.php?h=$hash&success-l=$s");
                    }else{
                        $e = "Não foi possível atualizar sua senha. Tente novamente";
                        header("location: forgotPass.php?h=$hash&err=$e");
                    }
                }else{
                ?>
                    <div class="container mt-5">
                        <h3>Atualizar Senha <small>Alunos do Ensino médio - Auxílio Emergencial</small></h3>
                        <div class="jumbotron">
                            
                            <form action="forgotPass.php?h=<?= $hash ?>&&cp=ok" method="POST" >

                                <label for="user_mail">Senha:</label>
                                <input autocomplete="off" type="password" name="pass" id="pass" class="form-control">

                                <label for="conf_pass">Confirme a Senha:</label>
                                <input autocomplete="off" type="password" name="conf_pass" id="conf_pass" class="form-control">

                                <button type="submit" class="btn mt-3 btn-dark">Enviar</button>

                            </form>
                            
                            <a href="login.php">Possui uma conta? Entre com seu RM!</a>   
                            
                            <?php if(isset($_GET['success-l'])): ?>
                                <div class="mt-4 alert-success alert fade show" role="alert">
                                    <?= urldecode( $_GET['success-l']); ?>
                                </div>

                                <script type="text/javascript">
                                    setTimeout(() =>{
                                        window.location = 'login.php';
                                    }, 3000)
                                </script>
                            <?php endif ?>
                        </div>
                    </div>
                <?php
                }
            endif;   

            // -----------------------------------------------------------------------------------------------------
            //ENVIO DE EMAIL
            
            if( isset($_REQUEST['send']) and $_REQUEST['send'] === 'ok' and !isset($_REQUEST['h'])){
                $input_values = array();
                
                $acceptedInputs = array(
                    'user_mail' => 'email',
                );
                foreach($acceptedInputs as $key => $input){

                    if( !isset($_POST[$key]) or $_POST[$key] === '') {
                        $err =  "O campo $input é obrigatório!";
                        header("location: forgotPass.php?err=$err");
                        exit;
                    }else{
                        $input_values[$key] = $_POST[$key];
                    }

                }      
                
                $mail = $input_values['user_mail'];


                $user = $u->get(['email' => $mail]);

                if(!$user) {
                    $err = urlencode("Digite um email válido!");
                    header("location:forgotPass.php?err=$err");
                    exit();
                }

                // if($user->forgot_pass){
                //     $err = urlencode("Ocorreu um erro\nTente novamente Mais tarde");
                //     header("location:forgotPass.php?err=$err");
                //     exit();
                // }

                $mailer = new Mailer\Mailer();

                $hash = sha1(rand(100000,9999999));

                $body = "
                <body>

                    <h1>Olá {$user->name}!</h1>
                    <h3>
                        Recentemente recebemos uma requisição de troca de senha do sistema <a href='".BASE_URL."'> auxílio emergencial</a>. 
                        Caso não tenha feito isso, ignore esta mensagem. 
                    <h3>
                    <a href='".BASE_URL."forgotPass.php?h=$hash'>Clique aqui para trocar sua senha</a>

                <body>  
                ";
                $enviou = $mailer->send($mail, $user->name, 'Recuperar Senha',$body);

                $filter=array(  
                    'id' => $user->id,
                );

                $data = array('forgot_pass' => true, 'url_hash' => $hash);
                $update = $u->update( $filter, $data);

                if($update and $enviou){
                    $s = urlencode("Pronto {$user->name}. Enviamos um email com os próximos passos!");
                    header("location:forgotPass.php?success=$s");
                }else{
                    $e = urlencode("Não foi possível enviar um email para $mail");
                    header("location:forgotPass.php?err=$e");
                }
                
                // }
            }else if(!isset($_REQUEST['h'])){
        ?>
            <div class="container mt-5">
                <h3>Esqueceu Senha <small>Alunos do Ensino médio</small></h3>
                <div class="jumbotron">
                    <form action="forgotPass.php?send=ok" method="POST" >

                        <label for="user_mail">Email:</label>
                        <input autocomplete="off" type="email" name="user_mail" id="user_mail" class="form-control">

                        <button type="submit" class="btn mt-3 btn-dark">Enviar</button>

                    </form>
                    
                    
                    <a href="login.php">Possui uma conta? Entre com seu RM!</a>                    
                </div>
            </div>
            <?php }//fim else!!
        ?>
        <div class="container">
            <?php if(isset($_GET['err'])): ?>
                <div class="mt-4 alert-danger alert fade show" role="alert">
                    <?=$_GET['err']?>
                </div>
            <?php endif ?>

            <?php if(isset($_GET['success'])): ?>
                <div class="mt-4 alert-success alert fade show" role="alert">
                    <?= urldecode( $_GET['success']); ?>
                </div>
            <?php endif ?>
        </div>
    </body>
</html>