<?php 

function validate($dataArray, $locationErr){
    $input_values= array();
    foreach($dataArray as $key => $input){
        if( !isset($_POST[$key]) or $_POST[$key] === '') {
            $err =  "O campo $input é obrigatório!";
            setMessage('err',$err, $locationErr);
            exit;
        }else{
            $input_values[$key] = $_POST[$key];
        }
    }
    return $input_values;
}