<?php
        include "./configuration/constant.php";
        include "./configuration/database.php";
    // secure admin pages 
    if (!isset($_SESSION['user_id'])) {
        header("location: " . root_url . "login.php");
        die();
    }
    $current_payment = $_SESSION['user_id'];
    // get id from url
    if (isset($_GET['id'])) {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $edit_search = "SELECT * FROM cart WHERE id=$id";
        $result = mysqli_query($connection, $edit_search);
        $edit = mysqli_fetch_assoc($result);
    }
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
    // check transaction errors/
    $transaction_select = "SELECT COUNT(*) AS failed_count FROM transaction_log 
    WHERE user_id = $current_payment AND actions_logged = 'payment_failed' AND created_at >= NOW() - INTERVAL 10 MINUTE";
    $transaction_query = mysqli_query($connection, $transaction_select);
    $transaction = mysqli_fetch_assoc($transaction_query);
    // check bot behaviours
    $bot_select = "SELECT COUNT(*) AS attempt FROM transaction_log 
    WHERE user_id = $current_payment AND actions_logged = 'payment_attempt' AND created_at >= NOW() - INTERVAL 5 MINUTE";
    $bot_query = mysqli_query($connection, $bot_select);
    $bot = mysqli_fetch_assoc($bot_query);
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
        // select product details
        $product_id = $edit['product_id'];
        $select_product = "SELECT * FROM products WHERE id = $product_id";
        $query_product = mysqli_query($connection, $select_product);
        $product = mysqli_fetch_assoc($query_product);
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
        <div>Total checkout price is $<?= number_format($total_price, 2) ?></div>
        <form action="<?= root_url ?>admin/payment_logic.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $edit['id'] ?>">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <input type="hidden" name="product_name" value="<?= $product['product'] ?>">
            <input type="hidden" name="total" value="<?= $total_price ?>">
            <input type="file" name="avatar">
            <button type="submit" name="submit">Submit</button>
        </form>
    </section>
    <?php endif ?>
</body>
</html>