    <?php
        include "./configuration/database.php";
        include "./partials/header.php";
        $select_items = "SELECT * FROM transaction_log ORDER BY created_at DESC";
        $query_items = mysqli_query($connection, $select_items);
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
            <a href="transactions.php" class="active">Transactions</a>
            <a href="order.php">View Orders</a>
            <?php endif ?>
            <a href="histroy.php">Order History</a>
            <a href="log_out.php">Log Out</a>
        </aside>
        <main>
            <?php if (mysqli_num_rows($query_items) > 0) : ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Customer ID</th>
                    <th>Transactions</th>
                    <th>IP address</th>
                    <th>Time</th>
                </tr>
                <?php while ($item = mysqli_fetch_assoc($query_items)) : ?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td><?= $item['user_id'] ?></td>
                    <td><?= $item['actions_logged'] ?></td>
                    <td><?= $item['ip_address'] ?></td>
                    <td><?= $item['created_at'] ?></td>
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