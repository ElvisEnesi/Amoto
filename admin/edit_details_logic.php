<?php
    // including files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    if (isset($_POST['submit'])) {
        // declare variables
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $first_name = filter_var($_POST['fname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $last_name = filter_var($_POST['lname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $user_name = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $address = filter_var($_POST['address'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        // validating inputs
        if (!$first_name || !$last_name || !$user_name || !$email || !$address) {
            $_SESSION['edit_details'] = "Fill in all inputs!";
        } 
        if (isset($_SESSION['edit_details'])) {
            header("location: " . root_url . "admin/edit_details.php?id=" . $id);
            die();
        } else {
            $update = "UPDATE user SET first_name='$first_name', last_name='$last_name', user_name='$user_name', 
            email='$email', address='$address' WHERE id=$id";
            $query = mysqli_query($connection, $update);
            if (!mysqli_errno($connection)) {
                $_SESSION['edit_details_success'] = "Details successfully updated!!";
                header("location: " . root_url . "admin/dashboard.php");
                die();
            } else {
                $_SESSION['edit_details_error'] = "Couldn't update your details!!";
                header("location: " . root_url . "admin/dashbboard.php");
                die();
            }
        }
    } else {
        header("location: " . root_url . "admin/edit_details.php");
        die();
    }
    