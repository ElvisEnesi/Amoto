    <?php
        include "./configuration/constant.php";
        include "./configuration/database.php";
        include "./partials/header.php";
        // get id from url
        if (isset($_GET['id'])) {
            $id = (int) $_GET['id'];
            // select product with that id
            $select = "SELECT * FROM products WHERE id=?";
            $stmt = mysqli_prepare($connection, $select);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $query = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($query) == 1) {
                $product = mysqli_fetch_assoc($query);
            }
        }
    ?>
    <?php  
        if (isset($_SESSION['single_logic'])) {
            echo "<div class='notice'>";
            echo $_SESSION['single_logic'];
            echo "</div>";
        }
        unset($_SESSION['single_logic']);
    ?>
    <section class="cart">
        <div class="cart_item">
            <div class="cart_img">
                <img src="./images/items/<?= htmlspecialchars($product['picture'], ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="cart_info">
                <h3><?= htmlspecialchars($product['product'], ENT_QUOTES, 'UTF-8') ?></h3>
                <p>$<?= htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8') ?></p>
                <form action="admin/single_logic.php?id=<?= htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8') ?>" method="post">
                    <label for="qty">Quantity</label>
                    <input type="number" name="qty" min="1">
                    <br><br>
                    <?php if (isset($_SESSION['user_id'])) : ?>
                    <button type="submit" name="submit">Add to cart!!</button>
                    <?php else : ?>
                    <button type="submit" name="login">Add to cart!!</button>
                    <?php endif ?>
                </form>
            </div>
        </div>
    </section>
    <?php
        include "./partials/footer.php";