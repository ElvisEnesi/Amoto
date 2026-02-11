<?php
    // include files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    // get id from url
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];
        // delete item
        $delete = "DELETE FROM user WHERE id=?";
        $stmt = mysqli_prepare($connection, $delete);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        // checking database errors
        if (!mysqli_errno($connection)) {
            // redirect with success message
            $_SESSION['delete_success'] = "Customer successfully deleted!!";
            header("location: " . root_url . "admin/customers.php");
            die();
        } else {
            // redirect with error message
            $_SESSION['delete_error'] = "Couldn't delete this customer!!";
            header("location: " . root_url . "admin/customers.php");
            die();
        }
    } else {
        // redirect back to manage category
        header("location: " . root_url . "admin/customers.php");
        die();
    }
    