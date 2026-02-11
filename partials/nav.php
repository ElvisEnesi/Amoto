    <?php 
        include "./configuration/database.php";
        // select all products
        $select_category = "SELECT * FROM category";
        $query_category = mysqli_query($connection, $select_category);
    ?>
    <section class="nav">
        <a href="<?php echo root_url ?>shop.php">Shop All</a>
        <?php while ($category = mysqli_fetch_assoc($query_category)) : ?>
        <a href="<?php echo root_url ?>category.php?id=<?= htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($category['title'], ENT_QUOTES, 'UTF-8') ?></a>
        <?php endwhile ?>
    </section>