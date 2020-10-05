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
        <link rel="stylesheet" href="assets/css/main.css">
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
            require_once __DIR__  . '/vendor/autoload.php';
            if(!require 'isAuthorized.php') logout();
            use Model\User;
            $u = new User();
            
            if(!isset($_SESSION)) session_start();

            $user = getUpdatedUser();


            use Model\Auxilio;
            $aux = new Auxilio();

            $a = $aux->get(['user_id' => $user->id]);
            
            use Model\Questions;
            $q_model = new Questions();
                
            $answered_questions = $user->answered_questions;
            
            if(isset($_REQUEST['update']) and $_REQUEST['update']=== 'yes'){
                $acceptedInputs = array(
                    'link_photo' => 'URL Imagem',
                    'name' => 'Nome',
                    'school' => 'Escola',
                    'rm' => 'RM'
                );

                $input_values = validate($acceptedInputs, 'painel.php');
                
                $updated = $u->update(['id' => $user->id], $input_values);

                if(!$updated) setMessage('err', 'Não foi possível atualizar sua informações<br>Tente novamente mais tarde.', 'painel.php');

                setMessage('success',"Parabéns {$user->name}, seus dados foram atualizados!",'bank_panel.php', 10000);

            }
            
            if(isset($_REQUEST['cadastrar']) and $_REQUEST['cadastrar'] === 's' ){
                
                $q_model->issetUserID($user->id);

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

                ableToAuxEm($input_values);

                $inserted = $q_model->insert($input_values, $user->id);

                if(isset($_POST['link_photo'])) $u->update(['id' => $user->id], ['link_photo' => $_POST['link_photo']]);
                
                if(!$inserted){
                    $q_model->delete($user->id);
                    $u->update(['id' => $user->id], ['answered_questions' => false]);
                    setMessage('err', 'Ocorreu um erro! <br>Preencha novamente o formulário, e reenvie', 'painel.php');         
                }

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
                        <?php if(!$a->status ): ?>
                            <?php if(!$answered_questions): ?>
                                <h2>Boa <?= $user->name?>! </h2>
                                <p>Responda as questões abaixo para concluir sua inscrição</p>
                                <form id="form" action="painel.php?cadastrar=s" method="POST" >

                                    <?php require_once 'includes/questions_form.php' ?>

                                    <button class="mt-5 btn btn-primary">Enviar</button>
                                </form> 
                            <?php endif ?>
                            <?php if(!$user->answered_bank_q and $answered_questions): ?>
                                <div class="alert alert-success">
                                    <?= $user->name ?>, falta pouco para terminar o cadastro no auxílio emergencial! 
                                </div>
                                
                                <div id="helperDivImageLink" photo_link="<?= $user->link_photo ?>" hidden></div>

                                <h2>Confirme seus dados para prosseguirmos!</h2>

                            
                                <form action="painel.php?update=yes" method="POST" >

                                    <p>Email:</p>
                                    <input class="form-control" readonly value="<?= $user->email ?>" type="email">  <br>

                                    <p>Imagem URL:</p>
                                    <div id="errImgURL" class="alert alert-danger" hidden> A URL: <?=$user->link_photo?> não é válida! Insira um link válido </div>
                                    <div class="profile_image" >
                                        <img src="<?=$user->link_photo?>" alt="image_<?=$user->name?>" id="profile_image" title="profile_<?=$user->name?>">
                                        <input class="inputs form-control" readonly type="url" class="form-control" value="<?=$user->link_photo?>" name="link_photo">
                                    </div><br>

                                    <p>Nome:</p>
                                    <input class="form-control inputs" readonly value="<?= $user->name ?>" name="name" type="text">  <br>

                                    <p>Escola:</p>
                                    <input class="form-control inputs" readonly value="<?= $user->school ?>" name="school" type="text">  <br>

                                    <p>RM:</p>
                                    <input  maxlength="6" onkeypress="return onlyNumber()" class="form-control inputs" name="rm" readonly value="<?= $user->rm ?>" type="text">  <br>

                                    <a href="bank_panel.php" id="conf" class="btn btn-success">Confirmar</a>

                                    <br>
                                    <button class="btn btn-dark mt-3" hidden type="submit" id="submit">Enviar</button>
                                    <br>
                                    <button class="btn btn-danger mt-3" hidden type="button" id="cancel">Cancelar</button>
                                    <br>
                                    <button class="btn btn-dark mr-3" type="button" id="alterar">Alterar</button>
                                    
                                </form>
                            <?php else: ?>
                                <?php if ( $user->answered_bank_q ):  ?>
                                    <h3><?=$user->name?> seus dados estão em análise...</h3>
                                    <?php if ( !$user->has_bank_account ):  ?>
                                        <a href="bank_panel.php?get_pdf=<?=$user->id?>">Baixar seus dados</a>
                                    <?php endif ?>
                                <?php endif ?> 
                            <?php endif ?>

                            <?php if ( $a->status and $a->comments ):  ?>
                                <div class="alert alert-danger">
                                    <h3> <?=$user->name?>, seu pedido foi rejeitado. </h3>
                                    <p>Observações: <?=$a->comments?></p>
                                </div>
                            <?php endif ?>
                        <?php else: ?>
                            <div class="alert alert-success">
                                <h3>Parabéns <?=$user->name?>, você está cadastrado no auxílio emergencial!!</h3>
                                <a href="bank_panel.php?get_pdf=<?=$user->id?>">Baixar seus dados</a>
                            </div>
                        <?php endif ?>
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

            const btnConf = id('conf')

            const inputs = document.getElementsByClassName('inputs')

            btnAlterar.onclick = function(){
                show([submitButton, btnCancelar, ])
                hide([btnAlterar, btnConf])
                
                unsetReadOnlyInputs(inputs)       
            }
            btnCancelar.onclick = function(){           
                hide([btnCancelar, submitButton, ])
                show([btnAlterar, btnConf])
                
                setReadOnlyInputs(inputs)
            }

            // window.onload = function(){
            //     const divProfileImage = id('profile_image')
            //     const imgURL = id('helperDivImageLink').getAttribute('photo_link')
            //     const errDivURL = id('errImgURL')
            //     console.log(imgURL)
            //     if(!isImage(imgURL)){
            //         hide([divProfileImage])
            //         show([errDivURL])
            //     }
            // }
        </script>


    <?php endif ?>
</html>