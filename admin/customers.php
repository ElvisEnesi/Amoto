    <?php
        include "./configuration/database.php";
        include "./partials/header.php";
        // 
        $current_admin_user = $_SESSION['user_id'];
        $select_user = "SELECT * FROM user WHERE NOT id=$current_admin_user";
        $query_user = mysqli_query($connection, $select_user);
    ?>
    <?php  
        if (isset($_SESSION['edit_customer_success'])) {
            echo "<div class='notice'>";
            echo $_SESSION['edit_customer_success'];
            echo "</div>";
        }
        unset($_SESSION['edit_customer_success']);
    ?>
    <?php  
        if (isset($_SESSION['edit_customer_error'])) {
            echo "<div class='notice'>";
            echo $_SESSION['edit_customer_error'];
            echo "</div>";
        }
        unset($_SESSION['edit_customer_error']);
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
            <?php if (isset($_SESSION['i_am_admin'])) : ?>
            <a href="add_category.php">Add Category</a>
            <a href="manage_category.php">Manage Categories</a>
            <a href="add_item.php">Add Items</a>
            <a href="manage_items.php">Manage Items</a>
            <a href="manage_carts.php">Manage Carts</a>
            <a href="customers.php" class="active">View Customers</a>
            <a href="transactions.php">Transactions</a>
            <a href="order.php">View Orders</a>
            <?php endif ?>
            <a href="histroy.php">Order History</a>
            <a href="log_out.php">Log Out</a>
        </aside>
        <main>
            <?php if (mysqli_num_rows($query_user) > 0) : ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                <?php while ($user = mysqli_fetch_assoc($query_user)) : ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= "{$user['first_name']} {$user['last_name']}" ?></td>
                    <td><?= $user['user_name'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td><?= $user['address'] ?></td>
                    <td><?= $user['fraud_status'] ?></td>
                    <td><a href="<?= root_url ?>admin/edit_customer.php?id=<?= $user['id'] ?>">Edit</a></td>
                    <td><a href="<?= root_url ?>admin/delete_customer.php?id=<?= $user['id'] ?>" class="danger">Delete</a></td>
                </tr>
                <?php endwhile ?>
            </table>
            <?php else : ?>
                No data to display, try adding!!
            </div>
            <?php endif ?>
        </main>
    </section>
    <?php
        include "../partials/footer.php";