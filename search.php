    <?php
        include "./configuration/database.php";
        include "./partials/header.php";
        include "./partials/nav.php";
        // search
         if (isset($_GET['search']) && isset($_GET['submit'])) {
             $search = (string) $_GET['search'];
             $search_select = "SELECT * FROM products WHERE product LIKE ?";
             $stmt = mysqli_prepare($connection, $search_select);
             mysqli_stmt_bind_param($stmt, "s", "%$search%");
             mysqli_stmt_execute($stmt);
             $search_query = mysqli_stmt_get_result($stmt);
         } else {
             header("location: " . root_url . "index.php");
             exit();
         }
    ?>
    <?php if (mysqli_num_rows($search_query) > 0) : ?>
    <section class="cart">
        <?php while ($gotten = mysqli_fetch_assoc($search_query)) : ?>
        <div class="cart_item">
            <div class="cart_img">
                <img src="./images/items/<?= htmlspecialchars($gotten['picture'], ENT_QUOTES, 'UTF-8') ?>" onclick="window.location.href='<?= root_url ?>single.php?id=<?= htmlspecialchars($gotten['id'], ENT_QUOTES, 'UTF-8') ?>'" alt="">
            </div>
            <div class="cart_info">
                <h3><?= htmlspecialchars($gotten['product'], ENT_QUOTES, 'UTF-8') ?></h3>
                <p>$<?= htmlspecialchars($gotten['price'], ENT_QUOTES, 'UTF-8') ?></p>
                <form action="" method="post">
                    <input type="hidden" name="name" value="<?= htmlspecialchars($gotten['product'], ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="price" value="<?= htmlspecialchars($gotten['price'], ENT_QUOTES, 'UTF-8') ?>">
                    <label for="qty">Quantity</label>
                    <input type="number" name="qty">
                    <br><br>
                    <button type="submit">Add to cart!!</button>
                </form>
            </div>
        </div>
        <?php endwhile ?>
    </section>
    <?php else : ?>
    <div class="notice">
        No data to display!!
    </div>
    <?php endif ?>
    <?php
        include "./partials/footer.php";