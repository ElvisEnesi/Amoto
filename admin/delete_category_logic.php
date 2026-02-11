<?php
    // include files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    // ip address function
    function get_ip_address() {
        // declare ip_address
        $ip_address = '';
        // check various headers for potential ip address
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }  elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip_address = 'UNKNOWN';
        }
        return $ip_address;
    }
    // ip address
    $user_ip = get_ip_address();
    // get id from url
    if (isset($_GET['id']) && isset($_SESSION['user_id']) && isset($_SESSION['i_am_admin'])) {
        $id = (int) $_GET['id'];
    } else {
        // insert into unauthorized
        $unauthorized = mysqli_prepare($connection, "INSERT INTO unauthorized (ip_address) VALUES(?)");
        mysqli_stmt_bind_param($unauthorized, "s", $user_ip);
        mysqli_stmt_execute($unauthorized);
        // redirect
        header("location: " . root_url . "kick_you_out.php");
    }
    // update category before deleting
    $update = "UPDATE products SET category_id = ? WHERE category_id = ?";
    $up_stmt = mysqli_prepare($connection, $update);
    $up_id = 5; // Uncategory id
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