<?php
    include "./configuration/constant.php";
    include "./configuration/database.php";
    // payment logic
    if (isset($_POST['submit'])) {
        // declare variables
        $cart_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        $product_id = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);
        $product_name = filter_var($_POST['product_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $total = filter_var($_POST['total'], FILTER_SANITIZE_NUMBER_INT);
        $avatar = $_FILES['avatar'];
        $customer_id = $_SESSION['user_id'];
        // track transactions
        $ip = $_SERVER['REMOTE_ADDR'];
        mysqli_query($connection, "INSERT INTO transaction_log (user_id, ip_address) VALUES ($customer_id, '$ip')");
        if (!$avatar['name']) {
            $_SESSION['payment_logic'] = "Choose an image!!";
        } else {
            // work on
            $avatar_name = $avatar['name'];
            $avatar_tmp_name = $avatar['tmp_name'];
            $avatar_destination = "../images/payment/" . $avatar_name;
            // make sure file is an image
            $allowed_images = ['png', 'jpg', 'jpeg'];
            $extention = explode('.', $avatar_name);
            $extention = end($extention);
            if (in_array($extention, $allowed_images)) {
                // make sure file isn't too big 3mb+
                if ($avatar['size'] > 3000000) {
                    $_SESSION['payment_logic'] = "File should be less than 3mb!";
                }
            } else {
                $_SESSION['payment_logic'] = "File must be png, jpg or jpeg!!";
            }
        }
        // redirect if any error
        if (isset($_SESSION['payment_logic'])) {
            mysqli_query($connection, "INSERT INTO transaction_log (user_id, actions_logged, ip_address) VALUES ($customer_id, 'payment_failed', '$ip')");
            mysqli_query($connection, "UPDATE user SET fraud_status='flagged' WHERE id=$customer_id");
            header("location: " . root_url . "admin/payment.php?id=" . $cart_id);
            die();
        } else {
            // insert into orders
            $insert = "INSERT INTO orders SET customer_id=$customer_id, cart_id=$cart_id, product_id=$product_id, product_name='$product_name', 
            total=$total, picture='$avatar_name'";
            $query = mysqli_query($connection, $insert);
            if (!mysqli_errno($connection)) {
                // update cart status
                mysqli_query($connection, "INSERT INTO transaction_log (user_id, actions_logged, ip_address) VALUES ($customer_id, 'payment_success', '$ip')");
                $update = "UPDATE cart SET status='checked_out' WHERE id=$cart_id";
                $result = mysqli_query($connection, $update);
                move_uploaded_file($avatar_tmp_name, $avatar_destination);
                $_SESSION['success'] = "Payment made, check your history!!";
                header("location: " . root_url . "admin/cart.php");
                die();
            } else {
                $_SESSION['error'] = "Couldn't make payment";
                header("location: " . root_url . "admin/cart.php");
                die();
            }
        }
    } else {
        header("location: " . root_url . "admin/payment.php");
        die();
    }
    