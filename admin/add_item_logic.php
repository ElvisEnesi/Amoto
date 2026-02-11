<?php
    // including files
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
    if (!isset($_SESSION['user_id']) && !isset($_SESSION['i_am_admin'])) {
        // insert into unauthorized
        $unauthorized = mysqli_prepare($connection, "INSERT INTO unauthorized (ip_address) VALUES(?)");
        mysqli_stmt_bind_param($unauthorized, "s", $user_ip);
        mysqli_stmt_execute($unauthorized);
        // redirect
        header("location: " . root_url . "kick_you_out.php");
    }
    if (isset($_POST['submit'])) {
        // declare variables
        $title = (string) $_POST['title'];
        $price = (int) $_POST['price'];
        $category = (int) $_POST['category'];
        $avatar = $_FILES['avatar'];
        // validating inputs
        if (!$title || !$price || !$category || !$avatar['name']) {
            $_SESSION['add_item'] = "Fill in all inputs!";
        } elseif (!is_numeric($price) || !is_numeric($category)) {
            $_SESSION['add_item'] = "Price and Category must be numbers!";
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
    