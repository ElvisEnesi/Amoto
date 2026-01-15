<?php
    // include files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    // current user
    $current_user = $_SESSION['user_id'];
    // select cart 
    $select_cart = "SELECT * FROM cart WHERE customer_id=$current_user AND status='active'";
    $query_cart = mysqli_query($connection, $select_cart);
    $cart = mysqli_fetch_assoc($query_cart);
    // select product details
    $product_id = $cart['product_id'];
    $select_product = "SELECT * FROM products WHERE id=$product_id";
    $query_product = mysqli_query($connection, $select_product);
    $product = mysqli_fetch_assoc($query_product);
    if (isset($_GET['id'])) {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    } else {
        header("location: " . root_url . "admin/cart.php");
        die();
    }
    if (isset($_POST['payment'])) {
        header("location: " . root_url . "admin/payment.php?id=". $cart['id']);
        die();
    } else {
        header("location: " . root_url . "admin/cart.php");
        die();
    }