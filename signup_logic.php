<?php
    // including files
    include "./configuration/constant.php";
    include "./configuration/database.php";
    if (isset($_POST['submit'])) {
        // declare variables
        $first_name = (string) $_POST['fname'];
        $last_name = (string) $_POST['lname'];
        $user_name = (string) $_POST['username'];
        $email = (string) $_POST['email'];
        $address = (string) $_POST['address'];
        $create = (string) $_POST['create'];
        $confirm = (string) $_POST['confirm'];
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
                $allowed_images = ['png', 'jpg', 'jpeg'];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime_type = finfo_file($finfo, $avatar_tmp_name);
                if (in_array($mime_type, $allowed_images)) {
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
            $insert = "INSERT INTO user SET first_name=?, last_name=?, user_name=?, 
            email=?, address=?, password=?, picture=?";
            $stmt = mysqli_prepare($connection, $insert);
            mysqli_stmt_bind_param($stmt, "sssssss", $first_name, $last_name, $user_name, $email, $address, $hased_password, $avatar_name);
            mysqli_stmt_execute($stmt);
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
    