    <?php
        include "./configuration/database.php";
        include "./partials/header.php";
        $select_cart = "SELECT * FROM cart WHERE status=? ORDER BY id DESC";
        $stmt_cart = mysqli_prepare($connection, $select_cart);
        $status = "checked_out";
        mysqli_stmt_bind_param($stmt_cart, "s", $status);
        mysqli_stmt_execute($stmt_cart);
        $query_cart = mysqli_stmt_get_result($stmt_cart);
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
        if (isset($_SESSION['delete_error'])) {
            echo "<div class='notice'>";
            echo $_SESSION['delete_error'];
            echo "</div>";
        }
        unset($_SESSION['delete_error']);
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
            <a href="manage_carts.php" class="active">Manage Carts</a>
            <a href="customers.php">View Customers</a>
            <a href="transactions.php">Activities</a>
            <a href="order.php">View Orders</a>
            <?php endif ?>
            <a href="histroy.php">Order History</a>
            <a href="log_out.php">Log Out</a>
        </aside>
        <main>
            <table>
                <?php if (mysqli_num_rows($query_cart) > 0) : ?>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Delete</th>
                </tr>
                <?php while ($cart = mysqli_fetch_assoc($query_cart)) : ?>
                <?php 
                    // select item details
                    $item_id = $cart['product_id'];
                    $select_item = "SELECT * FROM products WHERE id=?";
                    $stmt_item = mysqli_prepare($connection, $select_item);
                    mysqli_stmt_bind_param($stmt_item, "i", $item_id);
                    mysqli_stmt_execute($stmt_item);
                    $query_items = mysqli_stmt_get_result($stmt_item);
                    $items = mysqli_fetch_assoc($query_items);
                ?>
                <tr>
                    <td><?= htmlspecialchars($cart['id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($items['product'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>$<?= htmlspecialchars($items['price'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($cart['quantity'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($cart['status'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><a href="delete_cart.php?id=<?= htmlspecialchars($cart['id'], ENT_QUOTES, 'UTF-8') ?>" class="danger">Delete</a></td>
                </tr>
                <?php endwhile ?>
                <?php else : ?>
                <div class="notice">
                    No data to display, try adding!!
                </div>
                <?php endif ?>
            </table>
        </main>
    </section>
    <?php
        include "../partials/footer.php";