    <?php
        include "./configuration/constant.php";
        include "./configuration/database.php";
        include "./partials/header.php";
        // get id from url
        if (isset($_GET['id'])) {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            // select product with that id
            $select = "SELECT * FROM products WHERE id=$id";
            $query = mysqli_query($connection, $select);
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
                <img src="./images/items/<?= $product['picture'] ?>">
            </div>
            <div class="cart_info">
                <h3><?= $product['product'] ?></h3>
                <p>$<?= $product['price'] ?></p>
                <form action="admin/single_logic.php" method="post">
                    <input type="hidden" name="customer" value="<?= $user['id'] ?>">
                    <input type="hidden" name="id" value="<?= $product['id'] ?>">
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