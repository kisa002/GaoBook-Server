<?php
    include 'database.php';

    $nickname = $_POST['nickname'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    signUp($nickname, $username, $password);
?>