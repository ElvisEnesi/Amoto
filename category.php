    <?php
        include "./configuration/constant.php";
        include "./configuration/database.php";
        include "./partials/header.php";
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $select_products = "SELECT * FROM products WHERE category_id = ?";
            $stmt = mysqli_prepare($connection, $select_products);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $query_products = mysqli_stmt_get_result($stmt);
        }
    ?>
    <?php
        $category_id = $id;
        $search_category = "SELECT * FROM category WHERE id=?";
        $stmt_category = mysqli_prepare($connection, $search_category);
        mysqli_stmt_bind_param($stmt_category, "i", $category_id);
        mysqli_stmt_execute($stmt_category);
        $query_category = mysqli_stmt_get_result($stmt_category);
        $category = mysqli_fetch_assoc($query_category);
    ?>
    <h2><?= htmlspecialchars($category['title'], ENT_QUOTES, 'UTF-8') ?></h2>
    <?php if (mysqli_num_rows($query_products) > 0) : ?>
    <section class="container">
        <?php while ($product = mysqli_fetch_assoc($query_products)) : ?>
        <div class="card">
            <div class="card_img">
                <img src="./images/items/<?= htmlspecialchars($product['picture'], ENT_QUOTES, 'UTF-8') ?>" onclick="window.location.href='single.php?id=<?= htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8') ?>'">
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
        include "./partials/nav.php";
        include "./partials/footer.php";