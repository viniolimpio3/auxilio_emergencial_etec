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
    <style>
        .flex-between{
            display: flex;
            flex-direction: row;
            justify-content: space-around;
        }

    </style>
        <script src="assets/js/main.js?v=1"></script>


    <body>
        <?php
            require_once __DIR__  . '/vendor/autoload.php';

            if(!isset($_SESSION)) session_start();
            if(!require 'isAuthorized.php') header('location:login.php');

            $user = isset($_SESSION['user']) ? $_SESSION['user'] : header('location:login.php?err=Erro') ;
            

            use Model\Auxilio;
            $aux = new Auxilio();

            use Model\Questions;
            $q_model = new Questions();

            use Model\User;
            $u = new User();

            if(!isset($user) || !isset($user->id)) header("location: doLogout.php");

            $cadastrado = $aux->exists($user->id);

            if(isset($_REQUEST['data-confirm']) and $_REQUEST['data-confirm'] === 'y' ){
                
                $obrigatorios = array(
                    'rg' => 'RG',
                    'uf_rg' => 'UF do RG',
                    'cpf' => 'CPF',
                    'cep' =>'CEP',
                    'qt_pc_desktop' => 'Quantidade de computadores Desktop',
                    'qt_pc_notebook' => 'Quantidade de computadores notebook',
                    'qt_sm_phone' => 'Quantidade de computadores notebook',
                    'renda_per_capita' => 'Renda per capita',
                    'qtd_in_house' => 'Quantidade de habitantes em casa',
                    'renda_ind' => 'Renda Individual',
                    'internet' => 'Internet',
                    'reason' => 'Motivo'
                );
                

                foreach($obrigatorios as $key => $input){
                    if( !isset($_POST[$key]) or $_POST[$key] === '') {
                        $err =  "O campo $input é obrigatório!";
                        header("location: painel.php?err=$err");
                        exit;
                    }else{
                        $input_values[$key] = $_POST[$key];
                    }
                }      

                //campos opcionais
                $input_values['isp_configs'] = isset($_POST['isp_configs']) ? $_POST['isp_configs'] : '' ;
                $input_values['pc_desktop_configs'] = isset($_POST['pc_desktop_configs']) ? $_POST['pc_desktop_configs'] : '' ;
                $input_values['pc_notebook_configs'] = isset($_POST['pc_notebook_configs']) ? $_POST['pc_notebook_configs'] : '' ;
                $input_values['sm_phone_configs'] = isset($_POST['sm_phone_configs']) ? $_POST['sm_phone_configs'] : '' ;

                $inserted = $q_model->insert($input_values, $user->id);
                
                if(!$inserted){
                    $e = urlencode('Ocorreu um erro! <br>Preencha novamente o formulário, e reenvie');
                    header("painel.php?err=$e");   
                }

                $inserted_aux = $aux->insert($user->id);

                if(!$inserted_aux){
                    $e = urlencode('Ocorreu um erro! <br>Tente novamente mais tarde');
                    header("painel.php?err=$e");   
                }

                $updateuser = $u->update(['id'=>$user->id],['answered_questions' =>true]);
                if(!$inserted_aux){
                    $e = urlencode('Ocorreu um erro! <br> Tente novamente mais tarde');
                    header("painel.php?err=$e");   
                }
                
                $s = urlencode("Parabéns {$user->name}, vc está cadastrado no auxílio emergencial. Aguarde para mais informações");
                header("location: painel.php?success=$s");

            }else{?>  
                <div class="container mt-5">
                    <?php require_once 'includes/basic_header.php'; ?>
                    <div class="jumbotron">
                        <?php 
                            $usuario = $u->get(['id' => $user->id]);
                            $diffTime = $u->getTimestampDiff($usuario->vf_code_created_at);
                        ?>
                    </div>
                </div>

            <?php }//fim else - $_request!!
            ?>

    </body>
<!-- 
    <script type="text/javascript">
        const btnAlterar = document.querySelector('#alterar')
        const submitButton = document.querySelector('#submit')
        const btnCancelar = document.querySelector('#cancel')

        const inputs = document.getElementsByClassName('inputs')

        btnAlterar.onclick = function(){
            submitButton.style.display = 'block'
            btnCancelar.style.display = 'block'
            btnAlterar.style.display = 'none'
            
            unsetReadOnlyInputs(inputs)
            document.querySelector('#temp').style.display = 'block'
        }
        btnCancelar.onclick = function(){
            
            btnAlterar.style.display = 'block'
            btnCancelar.style.display = 'none'
            submitButton.style.display = 'none'
            
            setReadOnlyInputs(inputs)
            document.querySelector('#temp').style.display = 'none'
        }
    </script> -->
</html>