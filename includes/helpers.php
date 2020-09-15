<?php 
use Model\User;
$u = new User();
if(!function_exists('dd')){
    function dd($v, $exit=false){
        echo '<pre>';
        print_r($v);
        echo '</pre>';
        if($exit) exit();
    }
}
date_default_timezone_set('America/Sao_Paulo');

if(!function_exists('nowMysqlFormat')){
    function nowMysqlFormat() {
        return date('Y-m-d H:i:s');
    }    
}
