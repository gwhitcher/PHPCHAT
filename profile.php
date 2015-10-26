<?php
//Login check
$login_check = new User();
$login_check->prtctd();

$routing_uri = $_SERVER['REQUEST_URI'];
$category_id = explode("/", $routing_uri);
$username = mysqli_real_escape_string(db_connect(), $category_id[3]);
$user_lookup = new User();
$user = $user_lookup->user_load_username($username);

echo '<h2>'.$user['username'].'</h2>';

$message_sql = db_select("SELECT * FROM message WHERE user_id = ".$_SESSION['user_id']." recipient_id = ".$user['id']."");
if(empty($message_sql)) {
    echo '<a class="btn btn-success" href="'.BASE_URL.'/message/'.$user['id'].'">Send Message</a>';
} else {
    echo '<a class="btn btn-warning" href="#">View Existing Message</a>';
}
if($_SESSION['role'] == 1) {
    echo ' ';
    echo '<a class="btn btn-danger" href="'.BASE_URL.'/ban/'.$user['id'].'">Ban User</a>';
    echo ' ';
    if($user['role'] == 0) {
        echo '<a class="btn btn-warning" href="'.BASE_URL.'/role/'.$user['id'].'/1">Make Admin</a>';
    } else {
        echo '<a class="btn btn-warning" href="'.BASE_URL.'/role/'.$user['id'].'/0">Make User</a>';
    }
    echo ' ';
    echo '<a class="btn btn-danger" href="'.BASE_URL.'/user/delete/'.$user['id'].'"  onclick="return confirm(\'Are you sure?\')">Delete User</a>';
}
echo '<br /><br />';

$sql = db_query("SELECT * FROM log WHERE user_id = '".$user['id']."' ORDER BY id DESC");

echo '<table class="table table-bordered">';

echo '<tr>';
echo '<th>#</th>';
echo '<th>Date</th>';
echo '<th>Post</th>';
echo '</tr>';

foreach($sql as $post) {
    echo '<tr>';
    echo '<td>'.$post['id'].'</td>';
    $date_create = date_create($post['posted_time']);
    $date = date_format($date_create, 'm-d-Y g:i A');
    echo '<td>'.$date.'</td>';
    echo '<td>'.$post['text'].'</td>';
    echo '</tr>';
}

echo '</table>';