<?php
    // including files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    if (isset($_POST['submit'])) {
        // declare variables
        $id = (int) $_POST['id'];
        $description = (int) $_POST['description'];
        $title = (int) $_POST['title'];
        // validating inputs
        if (!$description || !$title) {
            $_SESSION['edit_category'] = "Fill in all inputs!";
        } 
        if (isset($_SESSION['edit_category'])) {
            header("location: " . root_url . "admin/edit_category.php?id=" . $id);
            die();
        } else {
            $update = "UPDATE category SET title=?, description=? WHERE id=?";
            $query = mysqli_prepare($connection, $update);
            mysqli_stmt_bind_param($query, "ssi", $title, $description, $id);
            mysqli_stmt_execute($query);
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
    