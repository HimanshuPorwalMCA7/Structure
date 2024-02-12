<?php
//session_start();
include '../common/header.php';

if(isLogin())
{
    header('location:/modules/dashboard');
}


?>

<form action="authProcessor.php" method="post">
    <input type="text" name="email" placeholder="Email">
    <input name="_action" value="send_reset_password_link" type="hidden">
    <button type="submit">Send Reset Link</button>
    <div class="error">
        <?php
        if (!empty($_GET) && isset($_GET['err'])) {
            echo $_GET['err'];
        }
        ?>
    </div>
</form>

<a href="login.php"> To Back</a>

<?php
include '../common/footer.php';
?>
