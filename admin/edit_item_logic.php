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
    if (isset($_GET['id']) && isset($_SESSION['user_id']) && isset($_SESSION['i_am_admin'])) {
        $id = (int) $_GET['id'];
        $edit_search = "SELECT * FROM products WHERE id=?";
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
    // edit logic
    if (isset($_POST['submit'])) {
        // declare variables
        $title = (string) $_POST['title'];
        $previous_picture = (string) $edit['picture'];
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
            } else {
                $_SESSION['edit_item_error'] = "Couldn't update item!!";
            }
            // redirect 
            header("location: " . root_url . "admin/manage_items.php");
            die();
        }
    } else {
        header("location: " . root_url . "admin/edit_item.php");
        die();
    }
    