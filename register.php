<?php
if(!empty($_POST)) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    new User();
    User::user_save('', ''.$username.'', ''.$email.'', ''.$password.'', ''.$role.'');
}
?>
<div id="form-register">
    <h2>Register</h2>
    <form method="post" accept-charset="utf-8" action="<?php echo BASE_URL; ?>/register">
        <div class="form-group">
            <label id="username" for="username" class="sr-only">Username:</label>
            <input id="username" name="username" type="text" class="form-control" placeholder="Username" required autofocus>
        </div>

        <div class="form-group">
            <label id="email" for="email" class="sr-only">Email:</label>
            <input id="email" name="email" type="text" class="form-control" placeholder="Email" required>
        </div>

        <div class="form-group">
            <label id="password" for="password" class="sr-only">Password:</label>
            <input id="password" name="password" type="password" class="form-control" placeholder="Password" required>
        </div>

        <button class="btn btn-lg btn-primary btn-block" type="submit" id="submit" name="submit">Register</button>
    </form>
</div>