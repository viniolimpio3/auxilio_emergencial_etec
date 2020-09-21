<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cadastro Auxílio Emergencial Estudantil</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.20.0/axios.min.js" integrity="sha512-quHCp3WbBNkwLfYUMd+KwBAgpVukJu5MncuQaWXgCrfgcxCJAq/fo+oqrRKOj+UKEmyMCG3tb8RB63W+EmrOBg==" crossorigin="anonymous"></script>

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
            require_once __DIR__  . '/vendor/autoload.php'; //autoload de classes e arquivos 

            if(!isset($_SESSION)) session_start();
            if(!require 'isAuthorized.php') header('location:login.php');
            $user = updateUser();
            
            use Model\Auxilio;
            $aux = new Auxilio();
            
            use Model\Questions;
            $q_model = new Questions();
            
            use Model\User;
            $u = new User();


            if(isset($_REQUEST['get_pdf']) and $_REQUEST['get_pdf'] === $user->id){
                $s = generateBankPDF();
                if(!$s) setMessage('err', "Poxa {$user->name}, ocorreu um erro:/<br>Tente novamente mais tarde", 'bank_panel.php');
            }
            if(!isset($user) || !isset($user->id)) header("location: doLogout.php");

            $cadastrado = $aux->exists($user->id);

            $answered_bank_q = $user->answered_bank_q;

            if(isset($_REQUEST['h']) and $_REQUEST['h'] === 'doesnt-have-bank'){//não possui conta bancária
                $gerouPDF = generateBankPDF();
                if(!$gerouPDF) setMessage('err', "Poxa {$user->name}, ocorreu um erro:/<br>Tente novamente mais tarde", 'bank_panel.php');
                dd($gerouPDF, true);
                $u->update(['id' => $user->id],['answered_bank_q'=> true, 'has_bank_account' => false]);
                setMessage('success', 'Agora baixe o arquivo em PDF, e leve-o até uma agência bancária, que autorize a criação de uma conta corrente!', 'bank_panel.php?');        
            }
            
            
            if(isset($_REQUEST['h']) and $_REQUEST['h'] === '3fadfi2j3hra9sdufh2jhfk' ){//possui conta bancária
                

                $acceptedInputs = array(
                    'bank_agency' => 'Agência Bancária',
                    'bank_name' => 'Nome do Banco',
                    'bank_account' => 'Conta Corrente Bancária'
                );

                $input_values = validate($acceptedInputs, 'bank_panel.php');

                $updated = $q_model->update($user->id,$input_values);

                if(!$updated) {
                    $u->update(['id' => $user->id], ['answered_bank_q' => false]);
                    setMessage('err', "Poxa {$user->name}, ocorreu um erro:/<br>Tente novamente mais tarde", 'bank_panel.php');
                }
                $u->update(['id' => $user->id], ['answered_bank_q' => true, 'has_bank_account' => true]);

                setMessage('success', "Boa {$user->name}! Seus dados agora estão sendo analisados!", 'painel.php');

            }else{?>  
                <div class="container mt-5">
                    <?php require_once 'includes/basic_header.php'; ?>
                    <div class="jumbotron">
                        <?php 
                            err();
                            success('bank_panel.php');
                        ?>
                        <?php if ( !$answered_bank_q ):  ?>
                            <h2>OK <?=$user->name?>, agora responda:</h2>
                            <form action="bank_panel.php?h=3fadfi2j3hra9sdufh2jhfk" method="POST" >
                                <div class="row">
                                    <div class="col">
                                        <h4>Você possui uma conta bancária? *</h4>
                                        <div class="custom-control custom-radio">
                                            <input checked type="radio" id="n" value="0" name="has_bank_account" class="custom-control-input">
                                            <label class="custom-control-label" for="n">Não</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="s" value="1" name="has_bank_account" class="custom-control-input">
                                            <label class="custom-control-label" for="s">Sim</label>
                                        </div>
                                    </div>
                                </div>

                                <a id="if_not_have_bank_ac" href="bank_panel.php?h=doesnt-have-bank" class="mt-3 btn btn-dark">Confirmar</a>


                                <div hidden id="another_questions" >
                                    <div class="row">
                                        <div class="col">
                                            <label for="bank_account">Conta:</label>
                                            <input id="bank_account" name="bank_account" onkeypress="return onlyNumber()" maxlength="6" autocomplete="off" type="text" class="form-control">
                                        </div>
                                        <div class="col">
                                            <label for="bank_name">Banco:</label>
                                            <select class="form-control" name="bank_name" id="bankSelect">
                                                <option hidden value="0">Banco*</option>
                                            </select>
                                            <input name="bank_code" id="bank_code" type="hidden">
                                        </div>
                                        <div class="col">
                                            <label for="bank_agency">Agência:</label>
                                            <input id="bank_agency" name="bank_agency" onkeypress="return onlyNumber()" maxlength="6" autocomplete="off" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col">
                                            <button class="btn btn-dark" type="submit">Enviar</button>
                                        </div>
                                    </div>
                                </div>


                            </form>
                        <?php else: ?>
                            <?php if ( $user->has_bank_account ):  ?>
                                <!-- mostrar se o usuário cadastrou no auxílio emergencial -->
                            <?php else: ?>
                                <!-- user não possui conta bancária - mostrar pdf com seus dados para levar à um banco -->
                                <div class="alert alert-success">
                                    Sua requisição já esta finalizada
                                </div>
                                <a href="bank_panel.php?get_pdf=<?=$user->id?>" class="btn btn-dark">Clique aqui para baixar seu PDF</a>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                </div>
            <?php }//fim else - $_request!!
            ?>

    </body>

    <script type="text/javascript">

        window.onload = async function(){
            const bankSelect = id('bankSelect')
            const bankCode = id('bank_code')
            await getBankNames(id('bankSelect'))
            const hasAccount = document.getElementsByName('has_bank_account')[1]
            const notHaveAccount = document.getElementsByName('has_bank_account')[0]

            hasAccount.onchange = function(){
                if(hasAccount.checked){
                    hide([id('if_not_have_bank_ac'),])
                    show([id('another_questions'),])
                }
            }

            notHaveAccount.onchange = function(){
                if(notHaveAccount.checked) {
                    show([ id('if_not_have_bank_ac'), ])
                    hide([ id('another_questions'), ])
                }
            }

            bankSelect.onchange = function(e){
                const a = getBankCode(e.target.value)
                bankCode.setAttribute('value', a)
            }

        }
    </script>

</html>