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
                <img src="./images/items/<?= $product['picture'] ?>" onclick="window.location.href='<?= root_url ?>single.php?id=<?= $product['id'] ?>'">
                <div class="card_featured">Best seller</div>
            </div>
            <p><?= $product['product'] ?></p>
            <span class="price">$<?= $product['price'] ?></span>
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