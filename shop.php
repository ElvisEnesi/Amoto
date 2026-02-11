    <?php
        include "./configuration/constant.php";
        include "./configuration/database.php";
        include "./partials/header.php";
    ?>
    <h2>BEST SELLERS</h2>
    <?php
        include "./partials/nav.php";
    ?>
    <?php  
        if (isset($_SESSION['error'])) {
            echo "<div class='notice'>";
            echo $_SESSION['error'];
            echo "</div>";
        }
        unset($_SESSION['error']);
    ?>
    <?php
        $select_products = "SELECT * FROM products";
        $query_products = mysqli_query($connection, $select_products);
    ?>
    <?php if (mysqli_num_rows($query_products) > 0) : ?>
    <section class="container">
        <?php while ($product = mysqli_fetch_assoc($query_products)) : ?>
        <div class="card">
            <div class="card_img">
                <img src="./images/items/<?= htmlspecialchars($product['picture'], ENT_QUOTES, 'UTF-8') ?>" onclick="window.location.href='<?= root_url ?>single.php?id=<?= htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8') ?>'">
                <div class="card_featured">Best seller</div>
            </div>
            <p><?= htmlspecialchars($product['product'], ENT_QUOTES, 'UTF-8') ?></p>
            <span class="price">$<?= htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8') ?></span>
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