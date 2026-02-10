    <?php
        include "./configuration/database.php";
        include "./partials/header.php";
        $select_items = "SELECT * FROM activity ORDER BY id DESC";
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
            <a href="transactions.php" class="active">Activities</a>
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
                    <th>Activity caught</th>
                    <th>IP address</th>
                    <th>Time</th>
                </tr>
                <?php while ($item = mysqli_fetch_assoc($query_items)) : ?>
                <tr>
                    <td><?= htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($item['activity_status'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($item['ip_address'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= date('Y-m-d H:i', strtotime(htmlspecialchars($item['date'], ENT_QUOTES, 'UTF-8'))) ?></td>
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