    <?php
        include "./configuration/database.php";
        include "./partials/header.php";
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
        // all users except admin
        $current_admin_user = $_SESSION['user_id'] ?? null;
        $select_user = "SELECT * FROM user WHERE NOT id=?";
        $stmt_user = mysqli_prepare($connection, $select_user);
        mysqli_stmt_bind_param($stmt_user, "i", $current_admin_user);
        mysqli_stmt_execute($stmt_user);
        $query_user = mysqli_stmt_get_result($stmt_user);
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
            <a href="transactions.php">Activities</a>
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
                    <td><?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars("{$user['first_name']} {$user['last_name']}", ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user['user_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user['address'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user['fraud_status'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><a href="<?= root_url ?>admin/edit_customer.php?id=<?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?>">Edit</a></td>
                    <td><a href="<?= root_url ?>admin/delete_customer.php?id=<?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?>" class="danger">Delete</a></td>
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