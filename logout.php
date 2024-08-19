<?php
session_start();

    $_SESSION['loggedin'] = false;
    session_destroy();
    header('refresh:0;url=login.php');
    exit;

?>