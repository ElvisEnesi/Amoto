<?php
    // include files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    // get id from url
    if (isset($_GET['id'])) {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        // delete item
        $delete = "DELETE FROM user WHERE id=$id";
        $query = mysqli_query($connection, $delete);
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
    