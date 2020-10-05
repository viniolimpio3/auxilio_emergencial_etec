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
    if(!is_string($str)) return false;

    $str = strip_tags(trim($str));
    
    if(!strpos($str, '$')) return $str;

    return str_replace('R$', '', $str);
}
       
function ableToAuxEm( $data ){
    if(!$user = getUpdatedUser()) return false;
    $a = new Model\Auxilio();
    
    $has_register = $a->exists($user->id);
    if(!$has_register) $a->insert($user->id);
    

    $t = gettype($data);
    if($t !== 'array' && $t !== 'object') return false;

    $data = (Object) $data;

    //strings
    $per_capita = removeDollarSign($data->renda_per_capita);
    $ind = removeDollarSign($data->renda_ind);
    $residents = $data->qtd_in_house;
    $qt_pc_desktop = $data->qt_pc_desktop;
    $qt_pc_notebook = $data->qt_pc_notebook;
    $qt_sm_phone = $data->qt_sm_phone;

    //booleans
    $has_internet = $data->internet;

    if($per_capita > 1560){
        setStatusAuxEm(false, 'Renda per capita maior que R$1560.');
        return;
    }
    if($ind > 1200) {
        setStatusAuxEm(false, 'Renda individual maior que R$1200');
        return;
    }
    if($qt_pc_desktop > 1 || $qt_pc_notebook > 1 || $qt_sm_phone > 1 ) {
        setStatusAuxEm(false, 'A quantidade de dispositivos eletrônicos em sua casa, permite que você assita as aulas.');
        return;
    }
    if($has_internet){
        setStatusAuxEm(false, 'Você possui internet.');
        return;
    }

    return true;
}

function setStatusAuxEm($status, $comments){
    $user = getUpdatedUser();
    $a = new \Model\Auxilio();

    if(!is_bool($status)) return false;
    try{
        $a->update(['user_id' => $user->id],['status' => $status, 'comments' => $comments]);
    }catch(Exception $e){
        throw new Exception($e->getMessage());
        return false;
    }
        
}