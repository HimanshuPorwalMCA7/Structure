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

    include_once "../../core/Emailer.php";

    $emailerObject = new Emailer();
    $emailerObject->sendEmail("Hey, Admin New Request for content Writer signup","Hey Admin, Please check the admin panel,there is a new request for Approval");
    $emailerObject->sendEmail("Hey, Thanks for Sign Up","Hey.  Please keep checking your email box and spam folder,ASAP WE will notify back");

    

    if($userObject->rowCount($stmt))
    {
        header('location:/modules/auth/register.php?msg=Hey'.$_POST['full_name'].'your Account is successfully created but it login after approval');
    }
}
else if($_action == "send_reset_password_link"){
    $userObject = new UserClass();
    // receving email address
    $email = $_POST['email'];

    // checking user exists or not
    $user = $userObject->getUserByEmail($email);

    // if exists then redirect back with error
    if(empty($user)){
        header('location:/modules/auth/forgot-password.php?err=Hey '.$email.', doesn\'t exists in our database!');
        exit;
    }

    // if they are here mean they are a valid user
    // save that forgot key
    $forgotKey = generateRandomAlphanumeric();
    $stmt = $userObject->runQuery("update ".DB_PREFIX."_users set forgot_key = :forgot_key where email=:email", [':forgot_key'=>$forgotKey,':email'=>$email]);
    if(!$userObject->rowCount($stmt)){
        header('location:/modules/auth/forgot-password.php?err=Hey there is an issue with the server, please try again!');
        exit;
    }

    $resetPasswordLink = SITE_URL."modules/auth/forgot-rest-password-link.php?forgot-key=$forgotKey&email=".base64_encode($user['email']);
    include_once "../../core/Emailer.php";    
    $emailerObject = new Emailer();
    // 3 second
    $emailerObject->sendEmail("Hey ".$user['first_name'].", here is your password reset link", "Hey ".$user['first_name'].", here is your password reset <a href='".$resetPasswordLink."'>link</a>");
    $base64encodeEmail = base64_encode($email);
    header('location:/modules/auth/forgot-password-thankyou.php?email='.$base64encodeEmail.'&err=Hey, we have sent one rest password link on your email, please check and do the neeful!');
    exit;
}
else if($_action == "update_user_password_with_forgot_key"){
    
    $email = base64_decode($_REQUEST['email']);
    $forgotKey = $_REQUEST['forgot_key'];

    // fetch the current user
    $userObject = new UserClass();

    // if something has touched those encrypted value
    $user = $userObject->getUserByEmail($email);
    if(empty($user)){
        header('location:/modules/auth/forgot-password.php?err=Sorry that link was expired, please regenerate!');
        exit;
    }

    if($user['forgot_key'] != $forgotKey){
        header('location:/modules/auth/forgot-password.php?err=Sorry that link was expired, please regenerate!');
        exit;
    }

    $password = $_REQUEST['password'];

    // if user is coming upto here, means nobody touched our hash codes
    // run update command for password and send him on login page with success message
    $stmt = $userObject->runQuery("update ".DB_PREFIX."_users set password = :password, forgot_key=:forgot_key_null where email=:email and forgot_key=:forgot_key", [':password'=>$password,':forgot_key'=>$forgotKey,':email'=>$email, ':forgot_key_null'=>null]);

    if(!$userObject->rowCount($stmt)){
        header('location:/modules/auth/forgot-password.php?err=Hey, unable to update password please try again');
        exit;
    }
    header('location:/modules/auth/login.php?err=Hey, password updated successfully, please login');
    exit;
}

else if($_action == "process_basic_details"){
    
    $userObject = new UserClass();
    $mother_name =$_POST['mother_name'];
    $father_name = $_POST['father_name'];
    $dob=$_POST['dob'];
    $location = $_POST['location'];
    if ($location === "India")
    {
        $stage = 1; 
    }
    else{
        $stage = 2; 
    }
    
    $result = $userObject->updateStage($stage);
    if ($result) {
        $_SESSION['auth_user']['stage'] = $stage;
        echo "Stage updated successfully.";
    } else {
        echo "Error updating stage.";
    }
    $stmt = $userObject->basic_details($_POST);
    
    

}
else if($_action == "process_aadhar_pan_details"){
    $userObject = new UserClass();
    $aadhar_card =$_POST['aadhar_card'];
    $pan_card = $_POST['pan_card'];
    $stage = 2; 
    $result = $userObject->updateStage($stage);
    if ($result) {
        $_SESSION['auth_user']['stage'] = $stage;
        echo "Stage updated successfully.";
    } else {
        echo "Error updating stage.";
    }
    $stmt = $userObject->aadhar_pan_details($_POST);
}
else if($_action == "process_education_details"){
    $userObject = new UserClass();
    $tenth = $_POST['tenth'];
    $twelfth = $_POST['twelfth'];
    $graduation = $_POST['graduation'];
    $stage = 3; 
    $result = $userObject->updateStage($stage);
    if ($result) {
        $_SESSION['auth_user']['stage'] = $stage;
        echo "Stage updated successfully.";
    } else {
        echo "Error updating stage.";
    }
    $stmt = $userObject->education_details($_POST);
}
// else if($_action == "process_final_submission"){
//     $userObject = new UserClass();
//     $user_id = $_SESSION['authUser']['id'];
//     $mother_name = $basic_details['mother_name'];
//     $father_name = $basic_details['father_name'];
//     $dob = $basic_details['dob'];
//     $location = $basic_details['location'];
//     $aadhar_card = $aadhar_pan_details['aadhar_card'] ?? NULL;
//     $pan_card = $aadhar_pan_details['pan_card'] ?? NULL;
//     $tenth = $education_details['tenth'];
//     $twelfth = $education_details['twelfth'];
//     $graduation = $education_details['graduation'];
//     $stage = 4; 
//     $result = $userObject->updateStage($stage);
//     if ($result) {
//         $_SESSION['auth_user']['stage'] = $stage;
//         echo "Stage updated successfully.";
//     } else {
//         echo "Error updating stage.";
//     }
//     $stmt = $userObject->final_submission($_POST);
// }

?>

