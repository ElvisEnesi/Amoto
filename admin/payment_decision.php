<?php
    // include files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    // get cart id from url
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];
    } else {
        header("location: " . root_url . "admin/cart.php");
        die();
    }
    // redirect if id was gotten
    if (isset($_POST['payment'])) {
        header("location: " . root_url . "admin/payment.php?id=" . $id);
        die();
    } elseif (isset($_POST['delete'])) {
        header("location: " . root_url . "admin/delete_cart.php?id=" . $id);
        die();
    } else {
        header("location: " . root_url . "admin/cart.php");
        die();
    }