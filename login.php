<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string(db_connect(), $_POST['inputEmail']);
    $password = mysqli_real_escape_string(db_connect(), $_POST['inputPassword']);
    $remember_me = mysqli_real_escape_string(db_connect(), $_POST['remember_me']);
    $user = new User;
    $user->login($email, $password, $remember_me);
}
?>
<div id="form-login">
    <form class="form-signin" enctype="multipart/form-data" method="post" accept-charset="utf-8" action="<?php echo BASE_URL; ?>/login">
        <h2 class="form-signin-heading">Please sign in</h2>

        <div class="form-group">
            <label for="inputEmail" class="sr-only">Email address</label>
            <input type="email" id="inputEmail" name="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        </div>

        <div class="form-group">
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required>
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="remember_me" name="remember_me" value="remember_me"> Remember me
                </label>
            </div>
        </div>

        <button class="btn btn-lg btn-primary btn-block" type="submit" id="submit" name="submit">Sign in</button>
    </form>
</div>