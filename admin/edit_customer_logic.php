<?php
    // including files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    if (isset($_POST['submit'])) {
        // declare variables
        $id = (int) $_POST['id'];
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
    