<?php
require_once('files/functions.php');
$email = trim($_POST['email']);
$password = trim($_POST['password']);

if(login_user($email,$password)){
    alert('success','Account logged in successfully');
    header('Location: account.php');
    die();
} else{
    alert('danger','You entered wrong email or password');
    header('Location: login.php');
}



