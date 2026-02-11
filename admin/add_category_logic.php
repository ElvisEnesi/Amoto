<?php
    // including files
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
    if (!isset($_SESSION['user_id']) && !isset($_SESSION['i_am_admin'])) {
        // insert into unauthorized
        $unauthorized = mysqli_prepare($connection, "INSERT INTO unauthorized (ip_address) VALUES(?)");
        mysqli_stmt_bind_param($unauthorized, "s", $user_ip);
        mysqli_stmt_execute($unauthorized);
        // redirect
        header("location: " . root_url . "kick_you_out.php");
    }
    if (isset($_POST['submit'])) {
        // declare variables
        $title = (string) $_POST['title'];
        $description = (string) $_POST['description'];
        // validating inputs
        if (!$title || !$description) {
            $_SESSION['add_category'] = "Fill in all inputs!";
        } 
        if (isset($_SESSION['add_category'])) {
            header("location: " . root_url . "admin/add_category.php");
            die();
        } else {
            $insert = "INSERT INTO category SET title=?, description=?";
            $stmt = mysqli_prepare($connection, $insert);
            mysqli_stmt_bind_param($stmt, "ss", $title, $description);
            mysqli_stmt_execute($stmt);
            if (!mysqli_errno($connection)) {
                $_SESSION['add_category_success'] = "Category successfully created!!";
                header("location: " . root_url . "admin/manage_category.php");
                die();
            } else {
                $_SESSION['add_category_error'] = "Couldn't create new category!!";
                header("location: " . root_url . "admin/manage_category.php");
                die();
            }
        }
    } else {
        header("location: " . root_url . "admin/add_category.php");
        die();
    }
    