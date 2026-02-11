<?php
    // include files
    include "./configuration/constant.php";
    include "./configuration/database.php";
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
    // get id from url
    if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
        $id = (int) $_GET['id'];
    } else {
        // insert into unauthorized
        $unauthorized = mysqli_prepare($connection, "INSERT INTO unauthorized (ip_address) VALUES(?)");
        mysqli_stmt_bind_param($unauthorized, "s", $user_ip);
        mysqli_stmt_execute($unauthorized);
        // redirect
        header("location: " . root_url . "kick_you_out.php");
    }
    // logic
    if (isset($_POST['submit'])) {
        // declare variables
        $current = (string) $_POST['current'];
        $create = (string) $_POST['create'];
        $confirm = (string) $_POST['confirm'];
        // validate inputs
        if (!$confirm || !$create || !$current) {
            $_SESSION['edit_password'] = "Fill in all inputs!!";
        } else {
            // get password from database
            $get_user = mysqli_prepare($connection, "SELECT * FROM user WHERE id=?");
            mysqli_stmt_bind_param($get_user, "i", $id);
            mysqli_stmt_execute($get_user);
            $results = mysqli_stmt_get_result($get_user);
            if (mysqli_num_rows($results) == 0) {
                $_SESSION['edit_password'] = "User not found!!";
            } else {
                $gotten_result = mysqli_fetch_assoc($results);
                $realPassword = $gotten_result['password'];
                if ($current !== $realPassword) {
                    $_SESSION['edit_password'] = "Incorrect current password!!";
                } else {
                    if ($confirm == $create) {
                        $new_hased = password_hash($create, PASSWORD_DEFAULT);
                    } else {
                        $_SESSION['edit_password'] = "Create & Confirm passwords do not match!!";
                    }   
                } 
            }
        }
        // redirect back if there's any error
        if (isset($_SESSION['edit_password'])) {
            header("location: " . root_url . "admin/edit_password.php?id=" . $id);
            die();
        } else {
            $update = "UPDATE user SET password=? WHERE id=?";
            $query = mysqli_prepare($connection, $update);
            mysqli_stmt_bind_param($query, "si", $new_hased, $id);
            mysqli_stmt_execute($query);
            if (!mysqli_errno($connection)) {
                $_SESSION['edit_password_success'] = "Your password have been updated!!";
            } else {
                $_SESSION['edit_password_error'] = "Couldn't update password, try again!!";
            }
            // redirect    
            header("location: " . root_url . "admin/dashboard.php");
            die();
        }
    } else {
        header("location: " . root_url . "admin/edit_password.php");
        die();
    }
    