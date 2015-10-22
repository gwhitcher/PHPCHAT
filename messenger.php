<?php
if(!empty($_POST)) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    new Message();
    Message::message_save('', ''.$username.'', ''.$email.'', ''.$password.'');
}
?>
<h2>Messenger for: <?php echo $_SESSION['username'];?></h2>
<?php
$messenger = new Message();
$messenger->messenger();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#tabs').tab();
    });
</script>
