<?php
    // including files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    if (isset($_POST['submit'])) {
        // declare variables
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $previous_picture = filter_var($_POST['previous_image'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
        $avatar = $_FILES['avatar'];
        $previous_path = "../images/items/" . $previous_picture;
        // validating inputs
        if (!$title || !$price || !$category || !$avatar['name']) {
            $_SESSION['edit_item'] = "Fill in all inputs!";
        } elseif (!is_numeric($price)) {
            $_SESSION['edit_item'] = "Price must be numbers with no symbol!!";
        } else {
            // work on image
            $avatar_name = $avatar['name'];
            $avatar_tmp_name = $avatar['tmp_name'];
            $avatar_destination = "../images/items/" . $avatar_name;
            // make sure file is an image
            $allowed_images = ['png', 'jpg', 'jpeg', 'enc'];
            $extention = explode('.', $avatar_name);
            $extention = end($extention);
            if (in_array($extention, $allowed_images)) {
                // make sure file isn't too big 3mb+
                if ($avatar['size'] > 3000000) {
                    $_SESSION['edit_item'] = "File should be less than 3mb!";
                }
            } else {
                $_SESSION['edit_item'] = "File must be png, jpg or jpeg!!";
            }
        }

        if (isset($_SESSION['edit_item'])) {
            header("location: " . root_url . "admin/edit_item.php?id=" . $id);
            die();
        } else {
            $update = "UPDATE products SET product='$title', price=$price, category_id=$category, picture='$avatar_name' WHERE id=$id";
            $query = mysqli_query($connection, $update);
            if (!mysqli_errno($connection)) {
                if ($previous_path) {
                    unlink($previous_path);
                }
                move_uploaded_file($avatar_tmp_name, $avatar_destination);
                $_SESSION['edit_item_success'] = "Item successfully update!!";
                header("location: " . root_url . "admin/manage_items.php");
                die();
            } else {
                $_SESSION['edit_item_error'] = "Couldn't update item!!";
                header("location: " . root_url . "admin/manage_items.php");
                die();
            }
        }
    } else {
        header("location: " . root_url . "admin/edit_item.php");
        die();
    }
    