<?php
    // including files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    // logic
    if (isset($_POST['submit'])) {
        // declare variables
        $user_name = (string) $_POST['username'];
        $key = (string) $_POST['password'];
        // ip address function
        function get_ip_address() {
            // declare ip_address
            $ip_address = '';
            // check various headers for potential ip address
            if (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip_address = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }  elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip_address = $_SERVER['REMOTE_ADDR'];
            } else {
                $ip_address = 'UNKNOWN';
            }
            return $ip_address;
        }
        // ip address
        $user_ip = get_ip_address();
        // VALIDATE
        if (!$user_name || !$key) {
            $_SESSION['login'] = "Fill in all spaces!!";
            // insert login attempt
            $attempt = mysqli_prepare($connection, "INSERT INTO login_log (ip_address) VALUES (?)");
            mysqli_stmt_bind_param($attempt, "s", $user_ip);
            mysqli_stmt_execute($attempt);
        } else {
            // check for user
            $select = mysqli_prepare($connection, "SELECT * FROM user where user_name=?");
            mysqli_stmt_bind_param($select, "s", $user_name);
            mysqli_stmt_execute($select);
            $result = mysqli_stmt_get_result($select);
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
                    // insert login attempt
                    $success = mysqli_prepare($connection, "INSERT INTO login_log (log_status, ip_address) VALUES (?, ?)");
                    // status
                    $success_status = "success";
                    mysqli_stmt_bind_param($success, "ss", $success_status ,$user_ip);
                    mysqli_stmt_execute($success);
                    header("location: " . root_url . "admin/index.php");
                    die();
                }
            } else {
                $_SESSION['login'] = "User not found";
                // insert login attempt
                $failure = mysqli_prepare($connection, "INSERT INTO login_log (log_status, ip_address) VALUES (?, ?)");
                // status
                $failure_status = "failure";
                mysqli_stmt_bind_param($failure, "ss", $failure_status ,$user_ip);
                mysqli_stmt_execute($failure);
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
    
    
    
    
    

