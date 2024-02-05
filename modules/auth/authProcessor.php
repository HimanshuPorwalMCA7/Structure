<?php
session_start();
include_once '../../core/constants.php';
include_once '../../core/helper.php';
include_once './AuthClass.php';
if(empty($_POST))
{
    die('Empty Request not access directly and only Post is work');
}

if(!isset($_POST['_action'])){
    die("Invalid_action value!");
}

$_action = $_POST['_action'];

if($_action == "process_login"){
    $authObject = new AuthClass();
    $authObject->verifyLogin($_POST);
    $user = $authObject->getCurrentUser();
    if(!$user){
        header('location:/modules/auth/login.php?err=Sorry invalid username and password');
        exit;
    }
    if($user['status']==2)
    {
        header('location:/modules/auth/login.php?err=Hey'.$user['first_name'].' is correct  username and password but your account in under review');
        exit;
    }
    
        $authObject->createSession();
        header('location:/modules/dashboard');
}
?>