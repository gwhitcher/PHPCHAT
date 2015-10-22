<?php
$routing_uri = $_SERVER['REQUEST_URI'];
$category_id = explode("/", $routing_uri);
$username = mysqli_real_escape_string(db_connect(), $category_id[3]);
$user_lookup = new User();
$user = $user_lookup->user_load_username($username);
?>
<h2><?php echo $user['username']; ?></h2>
<?php
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