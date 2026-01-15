    <?php
        include "./configuration/database.php";
        include "./partials/header.php";
        include "../partials/nav.php";
        // current user
        $current_user = $_SESSION['user_id'];
        // check transaction errors
        $transaction_select = "SELECT COUNT(*) AS failed_count FROM transaction_log 
        WHERE user_id = $current_user AND actions_logged = 'payment_failed' AND created_at >= NOW() - INTERVAL 10 MINUTE";
        $transaction_query = mysqli_query($connection, $transaction_select);
        $transaction = mysqli_fetch_assoc($transaction_query);
        // check bot behaviours
        $bot_select = "SELECT COUNT(*) AS attempt FROM transaction_log 
        WHERE user_id = $current_user AND actions_logged = 'payment_attempt' AND created_at >= NOW() - INTERVAL 30 SECOND";
        $bot_query = mysqli_query($connection, $bot_select);
        $bot = mysqli_fetch_assoc($bot_query);
        // select cart 
        $select_cart = "SELECT * FROM cart WHERE customer_id=$current_user AND status='active' ORDER BY id DESC";
        $query_cart = mysqli_query($connection, $select_cart);
    ?>
    <?php  
        if (isset($_SESSION['success'])) {
            echo "<div class='notice'>";
            echo $_SESSION['success'];
            echo "</div>";
        }
        unset($_SESSION['success']);
    ?>
    <?php  
        if (isset($_SESSION['error'])) {
            echo "<div class='notice'>";
            echo $_SESSION['error'];
            echo "</div>";
        }
        unset($_SESSION['error']);
    ?>
    <?php  
        if (isset($_SESSION['transaction'])) {
            echo "<div class='notice'>";
            echo $_SESSION['transaction'];
            echo "</div>";
        }
        unset($_SESSION['transaction']);
    ?>
    <?php if (mysqli_num_rows($query_cart) > 0) : ?>
    <section class="cart">
        <?php while ($cart = mysqli_fetch_assoc($query_cart)) : ?>
        <?php
            $product_id = $cart['product_id'];
            $select_product = "SELECT * FROM products WHERE id=$product_id";
            $query_product = mysqli_query($connection, $select_product);
            $product = mysqli_fetch_assoc($query_product);
        ?>
        <div class="cart_item">
            <div class="cart_img">
                <img src="../images/items/<?= $product['picture'] ?>">
            </div>
            <div class="cart_info">
                <h3><?= $product['product'] ?></h3>
                <p>Price: $<?= $product['price'] ?></p>
                <p>Quantity: <?php echo $cart['quantity'] ?></p>
                <?php if ($transaction['failed_count'] >= 5 || $bot['attempt'] >= 3): ?>
                    <p>Payments are unavailable!! suspicious activity detected!!</p>
                <?php else : ?>
                <form action="<?= root_url ?>admin/payment_decision.php?id=<?= $cart['id'] ?>" method="post">
                    <input type="hidden" name="id" value="<?= $cart['id'] ?>">
                    <button type="submit" name="payment">Proceed to payment!!</button>
                    <button type="submit" name="delete">Remove from cart!!</button>
                </form>
                <?php endif ?>
            </div>
        </div>
        <?php endwhile ?>
    </section>
    <?php else : ?>
    <div class="notice">
        No data to display, try adding!!
    </div>
    <?php endif ?>
    <?php
        include "../partials/footer.php";