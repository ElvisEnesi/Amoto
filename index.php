    <?php
        include "./configuration/constant.php";
        include "./configuration/database.php";
        include "partials/header.php";
        include "partials/nav.php";
    ?>
    <section class="banner">
        <img src="./images/1766527607Shirt.jpg" alt="banner">
        <div class="banner_info">
            <h1>CUE THE COLOR</h1>
            <button onclick="window.location.href='shop.php'">Shop with us</button>
        </div>
    </section>
    <h2>BEST SELLERS</h2>
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
    <section id="btn_section">
        <button onclick="window.location.href='shop.php'" class="btn">Shop With Us</button>
    </section>
    <section class="enquiry">
        <h3>Campus Delivery</h3>
        <h3>No return</h3>
        <h3>Product satisfactory</h3>
    </section>
    <?php
        include "./partials/footer.php";