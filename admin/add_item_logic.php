<?php
    // including files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    if (isset($_POST['submit'])) {
        // declare variables
        $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
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
            $allowed_images = ['png', 'jpg', 'jpeg', 'enc'];
            $extention = explode('.', $avatar_name);
            $extention = end($extention);
            if (in_array($extention, $allowed_images)) {
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
            $insert = "INSERT INTO products SET product='$title', price=$price, category_id=$category, picture='$avatar_name'";
            $query = mysqli_query($connection, $insert);
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
    