<?php
    // including files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    if (isset($_POST['submit'])) {
        // declare variables
        $id = (int) $_POST['id'];
        $first_name = (string) $_POST['fname'];
        $last_name = (string) $_POST['lname'];
        $user_name = (string) $_POST['username'];
        $email = (string) $_POST['email'];
        $address = (string) $_POST['address'];
        // validating inputs
        if (!$first_name || !$last_name || !$user_name || !$email || !$address) {
            $_SESSION['edit_details'] = "Fill in all inputs!";
        } 
        if (isset($_SESSION['edit_details'])) {
            header("location: " . root_url . "admin/edit_details.php?id=" . $id);
            die();
        } else {
            $update = "UPDATE user SET first_name=?, last_name=?, user_name=?, 
            email=?, address=? WHERE id=?";
            $query = mysqli_prepare($connection, $update);
            mysqli_stmt_bind_param($query, "sssssi", $first_name, $last_name, $user_name, $email, $address, $id);
            mysqli_stmt_execute($query);
            if (!mysqli_errno($connection)) {
                $_SESSION['edit_details_success'] = "Details successfully updated!!";
                header("location: " . root_url . "admin/dashboard.php");
                die();
            } else {
                $_SESSION['edit_details_error'] = "Couldn't update your details!!";
                header("location: " . root_url . "admin/dashboard.php");
                die();
            }
        }
    } else {
        header("location: " . root_url . "admin/edit_details.php");
        die();
    }
    