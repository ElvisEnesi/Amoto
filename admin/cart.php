    <?php
        include "./configuration/database.php";
        include "./partials/header.php";
        include "../partials/nav.php";
        // current user
        $current_user = $_SESSION['user_id'];
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
        // select cart 
        $select_cart = "SELECT * FROM cart WHERE customer_id=? AND status=? ORDER BY id DESC";
        $query_cart = mysqli_prepare($connection, $select_cart);
        $status = "active";
        mysqli_stmt_bind_param($query_cart, "is", $current_user, $status);
        mysqli_stmt_execute($query_cart);
        $query_carts = mysqli_stmt_get_result($query_cart);
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
    <?php if (mysqli_num_rows($query_carts) > 0) : ?>
    <section class="cart">
        <?php while ($cart = mysqli_fetch_assoc($query_carts)) : ?>
        <?php
            $product_id = $cart['product_id'];
            $select_product = "SELECT * FROM products WHERE id=?";
            $query_product = mysqli_prepare($connection, $select_product);
            mysqli_stmt_bind_param($query_product, "i", $product_id);
            mysqli_stmt_execute($query_product);
            $products = mysqli_stmt_get_result($query_product);
            $product = mysqli_fetch_assoc($products);
        ?>
        <div class="cart_item">
            <div class="cart_img">
                <img src="../images/items/<?= htmlspecialchars($product['picture'], ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="cart_info">
                <h3><?= htmlspecialchars($product['product'], ENT_QUOTES, 'UTF-8') ?></h3>
                <p>Price: $<?= htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8') ?></p>
                <p>Quantity: <?php echo htmlspecialchars($cart['quantity'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php if ($transaction['failed_count'] >= 5 || $bot['attempt'] >= 3): ?>
                    <p>Payments are unavailable!! suspicious activity detected!!</p>
                <?php else : ?>
                <form action="<?= root_url ?>admin/payment_decision.php?id=<?= htmlspecialchars($cart['id'], ENT_QUOTES, 'UTF-8') ?>" method="post">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($cart['id'], ENT_QUOTES, 'UTF-8') ?>">
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