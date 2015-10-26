<?php
//Login check
$login_check = new User();
$login_check->prtctd();

$functions = new User();
$routing_uri = $_SERVER['REQUEST_URI'];
$user_id = explode("/", $routing_uri);
$user_id_clean = mysqli_real_escape_string(db_connect(), $user_id[3]);
$user_role_clean = mysqli_real_escape_string(db_connect(), $user_id[4]);
$functions->role_change($user_id_clean, $user_role_clean);