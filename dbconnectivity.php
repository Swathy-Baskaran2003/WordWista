<?php
session_start();

$db = mysqli_connect('localhost', 'root', '', 'login');
if(!$db)
    die("connection fail".mysqli_connect_error());


?>