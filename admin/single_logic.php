<?php
    // including files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    // not logged i
    if (isset($_POST['login'])) {
        header("location: " . root_url . "login.php");
        die();
    } 
    // logged in
    if (isset($_POST['submit'])) {
        // declare variables
        $customer_id = filter_var($_POST['customer'], FILTER_SANITIZE_NUMBER_INT);
        $product_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $quantity = filter_var($_POST['qty'], FILTER_SANITIZE_NUMBER_INT);
        // validate inputs
        if (!is_numeric($quantity)) {
            $_SESSION['single_logic'] = "Quantity must be a number!!";
        } elseif ($quantity < 1) {
            $_SESSION['single_logic'] = "Quantity can't be less than 1!!";
        }
        // redirect if any error
        if (isset($_SESSION['single_logic'])) {
            header("location: " . root_url . "single.php?id=" . $product_id);
            die();
        } else {
           // insert into cart
           $insert = "INSERT INTO cart SET customer_id='$customer_id', product_id='$product_id', quantity='$quantity'";
           $query = mysqli_query($connection, $insert);
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
    