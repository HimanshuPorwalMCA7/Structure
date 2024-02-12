

<?php
//session_start();
include '../common/header.php';

if(!isLogin())
{
    header('location:/modules/dashboard');
}


?>
<br>
Thank you Your form is Submitted