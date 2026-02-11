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
    if (isset($_GET['id']) && isset($_SESSION['user_id']) && isset($_SESSION['i_am_admin'])) {
        $id = (int) $_GET['id'];
    } else {
        // insert into unauthorized
        $unauthorized = mysqli_prepare($connection, "INSERT INTO unauthorized (ip_address) VALUES(?)");
        mysqli_stmt_bind_param($unauthorized, "s", $user_ip);
        mysqli_stmt_execute($unauthorized);
        // redirect
        header("location: " . root_url . "kick_you_out.php");
    }
    // update logic
    if (isset($_POST['submit'])) {
        // declare variables
        $description = (int) $_POST['description'];
        $title = (int) $_POST['title'];
        // validating inputs
        if (!$description || !$title) {
            $_SESSION['edit_category'] = "Fill in all inputs!";
        } 
        if (isset($_SESSION['edit_category'])) {
            header("location: " . root_url . "admin/edit_category.php?id=" . $id);
            die();
        } else {
            $update = "UPDATE category SET title=?, description=? WHERE id=?";
            $query = mysqli_prepare($connection, $update);
            mysqli_stmt_bind_param($query, "ssi", $title, $description, $id);
            mysqli_stmt_execute($query);
            if (!mysqli_errno($connection)) {
                $_SESSION['edit_category_success'] = "Category successfully updated!!";
            } else {
                $_SESSION['edit_category_error'] = "Couldn't update category!!";
            }
            // redirect
            header("location: " . root_url . "admin/manage_category.php");
            die();
        }
    } else {
        header("location: " . root_url . "admin/edit_category.php");
        die();
    }
    