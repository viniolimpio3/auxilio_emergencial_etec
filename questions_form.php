<style>
.flex-center{
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
}
</style>

<h4>Primeiro, preencha seus documentos</h4>  
<p style="color: red;">* Campo obrigatório</p>  
<div class="row mt-4">
    <div class="col">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="RG*"  onkeypress="return onlyNumber()" name="rg" id="rg">
        </div>
    </div>

    <div class="col">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="UF do RG*" maxlength="2" name="uf_rg" id="uf_rg">
        </div>
    </div>

    <div class="col">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="CPF*" onkeypress="return onlyNumber()" name="cpf" id="cpf">
        </div>
    </div>


</div>
<br>
<div class="row">
    <div class="col">
        <div class="input-group">
            <input class="form-control" type="text" maxlength="9" placeholder="CEP*" onkeypress="return onlyNumber()" name="cep" id="cep">
        </div>
    </div>

    <div class="col">
        <div class="input-group">
            <input class="form-control" type="url" placeholder="URL para foto de perfil" name="link_photo" id="link_photo">
        </div>
    </div>
</div>
<br><br>
<h4>Agora responda este questionário:</h4>
<div class="row mt-4">
    <div class="col">
        <label>Você possui internet? *</label>
        <div class="custom-control custom-radio">
            <input type="radio" id="true" value="1" name="internet" class="custom-control-input">
            <label class="custom-control-label" for="true">Sim</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" id="false" value="0" name="internet" class="custom-control-input">
            <label class="custom-control-label" for="false">Não</label>
        </div>

    </div>
    
    <div class="col">
        <label>Se sim, qual o nome da provedora de internet? <small>(Operadora)</small></label>
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Provedora de internet" name="isp_name" id="isp_name">
        </div>
    </div>

    <div class="col">
        <label>Nos informe a configuração de sua internet (velocidade, outros dados técnicos)? <small>Ignore caso você não possua internet!</small></label>
        <div class="input-group">
            <textarea placeholder="Digite aqui" class="form-control" style="resize: inherit; height: 79px;" name="isp_configs" id="isp_configs" cols="30" rows="10"></textarea>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col">
        <label>Quantos computadores desktop você possui em sua casa? *</label>
        <div class="custom-control custom-radio">
            <input type="radio" id="0" value="0" name="qt_pc_desktop" class="custom-control-input">
            <label class="custom-control-label" for="0">0</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" id="1" value="1" name="qt_pc_desktop" class="custom-control-input">
            <label class="custom-control-label" for="1">1</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" id="2" value="2" name="qt_pc_desktop" class="custom-control-input">
            <label class="custom-control-label" for="2">2</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" id="+3" value="+3" name="qt_pc_desktop" class="custom-control-input">
            <label class="custom-control-label" for="+3">3 ou mais</label>
        </div>


    </div>
    <div class="col">
        <label>Nos informe as especificações técnicas de seu (ou de seus) Computadores desktops: <small>Ignore caso você não possua!</small></label>
        <div class="input-group">
            <textarea placeholder="Digite aqui" class="form-control" style="resize: inherit; height: 79px;" name="pc_desktop_configs" id="pc_desktop_configs" cols="30" rows="10"></textarea>
        </div>
    </div>
</div>


<div class="row mt-5">
    <div class="col">
        <label>Quantos computadores notebook você possui em sua casa? *</label>
        <div class="custom-control custom-radio">
            <input type="radio" id="0_not" value="0" name="qt_pc_notebook" class="custom-control-input">
            <label class="custom-control-label" for="0_not">0</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" id="1_not" value="1" name="qt_pc_notebook" class="custom-control-input">
            <label class="custom-control-label" for="1_not">1</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" id="2_not" value="2" name="qt_pc_notebook" class="custom-control-input">
            <label class="custom-control-label" for="2_not">2</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" id="+3_not" value="+3" name="qt_pc_notebook" class="custom-control-input">
            <label class="custom-control-label" for="+3_not">3 ou mais</label>
        </div>


    </div>
    <div class="col">
        <label>Nos informe as especificações técnicas de seu (ou de seus) Computadores notebooks: <small>Ignore caso você não possua!</small></label>
        <div class="input-group">
            <textarea placeholder="Digite aqui" class="form-control" style="resize: inherit; height: 79px;" name="pc_notebook_configs" id="pc_notebook_configs" cols="30" rows="10"></textarea>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col">
        <label>Quantos smartphones você possui? *</label>
        <div class="custom-control custom-radio">
            <input type="radio" id="0_sm" value="0" name="qt_sm_phone" class="custom-control-input">
            <label class="custom-control-label" for="0_sm">0</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" id="1_sm" value="1" name="qt_sm_phone" class="custom-control-input">
            <label class="custom-control-label" for="1_sm">1</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" id="2_sm" value="2" name="qt_sm_phone" class="custom-control-input">
            <label class="custom-control-label" for="2_sm">2</label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" id="+3_sm" value="+3" name="qt_sm_phone" class="custom-control-input">
            <label class="custom-control-label" for="+3_sm">3 ou mais</label>
        </div>

    </div>
    <div class="col">
        <label>Nos informe as especificações técnicas de seu (ou de seus) smartphone(s): <small>Ignore caso você não possua!</small></label>
        <div class="input-group">
            <textarea placeholder="Digite aqui" class="form-control" style="resize: inherit; height: 79px;" name="sm_phone_configs" id="sm_phone_configs" cols="30" rows="10"></textarea>
        </div>
    </div>
</div>

<h4 class="mt-5">Questões de renda:</h4>
<div class="row mt-4 flex-center">

    <div class="col">
        <label for="renda_per_capita">Qual a renda per capita de sua família?* <small>Soma das rendas individuas dividida pelo número de pessoas de sua casa</small></label>
        <div class="input-group">
            <input class="form-control" onkeypress="return onlyNumber()" type="text" placeholder="Renda per capita*" name="renda_per_capita" id="renda_per_capita">
        </div>
    </div>

    <div class="col">
        <label for="qtd_in_house">Quantas pessoas vivem atualmente em sua casa?*</label>
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Nº Habitantes em sua casa*" name="qtd_in_house" id="qtd_in_house">
        </div>
    </div>

    

    <div class="col">
        <label for="renda_ind">Qual a sua renda individual?*</label>
        <div class="input-group">
            <input class="form-control" onkeypress="return onlyNumber()" type="text" placeholder="Renda Individual*" name="renda_ind" id="renda_ind">
        </div>
    </div>
</div>

<h4 class="mt-5">Motivo do pedido:</h4>
<p>Escreva um pouco abaixo, sobre o porquê você necessita do auxílio emergencial</p>
<div class="row mt-4">
    <div class="col">
        <textarea placeholder="Escreva aqui" class="form-control" style="resize: inherit; height: 79px;" name="reason" id="reason" cols="30" rows="10"></textarea>
    </div>

</div>
