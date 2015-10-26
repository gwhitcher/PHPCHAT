<?php
//Login check
$login_check = new User();
$login_check->prtctd();

$routing_uri = $_SERVER['REQUEST_URI'];
$user_id = explode("/", $routing_uri);
$user_id_clean = mysqli_real_escape_string(db_connect(), $user_id[3]);
$functions = new User();
$functions->ban($user_id_clean);