    <?php
        include "./configuration/database.php";
        include "./partials/header.php";
        $select_orders = "SELECT * FROM orders ORDER BY id DESC";
        $query_orders = mysqli_query($connection, $select_orders);
    ?>
    <?php  
        if (isset($_SESSION['edit_status_logic_success'])) {
            echo "<div class='notice'>";
            echo $_SESSION['edit_status_logic_success'];
            echo "</div>";
        }
        unset($_SESSION['edit_status_logic_success']);
    ?>
    <?php  
        if (isset($_SESSION['edit_status_logic_error'])) {
            echo "<div class='notice'>";
            echo $_SESSION['edit_status_logic_error'];
            echo "</div>";
        }
        unset($_SESSION['edit_status_logic_error']);
    ?>
        <div id="btn" class="open" onclick="openSide()">
            <ion-icon name="chevron-forward-outline"></ion-icon>
        </div>
        <div id="btn" class="close" onclick="closeSide()">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </div>
    <section class="dashbord">
        <aside id="sidebar">
            <a href="dashboard.php">Profile</a>
            <?php if (isset($_SESSION['user_id'])) : ?>
            <a href="add_category.php">Add Category</a>
            <a href="manage_category.php">Manage Categories</a>
            <a href="add_item.php">Add Items</a>
            <a href="manage_items.php">Manage Items</a>
            <a href="manage_carts.php">Manage Carts</a>
            <a href="customers.php">View Customers</a>
            <a href="transactions.php">Activities</a>
            <a href="order.php" class="active">View Orders</a>
            <?php endif ?>
            <a href="histroy.php">Order History</a>
            <a href="log_out.php">Log Out</a>
        </aside>
        <main>
            <?php if (mysqli_num_rows($query_orders) > 0) : ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Title</th>
                    <th>Price</th>
                    <th>quantity</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Receipt</th>
                    <th>Date</th>
                    <th>Edit</th>
                </tr>
                <?php while ($order = mysqli_fetch_assoc($query_orders)) : ?>
                <?php
                    // select custome
                    $customer_id = $order['customer_id'];
                    $select_customer = "SELECT * FROM user WHERE id=?";
                    $stmt_customer = mysqli_prepare($connection, $select_customer);
                    mysqli_stmt_bind_param($stmt_customer, "i", $customer_id);
                    mysqli_stmt_execute($stmt_customer);
                    $query_customer = mysqli_stmt_get_result($stmt_customer);
                    $customer = mysqli_fetch_assoc($query_customer);
                    // select cart
                    $cart_id = $order['cart_id'];
                    $select_cart = "SELECT * FROM cart WHERE id=?";
                    $stmt_cart = mysqli_prepare($connection, $select_cart);
                    mysqli_stmt_bind_param($stmt_cart, "i", $cart_id);
                    mysqli_stmt_execute($stmt_cart);
                    $query_cart = mysqli_stmt_get_result($stmt_cart);
                    $cart = mysqli_fetch_assoc($query_cart);
                ?>
                <tr>
                    <td><?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($order['customer_id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($order['product_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>$<?= htmlspecialchars($order['total'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($cart['quantity'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($customer['address'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><a href="../images/payment/<?= htmlspecialchars($order['picture'], ENT_QUOTES, 'UTF-8') ?> " download="">Download</a></td>
                    <td><?= htmlspecialchars($order['date'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><a href="<?= root_url ?>admin/edit_status.php?id=<?= htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8') ?>">Edit</a></td>
                </tr>
                <?php endwhile ?>
            </table>
            <?php else : ?>
            <div class="notice">
                No data to display, try adding!!
            </div>
            <?php endif ?>
        </main>
    </section>
    <?php
        include "../partials/footer.php";