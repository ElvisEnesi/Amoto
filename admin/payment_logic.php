<?php
    include "./configuration/constant.php";
    include "./configuration/database.php";
    // current user
    $current_payment = $_SESSION['user_id'] ?? null;
    // get id from url
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];
        $edit_search = "SELECT * FROM cart WHERE id = ?";
        $edit_query = mysqli_prepare($connection, $edit_search);
        mysqli_stmt_bind_param($edit_query, "i", $id);
        mysqli_stmt_execute($edit_query);
        $result = mysqli_stmt_get_result($edit_query);
        $edit = mysqli_fetch_assoc($result);
    } else {
        header("location: " . root_url . "admin/cart.php");
        die();
    }
    $join = "SELECT p.id AS product_id, p.product AS title, p.price AS price, c.quantity AS quantity, c.product_id AS cart_product_id FROM cart c 
    INNER JOIN products p ON c.product_id = p.id WHERE c.status = ? AND c.customer_id = ?";
    $join_query= mysqli_prepare($connection, $join);
    $status = "active";
    mysqli_stmt_bind_param($join_query, "si", $status, $current_payment);
    mysqli_stmt_execute($join_query);
    $join_result = mysqli_stmt_get_result($join_query);
    $gotten = mysqli_fetch_assoc($join_result);

    // payment logic
    if (isset($_POST['submit'])) {
        // declare variables
        $cart_id = (int) $edit['id'];
        $product_id = (int) $gotten['product_id'];
        $product_name = (string) $gotten['title'];
        $quantity = (int) $gotten['quantity'];
        $total_price = (int) $gotten['price'] * (int) $gotten['quantity'];
        $avatar = $_FILES['avatar'];
        $customer_id = $_SESSION['user_id'];
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
        // log payment attempt
        $error =mysqli_prepare($connection, "INSERT INTO transaction_log (user_id, ip_address) VALUES (?, ?)");
        mysqli_stmt_bind_param($error, "is", $customer_id, $user_ip);
        mysqli_stmt_execute($error);
        if (!$avatar['name']) {
            $_SESSION['payment_logic'] = "Choose an image!!";
        } else {
            // work on
            $avatar_name = $avatar['name'];
            $avatar_tmp_name = $avatar['tmp_name'];
            $avatar_destination = "../images/payment/" . $avatar_name;
            // make sure file is an image
            $allowed_images = ['image/png', 'image/jpeg', 'image/jpg'];
            $finfo= finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $avatar_tmp_name);
            if (in_array($mime_type, $allowed_images)) {
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
            // insert into transaction log
            $failed = mysqli_prepare($connection, "INSERT INTO transaction_log (user_id, actions_logged, ip_address) VALUES (?, ?, ?)");
            $failed_status = "payment_failed";
            mysqli_stmt_bind_param($failed, "iss", $customer_id, $failed_status, $user_ip);
            mysqli_stmt_execute($failed);
            // update user fraud status
            $update_fraud = mysqli_prepare($connection, "UPDATE user SET fraud_status=? WHERE id=?");
            $fraud_status = "flagged";
            mysqli_stmt_bind_param($update_fraud, "si", $fraud_status, $customer_id);
            mysqli_stmt_execute($update_fraud);
            header("location: " . root_url . "admin/payment.php?id=" . $cart_id);
            die();
        } else {
            // insert into orders
            $insert = "INSERT INTO orders SET customer_id=?, cart_id=?, product_id=?, product_name=?, total=?, quantity=?, picture=?";
            $query = mysqli_prepare($connection, $insert);
            mysqli_stmt_bind_param($query, "iiisiis", $customer_id, $cart_id, $product_id, $product_name, $total_price, $quantity, $avatar_name);
            mysqli_stmt_execute($query);
            if (!mysqli_errno($connection)) {
                // insert into transaction log
                $transaction = mysqli_prepare($connection, "INSERT INTO transaction_log (user_id, actions_logged, ip_address) VALUES (?, ?, ?)");
                $payment_success = "payment_success";
                mysqli_stmt_bind_param($transaction, "iss", $customer_id, $payment_success, $user_ip);
                mysqli_stmt_execute($transaction);
                // update cart status
                $update = mysqli_prepare($connection, "UPDATE cart SET status=? WHERE id=?");
                $status = "checked_out";
                mysqli_stmt_bind_param($update, "si", $status, $cart_id);
                mysqli_stmt_execute($update);
                // move uploaded file
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
    