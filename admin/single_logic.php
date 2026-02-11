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
    // not logged in
    if (isset($_POST['login'])) {
        header("location: " . root_url . "login.php");
        die();
    } 
    // logged in
    if (isset($_POST['submit'])) {
        // declare variables
        $customer_id = (int) $_SESSION['user_id'] ?? null;
        $quantity = (int) $_POST['qty'];
        // get product id from url
        if (isset($_GET['id'])) {
            $product_id = (int) $_GET['id'];
        } else {
            header("location: " . root_url . "admin/single.php");
            die();
        }
        // validate inputs
        if (!is_numeric($quantity) || !is_numeric($product_id) || !is_numeric($customer_id)) {
            $_SESSION['single_logic'] = "All fields must be numbers!!";
        } elseif ($quantity < 1) {
            $_SESSION['single_logic'] = "Quantity can't be less than 1!!";
        }
        // redirect if any error
        if (isset($_SESSION['single_logic'])) {
            header("location: " . root_url . "single.php?id=" . $product_id);
            die();
        } else {
           // insert into cart
           $insert = "INSERT INTO cart SET customer_id=?, product_id=?, quantity=?";
           $stmt = mysqli_prepare($connection, $insert);
           mysqli_stmt_bind_param($stmt, "iii", $customer_id, $product_id, $quantity);
           $query = mysqli_stmt_execute($stmt);
           if (!mysqli_errno($connection)) {
                $_SESSION['success'] = "Item successfully added to cart!!";
                header("location: " . root_url . "admin/cart.php");
                die();
           } else {
                $_SESSION['error'] = "Couldn't add to cart!!";
                header("location: " . root_url . "shop.php");
                die();
           }
        }
    } else {
        header("location: " . root_url . "admin/single.php");
        die();
    }
    