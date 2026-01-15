<?php
    // include files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    // logic
    if (isset($_POST['submit'])) {
        // declare variables
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $new_picture = $_FILES['avatar'];
        $old_picture = filter_var($_POST['previous'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        // check inputs
        if (!$new_picture['name']) {
            $_SESSION['edit_image'] = "Choose an image!!";
        } else {
            // work on image
            $picture_name = $new_picture['name'];
            $picture_tmp_name = $new_picture['tmp_name'];
            $picture_destination = "../images/users/" . $picture_name;
            // make sure file is an image
            $allowed_files = ['jpg', 'png', 'jpeg', 'enc'];
            $extention = explode('.', $picture_name);
            $extention = end($extention);
            if (in_array($extention, $allowed_files)) {
                if ($new_picture['size'] > 3000000) {
                    $_SESSION['edit_image'] = "File must be less than 3mb!!";
                }
            } else {
                $_SESSION['edit_image'] = "File must be jpg, png, jpeg or enc!!";
            }
        }
        // redirect if there's any problem
        if (isset($_SESSION['edit_image'])) {
            header("location: " . root_url . "admin/edit_image.php" . $id);
            die();
        } else {
            // update image
            $old_image_path = "../images/users/" . $old_picture;
            $update = "UPDATE user SET picture='$picture_name' WHERE id=$id";
            $query = mysqli_query($connection, $update);
            if (!mysqli_errno($connection)) {
                // unlink previous picture from folder
                if ($old_image_path) {
                    unlink($old_image_path);
                }
                move_uploaded_file($picture_tmp_name, $picture_destination);
                // redirect to dashboard with success update message
                $_SESSION['edit_image_success'] = "Image updated successfully!!";
                header("location: " . root_url . "admin/dashboard.php");
                die();
            } else {
                // redirect back to dashboard with error message
                $_SESSION['edit_image_error'] = "Couldn't update image, try again!!";
                header("location: " . root_url . "admin/dashboard.php");
                die();
            }
        }
    } else {
        header("location: " . root_url . "admin/edit_image.php");
        die();
    }
    


