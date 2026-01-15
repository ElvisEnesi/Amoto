    <?php
        include "./configuration/database.php";
        include "./partials/header.php";
        $select_cart = "SELECT * FROM cart WHERE status='checked_out' ORDER BY id DESC";
        $query_cart = mysqli_query($connection, $select_cart);
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
            <a href="transactions.php">Transactions</a>
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
                    $select_item = "SELECT * FROM products WHERE id='$item_id'";
                    $query_items = mysqli_query($connection, $select_item);
                    $items = mysqli_fetch_assoc($query_items);
                ?>
                <tr>
                    <td><?= $cart['id'] ?></td>
                    <td><?= $items['product'] ?></td>
                    <td>$<?= $items['price'] ?></td>
                    <td><?= $cart['quantity'] ?></td>
                    <td><?= $cart['status'] ?></td>
                    <td><a href="delete_cart.php?id=<?= $cart['id'] ?>" class="danger">Delete</a></td>
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