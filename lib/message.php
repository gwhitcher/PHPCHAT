<?php
class Message {

    public static function messenger() {
        //Tabs
        $message_sql = db_query("SELECT * FROM message WHERE user_id = ".$_SESSION['user_id']."");
        echo '<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">';
        foreach($message_sql as $message) {
            $user_sql = db_select_row("SELECT * FROM user WHERE id = ".$message['recipient_id']);
            echo '<li class="active"><a href="#message'.$message['id'].'" data-toggle="tab">'.$user_sql['username'].'</a></li>';
        }
        echo '</ul>';

        //Tab content
        echo '<div id="my-tab-content" class="tab-content">';
        foreach($message_sql as $message) {
            echo '<div id="chat_body">';

            echo '<div class="tab-pane active" id="#message'.$message['id'].'">';
            $user_sql = db_select_row("SELECT * FROM user WHERE id = ".$message['user_id']);
            echo '<div class="post_body">';
            $date_create = date_create($message['posted_time']);
            $date = date_format($date_create, 'm-d-Y g:i A');
            echo '<span class="post_posted_time">'.$date.'</span> ';
            echo '<span class="post_username">['.$user_sql['username'].']</span>';
            echo ': ';
            echo '<span class="post_text">'.$message['message'].'</span>';
            echo '</div>';

            echo '<div id="chat_post">
    <form method="post" accept-charset="utf-8" action="'.BASE_URL.'/messenger">
    <input type="hidden" id="message_id" name="message_id" value="'.$message['id'].'">
        <div class="row">
            <div class="col-md-11"><input type="text" id="chat_input" name="chat_input" class="form-control" placeholder="Hello World..." required></div>
            <div class="col-md-1"><button type="submit" id="submit" name="submit" class="btn btn-primary">Submit</button></div>
        </div>
    </form>
</div>';
            echo '</div>';

            echo '</div>';
        }
        echo '</div>';

        //Working tabs example
        /*
        echo '<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
        <li class="active"><a href="#red" data-toggle="tab">Red</a></li>
        <li><a href="#orange" data-toggle="tab">Orange</a></li>
        <li><a href="#yellow" data-toggle="tab">Yellow</a></li>
        <li><a href="#green" data-toggle="tab">Green</a></li>
        <li><a href="#blue" data-toggle="tab">Blue</a></li>
    </ul>
    <div id="my-tab-content" class="tab-content">
        <div class="tab-pane active" id="red">
            <h1>Red</h1>
            <p>red red red red red red</p>
        </div>
        <div class="tab-pane" id="orange">
            <h1>Orange</h1>
            <p>orange orange orange orange orange</p>
        </div>
        <div class="tab-pane" id="yellow">
            <h1>Yellow</h1>
            <p>yellow yellow yellow yellow yellow</p>
        </div>
        <div class="tab-pane" id="green">
            <h1>Green</h1>
            <p>green green green green green</p>
        </div>
        <div class="tab-pane" id="blue">
            <h1>Blue</h1>
            <p>blue blue blue blue blue</p>
        </div>
    </div>
</div>';
        */
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