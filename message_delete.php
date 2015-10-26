<?php
//Login check
$login_check = new User();
$login_check->prtctd();

$routing_uri = $_SERVER['REQUEST_URI'];
$message_id = explode("/", $routing_uri);
$message_id_clean = mysqli_real_escape_string(db_connect(), $message_id[4]);
$message_lookup = new Message();
$message_lookup->message_delete($message_id_clean);