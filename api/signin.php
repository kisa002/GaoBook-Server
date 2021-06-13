<?php
    include('database.php');

    $username = $_POST['username'];
    $password = $_POST['password'];

    signIn($username, $password);
?>