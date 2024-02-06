<?php
include_once '../common/header.php';

if(!isLogin())
{
    header('location:/modules/auth/login.php');
}


include_once '../common/footer.php';

