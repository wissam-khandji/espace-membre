<?php

    session_start();
    session_unset();//Desactive la session
    session_destroy();//Detruit la session
    setcookie('auth', '', time()-1, '/', null, false, true); //detruit le cookie

    header('location: index.php');
    exit();



?>