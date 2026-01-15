    <?php
        include "./configuration/database.php";
        include "./partials/header.php";
        // select order history to display
        $select_orders = "SELECT * FROM orders WHERE customer_id=$current_user ORDER BY date DESC";
        $query_orders = mysqli_query($connection, $select_orders);
    ?>
    <?php if (mysqli_num_rows($query_orders) > 0) : ?>
    <section class="cart">
        <?php while ($order = mysqli_fetch_assoc($query_orders)) : ?>
        <?php 
            // select cart
            $cart_id = $order['cart_id'];
            $select_cart = "SELECT * FROM cart WHERE id=$cart_id";
            $query_cart = mysqli_query($connection, $select_cart);
            $cart = mysqli_fetch_assoc($query_cart);
            // select product details
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
                <h3><?= $order['product_name'] ?></h3>
                <p>$<?= $order['total'] ?></p>
                <p>Status: "<?= $order['status'] ?>"</p>
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