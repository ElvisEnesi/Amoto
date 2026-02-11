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
        // select all categories
        $category_select = "SELECT * FROM category";
        $category_query = mysqli_query($connection, $category_select);
    ?>
    <?php  
        if (isset($_SESSION['add_category_success'])) {
            echo "<div class='notice'>";
            echo $_SESSION['add_category_success'];
            echo "</div>";
        }
        unset($_SESSION['add_category_success']);
    ?>
    <?php  
        if (isset($_SESSION['add_category_error'])) {
            echo "<div class='notice'>";
            echo $_SESSION['add_category_error'];
            echo "</div>";
        }
        unset($_SESSION['add_category_error']);
    ?>
    <?php  
        if (isset($_SESSION['edit_category_success'])) {
            echo "<div class='notice'>";
            echo $_SESSION['edit_category_success'];
            echo "</div>";
        }
        unset($_SESSION['edit_category_success']);
    ?>
    <?php  
        if (isset($_SESSION['edit_category_error'])) {
            echo "<div class='notice'>";
            echo $_SESSION['edit_category_error'];
            echo "</div>";
        }
        unset($_SESSION['edit_category_error']);
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
            <a href="manage_category.php" class="active">Manage Categories</a>
            <a href="add_item.php">Add Items</a>
            <a href="manage_items.php">Manage Items</a>
            <a href="manage_carts.php">Manage Carts</a>
            <a href="customers.php">View Customers</a>
            <a href="transactions.php">Activities</a>
            <a href="order.php">View Orders</a>
            <?php endif ?>
            <a href="histroy.php">Order History</a>
            <a href="log_out.php">Log Out</a>
        </aside>
        <main>
            <?php if (mysqli_num_rows($category_query) > 0) : ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                <?php while ($category = mysqli_fetch_assoc($category_query)) : ?>
                <tr>
                    <td><?= htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($category['title'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><a href="edit_category.php?id=<?= htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8') ?>">Edit</a></td>
                    <td><a href="delete_category.php?id=<?= htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8') ?>" class="danger">Delete</a></td>
                </tr>
                <?php endwhile ?>
            </table>
            <?php else: ?>
            <div class="notice">
                No data to display, try adding!!
            </div>
            <?php endif ?>
        </main>
    </section>
    <?php
        include "../partials/footer.php";