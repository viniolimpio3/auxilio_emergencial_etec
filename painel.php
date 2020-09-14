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
        <script src="assets/js/main.js"></script>

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


    <body>
        <?php
            require_once __DIR__  . '/vendor/autoload.php';

            if(!isset($_SESSION)) session_start();
            if(!require 'isAuthorized.php') header('location:login.php');

            $user = $_SESSION['user'];

            use Model\Auxilio;
            $aux = new Auxilio();

            use Model\Questions;
            $q_model = new Questions();

            use Model\User;
            $u = new User();

            if(!isset($user) || !isset($user->id)) header("location: doLogout.php");

            $cadastrado = $aux->exists($user->id);
            
            if(isset($_REQUEST['cadastrar']) and $_REQUEST['cadastrar'] === 's' ){
                
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
                    <div class="flex-between mb-3">
                        <h3>Cadastro Auxílio Emergencial <small>Alunos do Ensino médio</small></h3>
                        <a href="doLogout.php" class="btn btn-danger">Sair</a>
                    </div>
                    <div class="jumbotron">

                            <?php if(!$cadastrado):?>
                                <h2>Boa <?= $user->name?>! </h2>
                                <p>Responda as questões abaixo para concluir sua inscrição</p>
                                <form action="painel.php?cadastrar=s" method="POST" >

                                    <?php if(!require 'questions_form.php') require 'questions_form.php' ?>
                                    <button class="mt-5 btn btn-primary">Enviar</button>
                                </form> 

                            <?php else: ?>
                                <div class="alert alert-success">
                                    Parabéns <?= $user->name ?>, você está cadastrado no auxílio emergencial! 
                                </div>

                                <h2>Seus Dados:</h2>
                                <form action="painel.php?update=yes" method="POST" >

                                    <p>Nome:</p>
                                    <input class="form-control inputs" readonly value="<?= $user->name ?>" type="text">  <br>

                                    <p>Cidade:</p>
                                    <input class="form-control inputs" readonly value="<?= $user->city ?>" type="text">  <br>

                                    <p>Estado:</p>
                                    <input class="form-control inputs" readonly value="<?= $user->state ?>" type="text">  <br>

                                    <p>Escola:</p>
                                    <input class="form-control inputs" readonly value="<?= $user->school ?>" type="text">  <br>

                                    <p>RM:</p>
                                    <input  maxlength="6" onkeypress="return onlyNumber()" class="form-control inputs" readonly value="<?= $user->rm ?>" type="text">  <br>

                                    <button class="btn btn-dark" style="display: none !important;" type="submit" id="submit">Enviar</button>
                                    <br>
                                    <button class="btn btn-danger" style="display: none !important;" type="button" id="cancel">Cancelar</button>
                                    <br>

                                    <div style="display: none;" id="temp" class="alert alert-danger">
                                        Ainda não desenvolvemos essa função :/
                                    </div>
                                    <button class="btn btn-dark" type="button" id="alterar">Alterar Seus dados</button>
                                </form>
                            <?php endif; ?>

                            <?php if(isset($_GET['err'])): ?>
                                <div class="mt-4 alert-danger alert-dismissible alert fade show" role="alert">
                                    <?=$_GET['err']?>
                                </div>
                            <?php endif ?>

                            <?php if(isset($_GET['success'])): ?>
                                <div class="mt-4 alert-success alert-dismissible alert fade show" role="alert">
                                    <?=$_GET['success']?>
                                </div>
                                <script>
                                    setTimeout(()=>{
                                        location.reload()
                                    },3000)
                                </script>
                            <?php endif ?>

                    </div>
                </div>
            <?php }//fim else - $_request!!

           
            ?>

    </body>

    <script type="text/javascript">
        const btnAlterar = document.querySelector('#alterar')
        const submitButton = document.querySelector('#submit')
        const btnCancelar = document.querySelector('#cancel')

        const inputs = document.getElementsByClassName('inputs')

        btnAlterar.onclick = function(){
            submitButton.style.display = 'block'
            btnCancelar.style.display = 'block'
            btnAlterar.style.display = 'none'
            
            Object.values(inputs).forEach(field  => {
                field.removeAttribute('readonly')
            })

            document.querySelector('#temp').style.display = 'block'
        }
        btnCancelar.onclick = function(){
            
            btnAlterar.style.display = 'block'
            btnCancelar.style.display = 'none'
            submitButton.style.display = 'none'
            Object.values(inputs).forEach(field  => {
                field.setAttribute('readonly', '')
            })

            document.querySelector('#temp').style.display = 'none'

        }
    </script>
</html>