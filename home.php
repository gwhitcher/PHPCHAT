<div id="chat_frame">
    <?php
    include("lib/post.php");
    $function = new Functions;
    $function->chat();
    ?>
</div>

<?php if(!empty($_SESSION['user_id'])) { ?>
<div id="chat_post">
    <form method="post" accept-charset="utf-8" action="<?php echo BASE_URL; ?>/index.php">
        <div class="row">
            <div class="col-md-11"><input type="text" id="chat_input" name="chat_input" class="form-control" placeholder="Hello World..." required></div>
            <div class="col-md-1"><button type="submit" id="submit" name="submit" class="btn btn-primary">Submit</button></div>
        </div>
    </form>
</div>
<?php } else {
    echo '<p>Please <a href="'.BASE_URL.'/login">login</a> to post.';
}
?>

