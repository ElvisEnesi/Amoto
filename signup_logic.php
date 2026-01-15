<?php
    // including files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    if (isset($_POST['submit'])) {
        // declare variables
        $first_name = filter_var($_POST['fname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $last_name = filter_var($_POST['lname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $user_name = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $address = filter_var($_POST['address'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $create = filter_var($_POST['create'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $confirm = filter_var($_POST['confirm'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $avatar = $_FILES['avatar'];
        // validating inputs
        if (!$first_name || !$last_name || !$user_name || !$email || !$address || !$create || !$confirm || !$avatar['name']) {
            $_SESSION['sign_up'] = "Fill in all inputs!";
        } elseif (strlen($create) < 8 || strlen($confirm) < 8) {
            $_SESSION['sign_up'] = "Passwords must be more than 8";
        } else {
            // check if passwords match
            if ($create !== $confirm) {
                $_SESSION['sign_up'] = "Passwords do not match!!";
            } else {
                // hase the password before inserting
                $hased_password = password_hash($create, PASSWORD_DEFAULT);
                // work on image
                $avatar_name = $avatar['name'];
                $avatar_tmp_name = $avatar['tmp_name'];
                $avatar_destination = "./images/users/" . $avatar_name;
                // make sure file is an image
                $allowed_images = ['png', 'jpg', 'jpeg', 'enc'];
                $extention = explode('.', $avatar_name);
                $extention = end($extention);
                if (in_array($extention, $allowed_images)) {
                    // make sure file isn't too big 3mb+
                    if ($avatar['size'] > 3000000) {
                        $_SESSION['sign_up'] = "File should be less than 3mb!";
                    }
                } else {
                    $_SESSION['sign_up'] = "File must be png, jpg or jpeg!!";
                }
            }
        }

        if (isset($_SESSION['sign_up'])) {
            header("location: " . root_url . "signup.php");
            die();
        } else {
            $insert = "INSERT INTO user SET first_name='$first_name', last_name='$last_name', user_name='$user_name', 
            email='$email', address='$address', password='$hased_password', picture='$avatar_name'";
            $query = mysqli_query($connection, $insert);
            if (!mysqli_errno($connection)) {
                move_uploaded_file($avatar_tmp_name, $avatar_destination);
                $_SESSION['sign_up_success'] = "Account successfully created, now login!!";
                header("location: " . root_url . "login.php");
                die();
            } else {
                $_SESSION['sign_up_error'] = "Couldn't create account!!";
                header("location: " . root_url . "signup.php");
                die();
            }
        }
    } else {
        header("location: " . root_url . "signup.php");
        die();
    }
    