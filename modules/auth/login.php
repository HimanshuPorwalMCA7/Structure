<?php
session_start();
include '../common/header.php';

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

<?php
include '../common/footer.php';
?>
