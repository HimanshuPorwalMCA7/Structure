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


function generateRandomAlphanumeric() {
    // Define the characters allowed in the alphanumeric string
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    // Get the length of the character string
    $charactersLength = strlen($characters);
    // Initialize the variable to store the generated code
    $randomString = '';
    // Loop to generate random characters
    for ($i = 0; $i < 6; $i++) {
        // Append a random character from the character string to the random string
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    // Return the generated random alphanumeric string
    return $randomString;
}