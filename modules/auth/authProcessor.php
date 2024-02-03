<?php
session_start();

if(empty($_POST))
{
    die('Empty Request not access directly and only Post is work');
}

echo"<pre>";
print_r($_POST);
print_r($_GET);
print_r($_REQUEST);
print_r($_SESSION);
?>