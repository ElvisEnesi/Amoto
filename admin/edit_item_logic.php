<?php
    // including files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    if (isset($_POST['submit'])) {
        // declare variables
        $id = (int) $_POST['id'];
        $title = (string) $_POST['title'];
        $previous_picture = (string) $_POST['previous_image'];
        $price = (int) $_POST['price'];
        $category = (int) $_POST['category'];
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
            $allowed_images = ['png', 'jpg', 'jpeg'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $avatar_tmp_name);
            if (in_array($mime_type, $allowed_images)) {
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
            $update = "UPDATE products SET product=?, price=?, category_id=?, picture=? WHERE id=?";
            $query = mysqli_prepare($connection, $update);
            mysqli_stmt_bind_param($query, "siisi", $title, $price, $category, $avatar_name, $id);
            mysqli_stmt_execute($query);
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
    