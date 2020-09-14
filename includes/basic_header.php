<?php
    if(!isset($_SESSION)) session_start();

    if(!isset($user))
        $user = isset($_SESSION['user']) ? $_SESSION['user'] :header('location:login.php');
?>
<div class="flex-between mb-3 ">
    <h3>Auxílio Emergencial <small>Aluno do Ensino Técnico: <?=$user->name ?></small></h3>
    <a href="doLogout.php" class="btn btn-danger">Sair</a>
</div>