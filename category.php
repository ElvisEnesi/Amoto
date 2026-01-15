    <?php
        include "./configuration/constant.php";
        include "./configuration/database.php";
        include "./partials/header.php";
        if (isset($_GET['id'])) {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            $select_products = "SELECT * FROM products WHERE category_id = $id";
            $query_products = mysqli_query($connection, $select_products);
        }
    ?>
    <?php
        $category_id = $id;
        $search_category = "SELECT * FROM category WHERE id=$category_id";
        $query_category = mysqli_query($connection, $search_category);
        $category = mysqli_fetch_assoc($query_category);
    ?>
    <h2><?= $category['title'] ?></h2>
    <?php if (mysqli_num_rows($query_products) > 0) : ?>
    <section class="container">
        <?php while ($product = mysqli_fetch_assoc($query_products)) : ?>
        <div class="card">
            <div class="card_img">
                <img src="./images/items/<?= $product['picture'] ?>" onclick="window.location.href='single.php?id=<?= $product['id'] ?>'">
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
        include "./partials/nav.php";
        include "./partials/footer.php";