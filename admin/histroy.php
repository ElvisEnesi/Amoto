    <?php
        include "./configuration/database.php";
        include "./partials/header.php";
        // select order history to display
        $select_orders = "SELECT * FROM orders WHERE customer_id=? ORDER BY date DESC";
        $stmt = mysqli_prepare($connection, $select_orders);
        mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
        mysqli_stmt_execute($stmt);
        $query_orders = mysqli_stmt_get_result($stmt);
    ?>
    <?php if (mysqli_num_rows($query_orders) > 0) : ?>
    <section class="cart">
        <?php while ($order = mysqli_fetch_assoc($query_orders)) : ?>
        <?php 
            // select cart
            $cart_id = $order['cart_id'];
            $select_cart = "SELECT * FROM cart WHERE id=?";
            $stmt_cart = mysqli_prepare($connection, $select_cart);
            mysqli_stmt_bind_param($stmt_cart, "i", $cart_id);
            mysqli_stmt_execute($stmt_cart);
            $query_cart = mysqli_stmt_get_result($stmt_cart);
            $cart = mysqli_fetch_assoc($query_cart);
            // select product details
            $product_id = $cart['product_id'];
            $select_product = "SELECT * FROM products WHERE id=?";
            $stmt_product = mysqli_prepare($connection, $select_product);
            mysqli_stmt_bind_param($stmt_product, "i", $product_id);
            mysqli_stmt_execute($stmt_product);
            $query_product = mysqli_stmt_get_result($stmt_product);
            $product = mysqli_fetch_assoc($query_product);
        ?>
        <div class="cart_item">
            <div class="cart_img">
                <img src="../images/items/<?= htmlspecialchars($product['picture'], ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="cart_info">
                <h3><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                <p>$<?= htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8') ?></p>
                <p>Status: "<?= htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8') ?>"</p>
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