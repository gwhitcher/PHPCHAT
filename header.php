<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PHPCHAT</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link href="<?php echo BASE_URL; ?>/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo BASE_URL; ?>/css/styles.css" rel="stylesheet" />
    <script src="<?php echo BASE_URL; ?>/js/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo BASE_URL; ?>/js/default.js" type="text/javascript"></script>
</head>

<body>

<?php $flash = new Flash(); ?>
<?php $flash->flash('flash_message'); ?>

<div class="container-fluid">

    <header id="header">
        <h1>PHPCHAT</h1>
    </header>

    <a class="btn btn-primary" href="<?php echo BASE_URL; ?>">Chat</a>
    <?php if(empty($_SESSION['user_id'])) { ?>
    | <a class="btn btn-primary" href="<?php echo BASE_URL; ?>/login">Login</a>
    | <a class="btn btn-primary" href="<?php echo BASE_URL; ?>/register">Register</a>
    <?php } else { ?>
    | <a class="btn btn-primary" href="<?php echo BASE_URL; ?>/profile/<?php echo $_SESSION['username']; ?>">Profile</a>
    | <a class="btn btn-primary" href="<?php echo BASE_URL; ?>/messenger">Messenger</a>
    | <a class="btn btn-primary" href="<?php echo BASE_URL; ?>/logout">Logout</a>
    <?php } ?>