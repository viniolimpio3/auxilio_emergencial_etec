<?php 
use Model\User;
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

if(!function_exists('updateUser')){
    if(!isset($_SESSION)) session_start();

    function updateUser(){
        $u = new User();
        
        if(!isset($_SESSION['user']) or !isset($_SESSION['user']->id)) logout();

        $user = $u->get(['id'=> $_SESSION['user']->id]);

        if(!$user) logout();

        return $user;
    }
}