<?php

function pr($arr)
{
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}


function isLogin() {
    return (isset($_SESSION['authUser']) && !empty($_SESSION['authUser']));
}
