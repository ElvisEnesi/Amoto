    <?php 
        include "./configuration/database.php";
        // select all products
        $select_category = "SELECT * FROM category";
        $query_category = mysqli_query($connection, $select_category);
    ?>
    <section class="nav">
        <a href="<?php echo root_url ?>shop.php">Shop All</a>
        <?php while ($category = mysqli_fetch_assoc($query_category)) : ?>
        <a href="<?php echo root_url ?>category.php?id=<?= $category['id'] ?>"><?= $category['title'] ?></a>
        <?php endwhile ?>
    </section>