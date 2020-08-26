<?php
    if(!isset($_SESSION)) session_start();
    return isset($_SESSION['auth']) and $_SESSION['auth'] === 'logado' ? true : false;
?>