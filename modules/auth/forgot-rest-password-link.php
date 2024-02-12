<?php 
include '../common/header.php';

// if user is already loggedin then we are redirecting to dashboard
if(isLogin()){
    header('location:/modules/dashboard');
}

include_once "./UserClass.php";
 
$email = base64_decode($_REQUEST['email']);
$forgotKey = $_REQUEST['forgot-key'];

// fetch the current user
$userObject = new UserClass();

// if something has touched those encrypted value
$user = $userObject->getUserByEmail($email);
if(empty($user)){
    die("This link has been expired, use <a href='forgot-password.php'>forgot password</a> link to regenerate");
}

if($user['forgot_key'] != $forgotKey){
    die("This link has been expired, use <a href='forgot-password.php'>forgot password</a> link to regenerate");
}

// if user is here he is valid one 


?>  
<h1>Enter a New Password to update</h1>
<form action="authProcessor.php" method="post">
    <input type="password" name="password" placeholder="enter a new password to update">
    <input name="_action" value="update_user_password_with_forgot_key" type="hidden">
    <input name="forgot_key" value="<?php echo $forgotKey ?>" type="hidden">
    <input name="email" value="<?php echo $_REQUEST['email'] ?>" type="hidden">
    <button type="submit">Update Password</button>
   <div class="error">
    <?php 
        if(!empty($_GET) && isset($_GET['err'])){
            echo $_GET['err'];
        }
        ?>
   </div>
</form>
<a href="login.php">To back</a>
<?php
include '../common/footer.php';