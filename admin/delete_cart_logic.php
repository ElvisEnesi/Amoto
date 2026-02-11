<?php
    // include files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    // get id from url
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];
        // delete item
        $delete = "DELETE FROM cart WHERE id=?";
        $stmt = mysqli_prepare($connection, $delete);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        // checking database errors
        if (!mysqli_errno($connection)) {
            // redirect with success message
            $_SESSION['delete_success'] = "Cart successfully deleted!!";
        } else {
            // redirect with error message
            $_SESSION['delete_error'] = "Couldn't delete cart!!";
        }
        if (isset($_SESSION['i_am_admin'])) {
            header("location: " . root_url . "admin/manage_carts.php");
            die();
        } else {
            header("location: " . root_url . "admin/cart.php");
            die();
        }
    } else {
        // redirect back if id was not gotten
        if (isset($_SESSION['i_am_admin'])) {
            header("location: " . root_url . "admin/manage_carts.php");
            die();
        } else {
            header("location: " . root_url . "admin/cart.php");
            die();
        }
    }
    
    