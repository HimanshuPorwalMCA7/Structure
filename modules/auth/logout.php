<?php
session_start();
unset($_SESSION['authUser']);
header('location:login.php');
?>