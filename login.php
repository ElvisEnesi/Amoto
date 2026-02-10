<?php
        include "./configuration/constant.php";
        include "./configuration/database.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppy</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php  
        if (isset($_SESSION['login'])) {
            echo "<div class='notice'>";
            echo $_SESSION['login'];
            echo "</div>";
        }
        unset($_SESSION['login']);
    ?>
    <?php  
        if (isset($_SESSION['sign_up_success'])) {
            echo "<div class='notice'>";
            echo $_SESSION['sign_up_success'];
            echo "</div>";
        }
        unset($_SESSION['sign_up_success']);
    ?>
    <?php  
        if (isset($_SESSION['delete_success'])) {
            echo "<div class='notice'>";
            echo $_SESSION['delete_success'];
            echo "</div>";
        }
        unset($_SESSION['delete_success']);
    ?>
    <?php
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
        // select attempt from login_log table
        $attempt_log = mysqli_prepare($connection, "SELECT COUNT(*) AS attempt FROM login_log WHERE ip_address = ? AND log_status = ? AND date >= NOW() - INTERVAL 30 MINUTE");
        // status
        $status = "attempt";
        mysqli_stmt_bind_param($attempt_log, "ss", $user_ip, $status);
        mysqli_stmt_execute($attempt_log);
        $attempt_result = mysqli_stmt_get_result($attempt_log);
        $attempt_row = mysqli_fetch_assoc($attempt_result);
        $attempts = $attempt_row['attempt'];
        // select failed from login_log table
        $failed_log = mysqli_prepare($connection, "SELECT COUNT(*) AS failed FROM login_log WHERE ip_address = ? AND log_status = ? AND date >= NOW() - INTERVAL 10 MINUTE");
        // status
        $status = "failure";
        mysqli_stmt_bind_param($failed_log, "ss", $user_ip, $status);
        mysqli_stmt_execute($failed_log);
        $failed_result = mysqli_stmt_get_result($failed_log);
        $failed_row = mysqli_fetch_assoc($failed_result);
        $failed = $failed_row['failed'];
    ?>
    <section class="form">
        <?php if ($attempts >= 5 || $failed >=3): ?>
            <?php 
                $activity = mysqli_prepare($connection, "INSERT INTO activity (activity_status, ip_address) VALUES (?, ?)");
                $status = "Brute force!!";
                mysqli_stmt_bind_param($activity, "ss", $status, $user_ip);
                mysqli_stmt_execute($activity);
            ?>
            <div class="notice">Too many failed attempts, try again after 30 minutes!!</div>
        <?php else : ?>
            <form action="login_logic.php" method="post">
                <h1>Login</h1>
                <input type="text" name="username" placeholder="Username">
                <input type="password" name="password" placeholder="Password">
                <button type="submit" name="submit">Submit</button>
            </form>
            <div class="note">
                Don't have an account? <a href="<?php echo root_url ?>signup.php">Sign up!!</a>
            </div>
        <?php endif; ?>
    </section>
</body>
</html>