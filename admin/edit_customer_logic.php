<?php
    // including files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    if (isset($_POST['submit'])) {
        // declare variables
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $category = filter_var($_POST['category'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        // validating inputs
        if (!$category) {
            $_SESSION['edit_customer'] = "Select an option!!";
        } 
        if (isset($_SESSION['edit_customer'])) {
            header("location: " . root_url . "admin/edit_customer.php?id=" . $id);
            die();
        } else {
            $update = "UPDATE user SET fraud_status='$category' WHERE id=$id";
            $query = mysqli_query($connection, $update);
            if (!mysqli_errno($connection)) {
                $_SESSION['edit_customer_success'] = "Status successfully updated!!";
                header("location: " . root_url . "admin/customers.php");
                die();
            } else {
                $_SESSION['edit_customer_error'] = "Couldn't update status!!";
                header("location: " . root_url . "admin/customers.php");
                die();
            }
        }
    } else {
        header("location: " . root_url . "admin/edit_customer.php");
        die();
    }
    