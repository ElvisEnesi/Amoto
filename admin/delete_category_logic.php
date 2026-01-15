<?php
    // include files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    // get id from url
    if (isset($_GET['id'])) {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        // update category before deleting
        $update = "UPDATE products SET category_id = 5 WHERE category_id = $id";
        $result = mysqli_query($connection, $update);
        // delete category
        $delete = "DELETE FROM category WHERE id=$id";
        $query = mysqli_query($connection, $delete);
        // checking database errors
        if (!mysqli_errno($connection)) {
            // redirect with success message
            $_SESSION['delete_success'] = "Category successfully deleted!!";
            header("location: " . root_url . "admin/manage_category.php");
            die();
        } else {
            // redirect with error message
            $_SESSION['delete_error'] = "Couldn't delete category!!";
            header("location: " . root_url . "admin/manage_category.php");
            die();
        }
    } else {
        // redirect back to manage category
        header("location: " . root_url . "admin/manage_category.php");
        die();
    }
    
    