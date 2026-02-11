<?php
    include "./configuration/constant.php";
    include "./configuration/database.php";
    // secure admin pages 
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
    if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
        $id = (int) $_GET['id'];
    } else {
        // insert into unauthorized
        $unauthorized = mysqli_prepare($connection, "INSERT INTO unauthorized (ip_address) VALUES(?)");
        mysqli_stmt_bind_param($unauthorized, "s", $user_ip);
        mysqli_stmt_execute($unauthorized);
        // redirect
        header("location: " . root_url . "kick_you_out.php");
    }
    // current payment user
    $current_payment = $_SESSION['user_id'] ?? null;
    // check transaction errors
    $transaction_select = "SELECT COUNT(*) AS failed_count FROM transaction_log 
    WHERE user_id = ? AND actions_logged = ? AND created_at >= NOW() - INTERVAL 10 MINUTE";
    $transaction_query = mysqli_prepare($connection, $transaction_select);
    $failed_status = "payment_failed";
    mysqli_stmt_bind_param($transaction_query, "is", $current_payment, $failed_status);
    mysqli_stmt_execute($transaction_query);
    $transaction = mysqli_stmt_get_result($transaction_query);
    $transaction = mysqli_fetch_assoc($transaction);
    // check bot behaviours
    $bot_select = "SELECT COUNT(*) AS attempt FROM transaction_log 
    WHERE user_id = ? AND actions_logged = ? AND created_at >= NOW() - INTERVAL 5 MINUTE";
    $bot_query = mysqli_prepare($connection, $bot_select);
    $bot_status = "payment_attempt";
    mysqli_stmt_bind_param($bot_query, "is", $current_payment, $bot_status);
    mysqli_stmt_execute($bot_query);
    $bot_result = mysqli_stmt_get_result($bot_query);
    $bot = mysqli_fetch_assoc($bot_result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php  
        if (isset($_SESSION['payment_logic'])) {
            echo "<div class='notice'>";
            echo $_SESSION['payment_logic'];
            echo "</div>";
        }
        unset($_SESSION['payment_logic']);
    ?>
    <div class="notice">
        9063789267 OPAY campus shop, Payment validates order!!   send receipt picture below!!
    </div>
    <?php
        $join = "SELECT p.id AS product_id, p.price AS price, c.quantity AS quantity, c.product_id AS cart_product_id FROM cart c 
        INNER JOIN products p ON c.product_id = p.id WHERE c.status = ? AND c.customer_id = ?";
        $join_query= mysqli_prepare($connection, $join);
        $status = "active";
        mysqli_stmt_bind_param($join_query, "si", $status, $current_payment);
        mysqli_stmt_execute($join_query);
        $join_result = mysqli_stmt_get_result($join_query);
        if ($join_query && $gotten = mysqli_fetch_assoc($join_result)) {
            $total_price = $gotten['price'] * $gotten['quantity'];
        }
    ?>
    <?php if ($transaction['failed_count'] >= 5 || $bot['attempt'] >= 3): ?>
        <?php
            $activity = mysqli_prepare($connection, "INSERT INTO activity (activity_status, ip_address) VALUES (?, ?)");
            $status = "Fraud transaction!!";
            mysqli_stmt_bind_param($activity, "ss", $status, $user_ip);
            mysqli_stmt_execute($activity);
            $_SESSION['transaction'] = "Too many failed transaction, try again after 15minutes!!";
            header("location: " .root_url . "admin/cart.php");
            die();
        ?>
    <?php else : ?>
    <section class="form">
        <div>Total checkout price is $<?= htmlspecialchars(number_format($total_price, 2), ENT_QUOTES, 'UTF-8') ?></div>
        <form action="<?= root_url ?>admin/payment_logic.php?id=<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>" method="post" enctype="multipart/form-data">
            <input type="file" name="avatar">
            <button type="submit" name="submit">Submit</button>
        </form>
    </section>
    <?php endif ?>
</body>
</html>