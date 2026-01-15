    <?php
        include "./configuration/database.php";
        include "./partials/header.php";
        $select_items = "SELECT * FROM products";
        $query_items = mysqli_query($connection, $select_items);
    ?>
    <?php  
        if (isset($_SESSION['add_item_success'])) {
            echo "<div class='notice'>";
            echo $_SESSION['add_item_success'];
            echo "</div>";
        }
        unset($_SESSION['add_item_success']);
    ?>
    <?php  
        if (isset($_SESSION['add_item_error'])) {
            echo "<div class='notice'>";
            echo $_SESSION['add_item_error'];
            echo "</div>";
        }
        unset($_SESSION['add_item_error']);
    ?>
    <?php  
        if (isset($_SESSION['edit_item_success'])) {
            echo "<div class='notice'>";
            echo $_SESSION['edit_item_success'];
            echo "</div>";
        }
        unset($_SESSION['edit_item_success']);
    ?>
    <?php  
        if (isset($_SESSION['edit_item_error'])) {
            echo "<div class='notice'>";
            echo $_SESSION['edit_item_error'];
            echo "</div>";
        }
        unset($_SESSION['edit_item_error']);
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
            <a href="manage_items.php" class="active">Manage Items</a>
            <a href="manage_carts.php">Manage Carts</a>
            <a href="customers.php">View Customers</a>
            <a href="transactions.php">Transactions</a>
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
                    <th>Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                <?php while ($item = mysqli_fetch_assoc($query_items)) : ?>
                <?php
                    $category_num = $item['category_id'];
                    $select_category = "SELECT * FROM category WHERE id=$category_num";
                    $query_category = mysqli_query($connection, $select_category);
                    $gotten_category = mysqli_fetch_assoc($query_category);//
                ?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td><?= $item['product'] ?></td>
                    <td>$<?= $item['price'] ?></td>
                    <td><?= $gotten_category['title'] ?></td>
                    <td><a href="edit_item.php?id=<?= $item['id'] ?>">Edit</a></td>
                    <td><a href="delete_item.php?id=<?= $item['id'] ?>" class="danger">Delete</a></td>
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