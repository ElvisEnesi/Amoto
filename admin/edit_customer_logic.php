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
    if (isset($_POST['submit'])) {
        // declare variables
        $category = (string) $_POST['category'];
        // validating inputs
        if (!$category) {
            $_SESSION['edit_customer'] = "Select an option!!";
        } 
        if (isset($_SESSION['edit_customer'])) {
            header("location: " . root_url . "admin/edit_customer.php?id=" . $id);
            die();
        } else {
            $update = "UPDATE user SET fraud_status=? WHERE id=?";
            $query = mysqli_prepare($connection, $update);
            mysqli_stmt_bind_param($query, "si", $category, $id);
            mysqli_stmt_execute($query);
            if (!mysqli_errno($connection)) {
                $_SESSION['edit_customer_success'] = "Status successfully updated!!";
            } else {
                $_SESSION['edit_customer_error'] = "Couldn't update status!!";
            }
            // redirect
            header("location: " . root_url . "admin/customers.php");
            die();
        }
    } else {
        header("location: " . root_url . "admin/edit_customer.php");
        die();
    }
    