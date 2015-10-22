<?php
class Message {

    public static function messenger() {
        //User ID from session
        $user_id = $_SESSION['user_id'];

        //Tabs
        $message_sql = db_query("SELECT * FROM message WHERE user_id = ".$user_id."");
        echo '<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">';
        $message_id = array();
        $i = 1;
        foreach($message_sql as $message) {
            $user_sql = db_select_row("SELECT * FROM user WHERE id = ".$message['recipient_id']);
            $item_class = ($i == 1) ? 'active' : '';
            echo '<li class="'.$item_class.'"><a href="#message'.$message['id'].'" data-toggle="tab">'.$user_sql['username'].'</a></li>';
            $message_id[] = $message['id'];
            $i++;
        }
        echo '</ul>';

        echo '<div id="my-tab-content" class="tab-content">';
        $i = 1;
        foreach($message_id as $message) {
            //Tab content
            $messages_sql = db_query("SELECT * FROM messages WHERE user_id = ".$user_id." AND message_id = ".$message."");
            $item_class = ($i == 1) ? 'tab-pane active' : 'tab-pane';
            echo '<div class="'.$item_class.'" id="message'.$message.'">';
            foreach($messages_sql as $messages) {
                $user_sql = db_select_row("SELECT * FROM user WHERE id = ".$messages['user_id']."");
                $date_create = date_create($messages['posted_time']);
                $date = date_format($date_create, 'm-d-Y g:i A');
                echo '<div class="post_body">';
                echo '<span class="post_posted_time">'.$date.'</span> ';
                echo '<span class="post_username">['.$user_sql['username'].']</span>';
                echo ': ';
                echo '<span class="post_text">'.$messages['message'].'</span>';
                echo '</div>';
            }
            echo '</div>';
            $i++;
        }
        echo '</div>';
    }

    public static function message_save($id, $user_id, $recipient_id, $message) {
        $user_id = mysqli_real_escape_string(db_connect(), $user_id);
        $recipient_id = mysqli_real_escape_string(db_connect(), $recipient_id);
        $message = mysqli_real_escape_string(db_connect(), $message);

        if(empty($id)) {
            db_query("INSERT INTO user (user_id, recipient_id, message) VALUES ('".$user_id."', '".$recipient_id."', '".$message."');");
        } else {
            db_query("UPDATE ".MYSQL_DB.".user SET user_id = '".$user_id."', recipient_id = '".$recipient_id."', message = '".$message."' WHERE user.id = ".$id.";");
        }
    }
}