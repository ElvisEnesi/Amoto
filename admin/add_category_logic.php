<?php
    // including files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    if (isset($_POST['submit'])) {
        // declare variables
        $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $description = filter_var($_POST['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        // validating inputs
        if (!$title || !$description) {
            $_SESSION['add_category'] = "Fill in all inputs!";
        } 
        if (isset($_SESSION['add_category'])) {
            header("location: " . root_url . "admin/add_category.php");
            die();
        } else {
            $insert = "INSERT INTO category SET title='$title', description='$description'";
            $query = mysqli_query($connection, $insert);
            if (!mysqli_errno($connection)) {
                $_SESSION['add_category_success'] = "Category successfully created!!";
                header("location: " . root_url . "admin/manage_category.php");
                die();
            } else {
                $_SESSION['add_category_error'] = "Couldn't create new category!!";
                header("location: " . root_url . "admin/manage_category.php");
                die();
            }
        }
    } else {
        header("location: " . root_url . "admin/add_category.php");
        die();
    }
    