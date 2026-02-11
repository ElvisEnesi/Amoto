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
    if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
        $id = (int) $_GET['id'];
        $edit_search = "SELECT * FROM user WHERE id=?";
        $stmt = mysqli_prepare($connection, $edit_search);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $edit = mysqli_fetch_assoc($result);
    } else {
        // insert into unauthorized
        $unauthorized = mysqli_prepare($connection, "INSERT INTO unauthorized (ip_address) VALUES(?)");
        mysqli_stmt_bind_param($unauthorized, "s", $user_ip);
        mysqli_stmt_execute($unauthorized);
        // redirect
        header("location: " . root_url . "kick_you_out.php");
    }
    // logic
    if (isset($_POST['submit'])) {
        // declare variables
        $new_picture = $_FILES['avatar'];
        $old_picture = (string) $edit['picture'];
        // check inputs
        if (!$new_picture['name']) {
            $_SESSION['edit_image'] = "Choose an image!!";
        } else {
            // work on image
            $picture_name = $new_picture['name'];
            $picture_tmp_name = $new_picture['tmp_name'];
            $picture_destination = "../images/users/" . $picture_name;
            // make sure file is an image
            $allowed_files = ['image/jpeg', 'image/png', 'image/jpg'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $picture_tmp_name);
            if (in_array($mime_type, $allowed_files)) {
                if ($new_picture['size'] > 3000000) {
                    $_SESSION['edit_image'] = "File must be less than 3mb!!";
                }
            } else {
                $_SESSION['edit_image'] = "File must be jpg, png, jpeg!!";
            }
        }
        // redirect if there's any problem
        if (isset($_SESSION['edit_image'])) {
            header("location: " . root_url . "admin/edit_image.php?id=" . $id);
            die();
        } else {
            // update image
            $old_image_path = "../images/users/" . $old_picture;
            $update = "UPDATE user SET picture=? WHERE id=?";
            $query = mysqli_prepare($connection, $update);
            mysqli_stmt_bind_param($query, "si", $picture_name, $id);
            mysqli_stmt_execute($query);
            if (!mysqli_errno($connection)) {
                // unlink previous picture from folder
                if ($old_image_path) {
                    unlink($old_image_path);
                }
                move_uploaded_file($picture_tmp_name, $picture_destination);
                // redirect to dashboard with success update message
                $_SESSION['edit_image_success'] = "Image updated successfully!!";
            } else {
                // redirect back to dashboard with error message
                $_SESSION['edit_image_error'] = "Couldn't update image, try again!!";
            }
            // redirect
            header("location: " . root_url . "admin/dashboard.php");
            die();
        }
    } else {
        header("location: " . root_url . "admin/edit_image.php");
        die();
    }
    


