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
            $_SESSION['edit_status_logic'] = "Select an option!!";
        } 
        if (isset($_SESSION['edit_status_logic'])) {
            header("location: " . root_url . "admin/edit_status.php?id=" . $id);
            die();
        } else {
            $update = "UPDATE orders SET status=? WHERE id=?";
            $query = mysqli_prepare($connection, $update);
            mysqli_stmt_bind_param($query, "si", $category, $id);
            mysqli_stmt_execute($query);
            if (!mysqli_errno($connection)) {
                $_SESSION['edit_status_logic_success'] = "Status successfully updated!!";
                header("location: " . root_url . "admin/order.php");
                die();
            } else {
                $_SESSION['edit_status_logic_error'] = "Couldn't update status!!";
                header("location: " . root_url . "admin/order.php");
                die();
            }
        }
    } else {
        header("location: " . root_url . "admin/edit_status.php");
        die();
    }
    