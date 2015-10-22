<?php
$routing_host = $_SERVER['HTTP_HOST'];
$routing_uri = $_SERVER['REQUEST_URI'];

$page_name = '404.php'; //If no page_name replace empty value with 404 page.

//Install
if(strpos($routing_uri,'/install') !== FALSE) {
    $page_name = "install.php";
    return $page_name;
}

//Login
if(strpos($routing_uri,'/login') !== FALSE) {
    $page_name = "login.php";
    return $page_name;
}

//Logout
if(strpos($routing_uri,'/logout') !== FALSE) {
    $page_name = "logout.php";
    return $page_name;
}

//Register
if(strpos($routing_uri,'/register') !== FALSE) {
    $page_name = "register.php";
    return $page_name;
}

//Profile
if(strpos($routing_uri,'/profile') !== FALSE) {
    $page_name = "profile.php";
    return $page_name;
}

//Profile
if(strpos($routing_uri,'/messenger') !== FALSE) {
    $page_name = "messenger.php";
    return $page_name;
}

//Index
if(strpos($routing_uri,'/') !== FALSE) {
    $page_name = "home.php";
    return $page_name;
}