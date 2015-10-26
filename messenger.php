<?php
//Login check
$login_check = new User();
$login_check->prtctd();
?>
<h2>Messenger for: <?php echo $_SESSION['username'];?></h2>
<?php
$messenger = new Message();
$messenger->messenger();