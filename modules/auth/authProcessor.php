<?php
session_start();
include_once '../../core/constants.php';
include_once '../../core/helper.php';
include_once './AuthClass.php';
include_once './UserClass.php';
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
else if($_action == "process_register"){
    $userObject = new UserClass();
    $email =$_POST['email'];

    $user=$userObject->getUserByEmail($email);

    if(!empty($user))
    {
        header('location:/modules/auth/register.php?err=Hey'.$email.'already exist');
        exit;
    }
    $stmt = $userObject->createUser($_POST);
    if($userObject->rowCount($stmt))
    {
        header('location:/modules/auth/register.php?msg=Hey'.$_POST['full_name'].'your Account is successfully created but it login after approval');
    }
}
?>