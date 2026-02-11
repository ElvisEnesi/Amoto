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
        $customer_id = (int) $_POST['customer'];
        $product_id = (int) $_POST['id'];
        $quantity = (int) $_POST['qty'];
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
           $insert = "INSERT INTO cart SET customer_id=?, product_id=?, quantity=?";
           $stmt = mysqli_prepare($connection, $insert);
           mysqli_stmt_bind_param($stmt, "iiii", $customer_id, $product_id, $quantity);
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
    