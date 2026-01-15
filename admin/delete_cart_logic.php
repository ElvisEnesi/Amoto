<?php
    // include files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    // get id from url
    if (isset($_GET['id'])) {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        // delete item
        $delete = "DELETE FROM cart WHERE id=$id";
        $query = mysqli_query($connection, $delete);
        // checking database errors
        if (!mysqli_errno($connection)) {
            // redirect with success message
            $_SESSION['delete_success'] = "Cart successfully deleted!!";
            header("location: " . root_url . "admin/manage_carts.php");
            die();
        } else {
            // redirect with error message
            $_SESSION['delete_error'] = "Couldn't delete cart!!";
            header("location: " . root_url . "admin/manage_carts.php");
            die();
        }
    } else {
        // redirect back to manage category
        header("location: " . root_url . "admin/manage_carts.php");
        die();
    }
    
    