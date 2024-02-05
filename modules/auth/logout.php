<?php
session_start();
include '../common/header.php';
unset($_SESSION['userID']);
header('location:login.php');
?>