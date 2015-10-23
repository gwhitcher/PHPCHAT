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
            echo '<li class="'.$item_class.'"><a id="'.$message['id'].'" href="#message'.$message['id'].'" data-toggle="tab">'.$user_sql['username'].' <span id="message_delete'.$message['id'].'">&times;</span></a></li>';
            $message_id[] = $message['id'];
            $i++;
        }
        echo '</ul>';

        echo '<div id="messenger_body">';
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
        echo '</div>';

        //Submit message form
        $message_id = '';
        if(!empty($_POST)) {
            $function = new Message();
            $message_id = $_POST['message_id'];
            $user_id = $_SESSION['user_id'];
            $text = $_POST['chat_input'];
            $function->message_post($message_id, $user_id, $text);
        }

        echo '<form method="post" accept-charset="utf-8" id="chat_form" name="chat_form" action="messenger#'.$message_id.'">';
        echo '<div class="row">';
        echo '<select id="message_id" name="message_id" style="display: none;">';
        foreach($message_sql as $message) {
            $user_sql = db_select_row("SELECT * FROM user WHERE id = ".$message['recipient_id']."");
            echo '<option id="'.$message['id'].'" value="'.$message['id'].'">'.$user_sql['username'].'</option>';
        }
        echo '</select>';
        echo '<div class="col-md-11"><input type="text" id="chat_input" name="chat_input" class="form-control" placeholder="Hello World..."></div>';
        echo '<div class="col-md-1"><button type="submit" id="submit" name="submit" class="btn btn-primary">Submit</button></div>';
        echo '</div>';
        echo '</form>';

        //Javascript reload for each message
        $message_sql = db_query("SELECT * FROM message WHERE user_id = ".$user_id."");

        echo '<script type="text/javascript">';
        foreach($message_sql as $message) {
            //Reload messages
            echo "$(document).ready(
    function() {
        setInterval(function() {
            $('#message".$message['id']."').load('messenger' +  ' #message".$message['id']."');
        }, 3000); //3 seconds
    });";
            //Make X into link to delete messages
            echo '$(document).ready(function(){
    $("#message_delete'.$message['id'].'").click(function(){
        var r = confirm("Are you sure you want to delete this message?");
        if (r == true) {
        window.location.href = "'.BASE_URL.'/message/delete/'.$message['id'].'";
    } else {
        alert("The message was not deleted");
    }
    return false;
    });
});';
        }
        echo "";
        echo '</script>';
    }

    public static function message_post($message_id, $user_id, $message) {
        $message_id = mysqli_real_escape_string(db_connect(), $message_id);
        $message_user_id = mysqli_real_escape_string(db_connect(), $user_id);
        $message_text = mysqli_real_escape_string(db_connect(), $message);
        $message_date = date("Y-m-d H:i:s");
        $message_sql = db_query("INSERT INTO messages (message_id, user_id, message, posted_time) VALUES ('".$message_id."', '".$message_user_id."', '".$message_text."', '".$message_date."');");
        return $message_sql;
    }

    public static function message_save($user_id, $recipient_id, $message) {
        $user_id = mysqli_real_escape_string(db_connect(), $user_id);
        $recipient_id = mysqli_real_escape_string(db_connect(), $recipient_id);
        $message = mysqli_real_escape_string(db_connect(), $message);

        $message_lookup = db_select("SELECT * FROM message WHERE user_id = ".$user_id." AND recipient_id = ".$recipient_id."");
        if(empty($message_lookup)) {
            db_query("INSERT INTO message (user_id, recipient_id) VALUES ('".$user_id."', '".$recipient_id."');");
            $last_id =  mysqli_insert_id(db_connect());
            db_query("INSERT INTO messages (message_id, user_id, message) VALUES ('".$last_id."', '".$user_id."', '".$message."');");
            $flash = new Flash();
            $flash->flash('flash_message', 'Message created!');
            header("Location: ".BASE_URL.'/messenger#messenger'.$last_id);
        }
    }

    public static function message_delete($id) {
        //User ID from session
        $user_id = $_SESSION['user_id'];

        if(empty($id)) {
            $flash = new Flash();
            $flash->flash('flash_message', 'Message does not exist!', 'warning');
            header("Location: ".BASE_URL.'/messenger');
        } else {
            db_query("DELETE FROM message WHERE id = ".$id." AND user_id = ".$user_id);
            db_query("DELETE FROM messages WHERE message_id = ".$id." AND user_id = ".$user_id);
            $flash = new Flash();
            $flash->flash('flash_message', 'Message deleted!');
            header("Location: ".BASE_URL.'/messenger');
        }
    }
}