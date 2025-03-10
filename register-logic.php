<?php
require_once('files/functions.php');
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$password_1 = trim($_POST['password_1']);
$phone_number = trim($_POST['phone_number']);
$first_name = trim($_POST['first_name']);
$last_name = trim($_POST['last_name']);

if($password != $password_1){
    alert('danger','Passwords did not match');
    header('Location: login.php');
    die();
}

$sql = "SELECT * FROM users WHERE email = '{$email}'";
$res = $conn->query($sql);

if( $res->num_rows > 0) {
    alert('danger','User with same email already exist');
    header('Location: login.php');
    die();
}

$password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (
    first_name,
    last_name,
    phone_number,
    email,
    password,
    user_type
) Values(
    '{$first_name}',
    '{$last_name}',
    '{$phone_number}',
    '{$email}',
    '{$password}',
    'customer'

)";

if($conn->query($sql)){
    login_user($email,$password);
    alert('success','Account created successfully');
    header('Location: account.php');
    die();
} else{
    alert('danger','Failed to create account');
    header('Location: login.php');
    die();
}
