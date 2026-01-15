<?php
    // including files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    if (isset($_POST['submit'])) {
        // declare variables
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $description = filter_var($_POST['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        // validating inputs
        if (!$description || !$title) {
            $_SESSION['edit_category'] = "Fill in all inputs!";
        } 
        if (isset($_SESSION['edit_category'])) {
            header("location: " . root_url . "admin/edit_category.php?id=" . $id);
            die();
        } else {
            $update = "UPDATE category SET title='$title', description='$description' WHERE id=$id";
            $query = mysqli_query($connection, $update);
            if (!mysqli_errno($connection)) {
                $_SESSION['edit_category_success'] = "Category successfully updated!!";
                header("location: " . root_url . "admin/manage_category.php");
                die();
            } else {
                $_SESSION['edit_category_error'] = "Couldn't update category!!";
                header("location: " . root_url . "admin/manage_category.php");
                die();
            }
        }
    } else {
        header("location: " . root_url . "admin/edit_category.php");
        die();
    }
    