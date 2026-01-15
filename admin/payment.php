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
    // check transaction errors
    $transaction_select = "SELECT COUNT(*) AS failed_count FROM transaction_log 
    WHERE user_id = $current_payment AND actions_logged = 'payment_failed' AND created_at >= NOW() - INTERVAL 10 MINUTE";
    $transaction_query = mysqli_query($connection, $transaction_select);
    $transaction = mysqli_fetch_assoc($transaction_query);
    // check bot behaviours
    $bot_select = "SELECT COUNT(*) AS attempt FROM transaction_log 
    WHERE user_id = $current_payment AND actions_logged = 'payment_attempt' AND created_at >= NOW() - INTERVAL 30 SECOND";
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
        $join = "SELECT SUM(p.price * c.quantity) AS total_price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.status = 'active' AND c.customer_id =$current_payment";
        $join_query= mysqli_query($connection, $join);
        $total_price = 0;
        if ($join_query && $gotten = mysqli_fetch_assoc($join_query)) {
            $total_price = $gotten['total_price'] ?? 0;
        }
        // select product details
        $product_id = $edit['product_id'];
        $select_product = "SELECT * FROM products WHERE id = $product_id";
        $query_product = mysqli_query($connection, $select_product);
        $product = mysqli_fetch_assoc($query_product);
    ?>
    <?php if ($transaction['failed_count'] >= 5 || $bot['attempt'] >= 3): ?>
        <?php
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