<?php 

function validate($dataArray, $locationErr){
    $input_values= array();
    foreach($dataArray as $key => $input){
        if( !isset($_POST[$key]) or $_POST[$key] === '') {
            $err =  "O campo $input é obrigatório!";
            header("location: $locationErr?err=$err");
            exit;
        }else{
            $input_values[$key] = $_POST[$key];
        }
    }
    return $input_values;
}
?>