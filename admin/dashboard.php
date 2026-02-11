    <?php
        include "./configuration/database.php";
        include "./partials/header.php";
    ?>
    <?php  
        if (isset($_SESSION['edit_image_success'])) {
            echo "<div class='notice'>";
            echo $_SESSION['edit_image_success'];
            echo "</div>";
        }
        unset($_SESSION['edit_image_success']);
    ?>
    <?php  
        if (isset($_SESSION['edit_image_error'])) {
            echo "<div class='notice'>";
            echo $_SESSION['edit_image_error'];
            echo "</div>";
        }
        unset($_SESSION['edit_image_error']);
    ?>
    <?php  
        if (isset($_SESSION['edit_password_success'])) {
            echo "<div class='notice'>";
            echo $_SESSION['edit_password_success'];
            echo "</div>";
        }
        unset($_SESSION['edit_password_success']);
    ?>
    <?php  
        if (isset($_SESSION['edit_password_error'])) {
            echo "<div class='notice'>";
            echo $_SESSION['edit_password_error'];
            echo "</div>";
        }
        unset($_SESSION['edit_password_error']);
    ?>
    <?php  
        if (isset($_SESSION['edit_details_success'])) {
            echo "<div class='notice'>";
            echo $_SESSION['edit_details_success'];
            echo "</div>";
        }
        unset($_SESSION['edit_details_success']);
    ?>
    <?php  
        if (isset($_SESSION['edit_details_error'])) {
            echo "<div class='notice'>";
            echo $_SESSION['edit_details_error'];
            echo "</div>";
        }
        unset($_SESSION['edit_details_error']);
    ?>
        <div id="btn" class="open" onclick="openSide()">
            <ion-icon name="chevron-forward-outline"></ion-icon>
        </div>
        <div id="btn" class="close" onclick="closeSide()">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </div>
    <section class="dashbord">
        <aside id="sidebar">
            <a href="dashboard.php" class="active">Profile</a>
            <?php if (isset($_SESSION['i_am_admin'])) : ?>
            <a href="add_category.php">Add Category</a>
            <a href="manage_category.php">Manage Categories</a>
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
            <div class="profile">
                <div class="profile_img">
                    <img src="../images/users/<?php echo htmlspecialchars($user['picture'], ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="edit"><a href="edit_image.php?id=<?php echo htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?>"><ion-icon name="pencil-outline"></ion-icon></a></div>
                </div>
                <div class="profile_info">
                    <p>Name: <?php echo htmlspecialchars("{$user['first_name']} {$user['last_name']}", ENT_QUOTES, 'UTF-8') ?></p>
                    <p>Username: <?php echo htmlspecialchars($user['user_name'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p>Email: <?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></p>
                    <div class="profile_buttons">
                        <button onclick="window.location.href='edit_password.php?id=<?php echo htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?>'">Change Password</button>
                        <button onclick="window.location.href='edit_image.php?id=<?php echo htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?>'">Change Image</button>
                        <button onclick="window.location.href='edit_details.php?id=<?php echo htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?>'">Edit Details</button>
                        <button onclick="window.location.href='delete_account.php?id=<?php echo htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?>'">Delete Account</button>
                    </div>
                </div>
            </div>
        </main>
    </section>
    <?php
        include "../partials/footer.php"; 
    ?>