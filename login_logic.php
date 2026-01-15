<?php
    // including files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    // logic
    if (isset($_POST['submit'])) {
        // declare variables
        $user_name = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $key = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!$user_name || !$key) {
            $_SESSION['login'] = "Fill in all spaces!!";
        } else {
            // check for user
            $select = "SELECT * FROM user where user_name='$user_name'";
            $result = mysqli_query($connection, $select);
            if (mysqli_num_rows($result) == 1) {
                // get details if found
                $user = mysqli_fetch_assoc($result);
                $real_password = $user['password'];
                // compare passwords
                if (password_verify($key, $real_password)) {
                    $_SESSION['user_id'] = $user['id'];
                    // check if user is an admin
                    if ($user['is_admin'] == 1) {
                        $_SESSION['i_am_admin'] = true;
                    }
                    header("location: " . root_url . "admin/index.php");
                    die();
                }
            } else {
                $_SESSION['login'] = "User not found";
            }
        }
        // redirect if there's any error
        if (isset($_SESSION['login'])) {
            header("location: " . root_url . "login.php");
            die();
        }
    } else {
        header("location: " . root_url . "login.php");
        die();
    }
    











?>
    
    
    
    
    

