<?php
    // include files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    // get id from url
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];
        // update category before deleting
        $update = "UPDATE products SET category_id = ? WHERE category_id = ?";
        $up_stmt = mysqli_prepare($connection, $update);
        $up_id = 5; // default category id
        mysqli_stmt_bind_param($up_stmt, "ii", $up_id, $id);
        mysqli_stmt_execute($up_stmt);
        // delete category
        $delete = "DELETE FROM category WHERE id=?";
        $stmt = mysqli_prepare($connection, $delete);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        // checking database errors
        if (!mysqli_errno($connection)) {
            // redirect with success message
            $_SESSION['delete_success'] = "Category successfully deleted!!";
        } else {
            // redirect with error message
            $_SESSION['delete_error'] = "Couldn't delete category!!";
        }
        // redirect
        header("location: " . root_url . "admin/manage_category.php");
        die();
    } else {
        // redirect back to manage category
        header("location: " . root_url . "admin/manage_category.php");
        die();
    }
    
    