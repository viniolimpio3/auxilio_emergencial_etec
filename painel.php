<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cadastro Auxílio Emergencial Estudantil</title>

        <script src="assets/js/main.js?v=1"></script>

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
        .profile_image img {
            max-width: 20%;
            margin-bottom: 15px;
            border: 2px solid #222;
        }

    </style>


    <body>
        <?php
            if(!require 'isAuthorized.php') logout();
            require_once __DIR__  . '/vendor/autoload.php';
            use Model\User;
            $u = new User();
            
            if(!isset($_SESSION)) session_start();

            $user = updateUser();

            use Model\Auxilio;
            $aux = new Auxilio();
            
            use Model\Questions;
            $q_model = new Questions();
                
            $answered_questions = $user->answered_questions;
            
            // if(isset($_REQUEST['update']) and $_REQUEST['update']=== 'yes'){
            //     
            // }
            
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
                    'reason' => 'Motivo',
                    'city' => 'Cidade'
                );

                $input_values = validate($obrigatorios, 'painel.php');

                //campos opcionais
                $input_values['isp_configs'] = isset($_POST['isp_configs']) ? $_POST['isp_configs'] : '' ;
                $input_values['pc_desktop_configs'] = isset($_POST['pc_desktop_configs']) ? $_POST['pc_desktop_configs'] : '' ;
                $input_values['pc_notebook_configs'] = isset($_POST['pc_notebook_configs']) ? $_POST['pc_notebook_configs'] : '' ;
                $input_values['sm_phone_configs'] = isset($_POST['sm_phone_configs']) ? $_POST['sm_phone_configs'] : '' ;

                $inserted = $q_model->insert($input_values, $user->id);
                
                if(!$inserted) setMessage('err', 'Ocorreu um erro! <br>Preencha novamente o formulário, e reenvie', 'painel.php');          

                $updateuser = $u->update([ 'id' => $user->id ],[ 'answered_questions' => true ]);

                if(!$updateuser) setMessage('err', 'Ocorreram erros internos! <br> Tente novamente mais tarde','painel.php');
                                 
                
                $s = "Parabéns {$user->name}, vc está cadastrado no auxílio emergencial. Aguarde para mais informações";
                header("location: painel.php?success=$s");

            }else{?>  
                <div class="container mt-5">
                    <?php require_once 'includes/basic_header.php'; ?>
                    <div class="jumbotron">

                            <?php 
                                err(10000);
                                success('painel.php');
                            ?>
                            <?php if(!$answered_questions):?>
                                <h2>Boa <?= $user->name?>! </h2>
                                <p>Responda as questões abaixo para concluir sua inscrição</p>
                                <form id="form" action="painel.php?cadastrar=s" method="POST" >

                                    <?php require_once 'includes/questions_form.php' ?>

                                    <button class="mt-5 btn btn-primary">Enviar</button>
                                </form> 

                            <?php else: ?>
                                <div class="alert alert-success">
                                    <?= $user->name ?>, falta pouco para terminar o cadastro no auxílio emergencial! 
                                </div>

                                <h2>Confirme seus dados para prosseguirmos!</h2>

                                

                                <form action="painel.php?update=yes" method="POST" >

                                    <p>Email:</p>
                                    <input class="form-control" readonly value="<?= $user->email ?>" type="email">  <br>

                                    <p>Imagem URL:</p>
                                    <div class="profile_image">
                                        <img src="<?=$user->link_photo?>" alt="image_<?=$user->name?>" title="profile_<?=$user->name?>">
                                        <input class="inputs form-control" readonly type="url" class="form-control" value="<?=$user->link_photo?>" name="link_photo">
                                    </div><br>

                                    <p>Nome:</p>
                                    <input class="form-control inputs" readonly value="<?= $user->name ?>" type="text">  <br>

                                    <p>Escola:</p>
                                    <input class="form-control inputs" readonly value="<?= $user->school ?>" type="text">  <br>

                                    <p>RM:</p>
                                    <input  maxlength="6" onkeypress="return onlyNumber()" class="form-control inputs" readonly value="<?= $user->rm ?>" type="text">  <br>

                                    <a href="bank_panel.php" class="btn btn-success">Confirmar</a>

                                    <br>
                                    <button class="btn btn-dark mt-3" hidden type="submit" id="submit">Enviar</button>
                                    <br>
                                    <button class="btn btn-danger mt-3" hidden type="button" id="cancel">Cancelar</button>
                                    <br>
                                    <div hidden id="temp" class="alert alert-danger">
                                        Ainda não desenvolvemos essa função :/
                                    </div>

                                    <button class="btn btn-dark mr-3" type="button" id="alterar">Alterar</button>
                                    
                                </form>
                            <?php endif; ?>

                            

                    </div>
                </div>
            <?php }//fim else - $_request!!

           
            ?>

    </body>
    <?php if($answered_questions): ?>
        <script type="text/javascript">
            const btnAlterar = id('alterar')
            const submitButton = id('submit')
            const btnCancelar = id('cancel')

            const inputs = document.getElementsByClassName('inputs')

            btnAlterar.onclick = function(){
                show([submitButton, btnCancelar, id('temp')])
                hide([btnAlterar])
                
                unsetReadOnlyInputs(inputs)       
            }
            btnCancelar.onclick = function(){           
                hide([btnCancelar, submitButton, id('temp')])
                show([btnAlterar])
                
                setReadOnlyInputs(inputs)
            }
        </script>


    <?php endif ?>
</html>