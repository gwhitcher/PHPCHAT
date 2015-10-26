<?php
//Login check
$login_check = new User();
$login_check->prtctd();

$routing_uri = $_SERVER['REQUEST_URI'];
$message_id = explode("/", $routing_uri);
$message_id_clean = mysqli_real_escape_string(db_connect(), $message_id[3]);
$user_id = $_SESSION['user_id'];
$message = ''.$_SESSION['username'].' initiated a conversation.';
$message_lookup = new Message();
$message_lookup->message_save($user_id, $message_id_clean, $message);