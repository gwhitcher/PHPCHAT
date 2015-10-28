<?php
class Functions {

    public static function chat() {
        $posts_sql = db_select("SELECT * FROM log ORDER BY id DESC LIMIT 1000");
        echo '<div id="chat_body">';
            foreach($posts_sql as $post) {
                $user_sql = db_select_row("SELECT * FROM USER WHERE id = ".$post['user_id']);
                echo '<div class="post_body">';
                $date_create = date_create($post['posted_time']);
                $date = date_format($date_create, 'm-d-Y g:i A');
                echo '<span class="post_posted_time">'.$date.'</span> ';
                echo '<span class="post_username">['.$user_sql['username'].']</span>';
                echo ': ';
                echo '<span class="post_text">'.$post['text'].'</span>';
                echo '</div>';
            }
        echo '</div>';
    }

    public static function chat_post($user_id, $text) {
        $post_user_id = mysqli_real_escape_string(db_connect(), $user_id);
        $post_text = mysqli_real_escape_string(db_connect(), $text);
        $post_date = date("Y-m-d H:i:s");
        $post_sql = db_query("INSERT INTO log (user_id, text, posted_time) VALUES ('".$post_user_id."', '".$post_text."', '".$post_date."');");
        return $post_sql;
    }

    public static function install() {
        //Create log table
        $sql = "CREATE TABLE log (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
user_id VARCHAR(255),
text TEXT,
posted_time DATETIME
)";
        db_query($sql);

        //Create user table
        $sql = "CREATE TABLE user (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(255),
email VARCHAR(255),
password VARCHAR(255),
role INT(11),
ip_address VARCHAR(255)
)";
        db_query($sql);

        //Create ban table
        $sql = "CREATE TABLE ban (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
ip_address VARCHAR(255),
user_id VARCHAR(255)
)";
        db_query($sql);

        //Create message table
        $sql = "CREATE TABLE message (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
user_id INT(11),
recipient_id INT(11)
)";
        db_query($sql);

        //Create messages table
        $sql = "CREATE TABLE messages (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
message_id INT(11),
user_id INT(11),
message TEXT,
posted_time DATETIME
)";
        db_query($sql);

        //Insert new user into database.
        $user = new User();
        $username = 'Administrator'; //User login
        $email = 'admin@admin.com'; //User login
        $password = $user->encrypt_password('password'); //Encrypt password
        $role = 1; //Admin role
        db_query("INSERT INTO user (username, email, password, role) VALUES ('".$username."', '".$email."', '".$password."', '".$role."');");


        //Flash message and forward to home.
        $flash = new Flash();
        $flash->flash('flash_message', 'PHPCHAT installed.  Thank you for choosing PHPCHAT.');
        header("Location: ".BASE_URL."");
    }
}