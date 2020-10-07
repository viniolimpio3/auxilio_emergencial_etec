<?php if(!$user->answered_bank_q and $answered_questions): ?>
    <div class="alert alert-success">
        <?= $user->name ?>, falta pouco para terminar o cadastro no auxílio emergencial! 
    </div>
    
    <div id="helperDivImageLink" photo_link="<?= $user->link_photo ?>" hidden></div>

    <h2>Confirme seus dados para prosseguirmos!</h2>

    <form action="painel.php?update=yes" method="POST" >

        <p>Email:</p>
        <input class="form-control" readonly value="<?= $user->email ?>" type="email">  <br>

        <p>Imagem URL:</p>
        <div id="errImgURL" class="alert alert-danger" hidden> A URL: <?=$user->link_photo?> não é válida! Insira um link válido </div>
        <div class="profile_image" >
            <img src="<?=$user->link_photo?>" alt="image_<?=$user->name?>" id="profile_image" title="profile_<?=$user->name?>">
            <input class="inputs form-control" readonly type="url" class="form-control" value="<?=$user->link_photo?>" name="link_photo">
        </div><br>

        <p>Nome:</p>
        <input class="form-control inputs" readonly value="<?= $user->name ?>" name="name" type="text">  <br>

        <p>Escola:</p>
        <input class="form-control inputs" readonly value="<?= $user->school ?>" name="school" type="text">  <br>

        <p>RM:</p>
        <input  maxlength="6" onkeypress="return onlyNumber()" class="form-control inputs" name="rm" readonly value="<?= $user->rm ?>" type="text">  <br>

        <a href="bank_panel.php" id="conf" class="btn btn-success">Confirmar</a>

        <br>
        <button class="btn btn-dark mt-3" hidden type="submit" id="submit">Enviar</button>
        <br>
        <button class="btn btn-danger mt-3" hidden type="button" id="cancel">Cancelar</button>
        <br>
        <button class="btn btn-dark mr-3" type="button" id="alterar">Alterar</button>
        
    </form>
<?php endif; ?>