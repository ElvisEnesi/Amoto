<?php
    // including files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    if (isset($_POST['submit'])) {
        // declare variables
        $title = (string) $_POST['title'];
        $price = (int) $_POST['price'];
        $category = (int) $_POST['category'];
        $avatar = $_FILES['avatar'];
        // validating inputs
        if (!$title || !$price || !$category || !$avatar['name']) {
            $_SESSION['add_item'] = "Fill in all inputs!";
        } else {
            // work on image on this
            $avatar_name = $avatar['name'];
            $avatar_tmp_name = $avatar['tmp_name'];
            $avatar_destination = "../images/items/" . $avatar_name;
            // make sure file is an image
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $avatar_tmp_name);
            $allowed_images = ['image/png', 'image/jpeg', 'image/jpg'];
            if (in_array($mime_type, $allowed_images)) {
                // make sure file isn't too big 3mb+
                if ($avatar['size'] > 3000000) {
                    $_SESSION['add_item'] = "File should be less than 3mb!";
                }
            } else {
                $_SESSION['add_item'] = "File must be png, jpg or jpeg!!";
            }
        }

        if (isset($_SESSION['add_item'])) {
            header("location: " . root_url . "admin/add_item.php");
            die();
        } else {
            $insert = "INSERT INTO products SET product=?, price=?, category_id=?, picture=?";
            $stmt = mysqli_prepare($connection, $insert);
            mysqli_stmt_bind_param($stmt, "siis", $title, $price, $category, $avatar_name);
            mysqli_stmt_execute($stmt);
            if (!mysqli_errno($connection)) {
                move_uploaded_file($avatar_tmp_name, $avatar_destination);
                $_SESSION['add_item_success'] = "Item successfully added!!";
                header("location: " . root_url . "admin/manage_items.php");
                die();
            } else {
                $_SESSION['add_item_error'] = "Couldn't add item!!";
                header("location: " . root_url . "admin/manage_items.php");
                die();
            }
        }
    } else {
        header("location: " . root_url . "admin/add_item.php");
        die();
    }
    