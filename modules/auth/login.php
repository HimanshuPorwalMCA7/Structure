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
    <input type="password" name="password" placeholder="Password">
    <input name="_action" value="process_login" type="hidden">
    <button type="submit">Login</button>
    <div class="error">
        <?php
        if (!empty($_GET) && isset($_GET['err'])) {
            echo $_GET['err'];
        }
        ?>
    </div>
</form>

<a href="register.php"> Create New User</a>

<?php
include '../common/footer.php';
?>
