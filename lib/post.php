<?php
if(!empty($_POST['submit'])) {
    $function = new Functions;
    $user_id = $_SESSION['user_id'];
    $text = $_POST['chat_input'];
    $function->chat_post($user_id, $text);
}