<?php 
require_once __DIR__ ."../../vendor/autoload.php";

use Model\User;
use Mpdf\Mpdf;
use Model\Questions;
function dd($v, $exit=false){
    echo '<pre>';
    print_r($v);
    echo '</pre>';
    if($exit) exit();
}

date_default_timezone_set('America/Sao_Paulo');


function nowMysqlFormat() {
    return date('Y-m-d H:i:s');
}


function setMessage($messageType,$message, $location, $options=false){
    header("location:$location?$messageType=" .urlencode($message). "&$options"); 
}


function logout(){
    session_destroy();
    header('location: doLogout.php');
}

if(!function_exists('getUpdatedUser')){
    if(!isset($_SESSION)) session_start();

    function getUpdatedUser(){
        $u = new User();
        
        if(!isset($_SESSION['user']) or !isset($_SESSION['user']->id)) logout();

        $user = $u->get(['id'=> $_SESSION['user']->id]);

        if(!$user) logout();

        return $user;
    }
}
function l($v, $ex=false){
    print_r("<script>console.log('$v')</script>");
    if($ex) exit();
}

function generateBankPDF(){
    $q = new Questions();
    $user = getUpdatedUser();
    $userQ = $q->get($user->id);
    

    $accepted = array(
        'Nome' => $user->name,
        'Email' => $user->email,
        'Escola' => $user->school,
        'RM' => $user->rm,
        'RG' => $userQ->rg,
        'Estado' => $userQ->uf_rg,
        'CEP' => $userQ->cep,
        'CPF' => $userQ->cpf,
        'Possui Internet' => yes_no($userQ->internet),
        'Nome da provedora de Internet' => $userQ->isp_name,
        'Configurações técnicas da Internet' => $userQ->isp_configs,
        'Quantidade de computadores desktop que possui' => $userQ->qt_pc_desktop,
        'Configurações de seu Desktop' => $userQ->pc_desktop_configs,
        'Quantidade de computadores notebook que possui' => $userQ->qt_pc_notebook,
        'Configurações do Notebook' => $userQ->pc_desktop_configs,
        'Quantidade de Smartphones que possui' => $userQ->qt_sm_phone,
        'Configurações do(s) Smartphones' => $userQ->sm_phone_configs,
        'Quantidade de Moradores onde mora' => $userQ->qtd_in_house,                            
        'Renda Per Capita' => $userQ->renda_per_capita,
        'Renda Individual' => $userQ->renda_ind,
        'Motivo do pedido do auxílio emergencial' => $userQ->reason,                            
    );
    

    
    $html = "
    <!DOCTYPE html>
    <html>
        <head>
            <meta http-equiv='Content-Type' content='text/html;charset=UTF-8'>
        </head>
        <body>
            <div class='container mt-5'>
                <nav class='navbar' style='display:flex;flex-direction:row;'>
                    <div class='right'>
                        <img style='max-width:70px;' src='assets/img/caixa.png' alt='caixa-icon' title='Caixa - Auxílio Emergencial'>
                    </div>
                    <div class='left'>
                        <div>
                            <h6><b>Nome:</b>{$user->name}</h6>
                            <h6><b>Escola:</b>{$user->school}</h6>
                            <h6><b>RM:</b>{$user->rm}</h6>
                        </div>
                    </div>
                </nav>
                <hr>
                <fieldset class='mt-2'>
                    <h5 class='mt-3'>Dados sobre {$user->name}, autorizando o Banco ___________ à criar uma conta corrente, na finalidade do aluno receber o auxílio de emergência para Alunos da Escola técnica</h5>

                    <table class='table mt-5'>
                        <thead>
                            <tr>
                                <th scope='col'>Dado</th>
                                <th scope='col'>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                        ";                          

                            
                            foreach ($accepted as $index => $value):
                                
                                $html .= "
                                    <tr>
                                        <td>$index</td>
                                        <td>$value</td>
                                    </tr>
                                ";
                            endforeach;
            $html .= "
                        </tbody>
                    </table>
                
                </fieldset>
                <hr>
                <br><br>
                <nav class='navbar mt-5'>
                    <div class='right'>
                        <img style='max-width:70px;' src='assets/img/caixa.png' alt='caixa-icon' title='Caixa - Auxílio Emergencial'>
                    </div>
                    <div class='center'>
                        &copy;2020 - Desenvolvido por @viniolimpio3
                    </div>
                    <div class='left'>
                        <div>
                            <b><p>Documento Fictício com fins de Aprendizado!</p></b>
                        </div>
                    </div>
                </nav>

            </div>
        </body>
        </html>
    ";
    try{
        $mpdf = new Mpdf();
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML("$html");    
        $mpdf->Output("bank_solicitation_{$user->rm}.pdf", 'I');
        return true;
    }catch(Exception $e){
        return false;
    }
}
function yes_no($v){
    return $v ? 'SIM' : 'NÃO';
}

