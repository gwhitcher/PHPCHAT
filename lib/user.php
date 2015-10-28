<?php
class User {

    public function __construct() {
    }

    public static function login($email, $password, $remember_me) {
        $login = db_select_row("SELECT * FROM user where email ='".$email."'");
        if($email == $login['email'] AND hash_equals($login['password'], crypt($password, $login['password']))) {
            if(!empty($remember_me)) {
                $params = session_get_cookie_params();
                setcookie(session_name(), $_COOKIE[session_name('PHPCHAT')], time() + 60*60*24*30, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
            }
            $_SESSION["user_id"] = $login['id'];
            $_SESSION["username"] = $login['username'];
            $_SESSION["email"] = $login['email'];
            $_SESSION["role"] = $login['role'];
            //Update IP on login
            db_query("UPDATE ".MYSQL_DB.".user SET ip_address = '".$_SERVER['REMOTE_ADDR']."' WHERE user.id = ".$login['id'].";");
            $flash = new Flash();
            $flash->flash('flash_message', 'Logged in!');
            header("Location: ".BASE_URL);
        } else {
            $flash = new Flash();
            $flash->flash('flash_message', 'Username or password incorrect!', 'danger');
            header("Location: ".BASE_URL."/login");
        }
    }

    public static function logout() {
        $flash = new Flash();
        $flash->flash('flash_message', 'Successfully logged out!');
        $_SESSION = array();
        session_destroy();
        unset($_COOKIE['PHPCHAT']);
    }

    public static function prtctd() {
        $routing_uri = $_SERVER['REQUEST_URI'];
        if(empty($_SESSION['user_id'])) {
            $flash = new Flash();
            $flash->flash('flash_message', 'You are not authorized to view this page!', 'danger');
            header("Location: ".BASE_URL."/login");
        } elseif(strpos($routing_uri,'/login') !== FALSE) {
            //Keeps login page free from protection.
        }
    }

    public static function ban($id) {
        if($_SESSION['role'] == 1) {
            $user = db_select_row("SELECT * FROM user WHERE id = '".$id."'");
            db_query("INSERT INTO ban (ip_address, user_id) VALUES ('".$user['ip_address']."', '".$user['id']."');");
            $flash = new Flash();
            $flash->flash('flash_message', 'User '.$user['username'].' successfully banned!');
            header("Location: ".BASE_URL);
        } else {
            $flash = new Flash();
            $flash->flash('flash_message', 'You are not authorized to view this page!', 'danger');
            header("Location: ".BASE_URL);
        }
    }

    public static function ban_check() {
        $banned_users = db_query("SELECT * FROM ban");
        if(empty($banned_users)) { $banned_users = array(); }
        $ip_addresses = array();
        $user_ids = array();
        foreach($banned_users as $banned_user) {
            $ip_addresses[] = $banned_user['ip_address'];
            $user_ids[] = $banned_user['user_id'];
        }
        if(empty($_SESSION['user_id'])) {
            $_SESSION['user_id'] = array();
        }
        if (in_array($_SERVER['REMOTE_ADDR'], $ip_addresses) OR in_array($_SESSION['user_id'], $user_ids)) {
            header("location:http://www.google.com/");
            exit();
        }
    }

    public static function role_change($user_id, $role_id) {
        if($_SESSION['role'] == 1) {
            if($role_id == 1) {
                $role = 'Admin';
            } else {
                $role = 'User';
            }
            $user = db_select_row("SELECT * FROM user WHERE id = '".$user_id."'");
            db_query("UPDATE ".MYSQL_DB.".user SET role = '".$role_id."' WHERE user.id = ".$user['id'].";");
            $flash = new Flash();
            $flash->flash('flash_message', 'User '.$user['username'].' successfully changed to '.$role.'!');
            header("Location: ".BASE_URL);
        } else {
            $flash = new Flash();
            $flash->flash('flash_message', 'You are not authorized to view this page!', 'danger');
            header("Location: ".BASE_URL);
        }
    }

    public static function user_load($id) {
        if(empty($id)) {
            $user = array();
            $flash = new Flash();
            $flash->flash('flash_message', 'User does not exist!', 'danger');
            header("Location: ".BASE_URL.'/404');
        } else {
            $user = db_select_row("SELECT * FROM user where id ='".$id."'");
            if(empty($user)) {
                $flash = new Flash();
                $flash->flash('flash_message', 'User does not exist!', 'danger');
                header("Location: ".BASE_URL.'/404');
            }
        }
        return $user;
    }

    public static function user_load_username($username) {
        $username_lookup = db_select_row("SELECT * FROM user where username ='".$username."'");
        if(empty($username) OR $username_lookup <= 0) {
            $flash = new Flash();
            $flash->flash('flash_message', 'User does not exist!', 'danger');
            header("Location: ".BASE_URL);
        }
        return $username_lookup;
    }

    public static function user_save($id, $username, $email, $password, $role) {
        $user_username = mysqli_real_escape_string(db_connect(), $username);
        $user_email = mysqli_real_escape_string(db_connect(), $email);

        //Validation check
        $username_lookup = db_select("SELECT * FROM user WHERE username = '".$user_username."'");
        if(count($username_lookup) > 0) {
            $flash = new Flash();
            $flash->flash('flash_message', 'Username or email already exists!', 'danger');
            header("Location: ".BASE_URL.'/register/');
        }
        $email_lookup = db_select("SELECT * FROM user WHERE email = '".$user_email."'");
        if(count($email_lookup) > 0) {
            $flash = new Flash();
            $flash->flash('flash_message', 'Username or email already exists!', 'danger');
            header("Location: ".BASE_URL.'/register/');
        }

        //Password security.
        $user_password = mysqli_real_escape_string(db_connect(), $password);
        $user = new User();
        $hash_password = $user->encrypt_password($user_password);

        $user_ip_address = $_SERVER['REMOTE_ADDR'];
        $user_role = mysqli_real_escape_string(db_connect(), $role);
        if(empty($id)) {
            db_query("INSERT INTO user (username, email, password, role, ip_address) VALUES ('".$user_username."', '".$user_email."', '".$hash_password."', '".$user_role."', '".$user_ip_address."');");
            $flash = new Flash();
            $flash->flash('flash_message', 'User created!');
            header("Location: ".BASE_URL.'/');
        } else {
            db_query("UPDATE ".MYSQL_DB.".user SET username = '".$user_username."', password = '".$hash_password."', role = '".$user_role."', ip_address = '".$user_ip_address."' WHERE user.id = ".$id.";");
            $flash = new Flash();
            $flash->flash('flash_message', 'User updated!');
            header("Location: ".BASE_URL.'/');
        }
    }

    public static function user_delete($id) {
        if(empty($id)) {
            $flash = new Flash();
            $flash->flash('flash_message', 'User does not exist!', 'danger');
            header("Location: ".BASE_URL.'/admin/');
        } else {
            db_query("DELETE FROM user WHERE id = ".$id);
            $chat_sql = db_select("SELECT * FROM log WHERE user_id = ".$id);
            $message_sql = db_select("SELECT * FROM message WHERE user_id = ".$id);
            $messages_sql = db_select("SELECT * FROM messages WHERE user_id = ".$id);
            foreach($chat_sql as $item) {
                db_query("DELETE FROM log WHERE id = ".$item['id']);
            }
            foreach($message_sql as $item) {
                db_query("DELETE FROM message WHERE id = ".$item['id']);
            }
            foreach($messages_sql as $item) {
                db_query("DELETE FROM messages WHERE id = ".$item['id']);
            }
            $flash = new Flash();
            $flash->flash('flash_message', 'User deleted!');
            header("Location: ".BASE_URL.'/');
        }
    }

    public function encrypt_password($password) {
        //A higher "cost" is more secure but consumes more processing power
        $cost = 10;

        // Create a random salt
        $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');

        // Prefix information about the hash so PHP knows how to verify it later.
        // "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
        $salt = sprintf("$2a$%02d$", $cost) . $salt;

        // Hash the password with the salt
        $hash_password = crypt($password, $salt);

        return $hash_password;
    }

}