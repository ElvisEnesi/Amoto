<?php
    // include files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    // logic
    if (isset($_POST['submit'])) {
        // declare variables
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $realPassword = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $current = filter_var($_POST['current'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $create = filter_var($_POST['create'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $confirm = filter_var($_POST['confirm'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        // validate inputs
        if (!$confirm || !$create || !$current) {
            $_SESSION['edit_password'] = "Fill in all inputs!!";
        } else {
            if ($current !== $realPassword) {
                $_SESSION['edit_password'] = "Incorrect current password!!";
            } else {
                if ($confirm == $create) {
                    $new_hased = password_hash($create, PASSWORD_DEFAULT);
                } else {
                    $_SESSION['edit_password'] = "Create & Confirm passwords do not match!!";
                }   
            } 
        }
        // redirect back if there's any error
        if (isset($_SESSION['edit_password'])) {
            header("location: " . root_url . "admin/edit_password.php?id-" . $id);
            die();
        } else {
            $update = "UPDATE user SET password='$new_hased' WHERE id=$id";
            $query = mysqli_query($connection, $update);
            if (!mysqli_errno($connection)) {
                $_SESSION['edit_password_success'] = "Your password have been updated!!";
                header("location: " . root_url . "admin/dashboard.php");
                die();
            } else {
                $_SESSION['edit_password_error'] = "Couldn't update password, try again!!";
                header("location: " . root_url . "admin/dashboard.php");
                die();
            }
        }
    } else {
        header("location: " . root_url . "admin/edit_password.php");
        die();
    }
    