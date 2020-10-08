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

            has_registerAux();

            $a = $aux->get(['user_id' => $user->id]);

            use Model\Questions;
            $q_model = new Questions();

            $q = $q_model->get($user->id);
                
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

            if(isset($_REQUEST['cadastrar']) and $_REQUEST['cadastrar'] === 's' and !$answered_questions){
                try{
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

                    $able = ableToAuxEm($input_values);

                    $inserted = $q_model->insert($input_values, $user->id);

                    if(isset($_POST['link_photo']) and $_POST['link_photo'] !== '' ) $u->update(['id' => $user->id], ['link_photo' => $_POST['link_photo']]);
                    
                    if(!$inserted){
                        $q_model->delete($user->id);
                        $u->update(['id' => $user->id], ['answered_questions' => false]);
                        setMessage('err', 'Ocorreu um erro! <br>Preencha novamente o formulário, e reenvie', 'painel.php');         
                    }

                    $updateuser = $u->update([ 'id' => $user->id ],[ 'answered_questions' => true ]);

                    if(!$updateuser) setMessage('err', 'Ocorreram erros internos! <br> Tente novamente mais tarde','painel.php');
                                        
                    if($able)setMessage('success', "Parabéns {$user->name}, você está quase cadastrado no auxílio emergencial. Aguarde para mais informações", 'painel.php');
                    else setMessage('error', "{$user->name}, infelizmente sua requisição foi negada...", 'painel.php');
                    

                }catch(Exception $e){
                    dd($e->getMessage());
                    $u->update(['id' => $user->id], ['answered_questions' => false]);
                    $aux->update(['user_id' => $user->id], ['status' => false, 'comments' => '']);
                }

            }else{?>  
                <div class="container mt-5">
                    <?php require_once 'includes/basic_header.php'; ?>
                    <div class="jumbotron">

                        <?php 
                            err(10000);
                            success('painel.php');
                        ?>
                        <?php if( $a->comments == ''): ?>
                            <?php if(!$answered_questions): ?>
                                <h2>Boa <?= $user->name?>! </h2>
                                <p>Responda as questões abaixo para concluir sua inscrição</p>
                                <form id="form" action="painel.php?cadastrar=s" method="POST" >

                                    <?php require_once 'includes/questions_form.php' ?>

                                    <button class="mt-5 btn btn-primary">Enviar</button>
                                </form> 
                            <?php endif ?>
                            <?php include_once 'includes/confirm_data.php' ?>

                        <?php else: ?>
                            <?php if ( $user->answered_bank_q ):  ?>
                                <div class="alert alert-<?=$a->status ? 'success' : 'danger'?>">
                                    <h2>Situação: <?= $a->status ?'Aprovado' : 'Não aprovado'; ?></h2> 
                                    <h6>Parecer: <?=$a->comments?>    </h6>                                        
                                </div>
                                
                                <?php if ( !$user->has_bank_account ):  ?>
                                    <br>
                                    <p>Você não possui um banco, baixe o pdf a seguir e leve-o ao banco mais próximo!</p>

                                    <a class="btn btn-success" href="bank_panel.php?get_pdf=<?=$user->id?>">Baixar PDF</a>
                                <?php else: ?>
                                    <br>
                                    <?php $bank = (Object) array(
                                        'Nome do Banco' => $q->bank_name,
                                        'Conta corrente' => $q->bank_account,
                                        'Agência' => $q->bank_agency
                                    ); ?>

                                    <h4><?=$user->name?>, como você possui uma conta bancária, o valor será depositado em sua conta!</h4>

                                    <h6>Seus dados bancários:</h6>

                                    <table class="table">
                                        <thead>
                                            <th scope="col">Nome do Banco</th>
                                            <th scope="col">Conta Corrente</th>
                                            <th scope="col">Agência</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <?php foreach ($bank as $k => $v) :  ?>
                                                    <td>    
                                                        <?=$v?>
                                                    </td>
                                                <?php endforeach ?>
                                            </tr>
                                        </tbody>  
                                    </table>                                    
                                <?php endif; ?>
                            <?php endif ?> 
                            <?php if ( !$a->status and $a->comments != '' ):  ?>
                                <div class="alert alert-danger">
                                    <h3> <?=$user->name?>, seu pedido foi rejeitado. </h3>
                                    <p>Observações: <?=$a->comments?></p>
                                </div>
                            <?php endif ?>

                            <?php include_once 'includes/confirm_data.php' ?>
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
        </script>


    <?php endif ?>
</html>