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

function removeDollarSign($str){
    $str = trim($str);
    if(!strpos('$', $str) && !strpos('R$', $str) ) return $str;
    
    return str_replace(['R$', '$'], '', $str);

}

if(!function_exists('ableToAuxEm')){   
    function ableToAuxEm( $data){
        $data = (Object) $data;

        $per_capita = $data->renda_per_capita;



        dd($data, true);
    }
}