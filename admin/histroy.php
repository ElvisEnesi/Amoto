    <?php
        // include database connection
        include "./configuration/database.php";
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
        if (!isset($_SESSION['i_am_admin'])) {
            // insert into unauthorized
            $unauthorized = mysqli_prepare($connection, "INSERT INTO unauthorized (ip_address) VALUES(?)");
            mysqli_stmt_bind_param($unauthorized, "s", $user_ip);
            mysqli_stmt_execute($unauthorized);
            // redirect
            header("location: " . root_url . "kick_you_out.php");
        }
        // include header
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
                <h3><?= htmlspecialchars($order['product_name'], ENT_QUOTES, 'UTF-8') ?></h3>
                <p>$<?= htmlspecialchars($order['total'], ENT_QUOTES, 'UTF-8') ?></p>
                <p>Quantity: <?= htmlspecialchars($order['quantity'], ENT_QUOTES, 'UTF-8') ?></p>
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