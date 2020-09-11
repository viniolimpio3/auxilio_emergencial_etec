<h4>Primeiro, preencha seus documentos</h4>  
<p style="color: red;">* Campo obrigatório</p>  
<div class="row">
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
<div class="row">
    <div class="col">
        <label>Você possui internet?</label>
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

        <label>Se sim, nos informe sua configuração (velocidade)?</label>
        <div class="input-group">
            <textarea style="resize: vertical;" name="isp_configs" id="isp_configs" cols="30" rows="10"></textarea>
            <input class="form-control" type="text" placeholder="Digite aqui" name="isp_configs" id="isp_configs">
        </div>
    </div>
</div>