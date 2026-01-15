    <?php
        include "./configuration/database.php";
        include "./partials/header.php";
        include "./partials/nav.php";
        // // search
        // if (isset($_GET['search']) && isset($_GET['submit'])) {
        //     $search = filter_var($_GET['search'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        //     $search_select = "SELECT * FROM products WHERE product LIKE '%$search%'";
        //     $search_query = mysqli_query($connection, $search_select);
        // } else {
        //     header("location: " . root_url . "index.php");
        //     die();
        // }
    ?>
    <?php //if (mysqli_num_rows($search_query) > 0) : ?>
    <section class="cart">
        <?php //while ($gotten = mysqli_fetch_assoc($search_query)) : ?>
        <div class="cart_item">
            <div class="cart_img">
                <img src="./images/items/<?= $gotten['picture'] ?>" onclick="window.location.href=''" alt="">
            </div>
            <div class="cart_info">
                <h3>Title</h3>
                <p>$45</p>
                <form action="" method="post">
                    <input type="hidden" name="name">
                    <input type="hidden" name="price">
                    <label for="qty">Quantity</label>
                    <input type="number" name="qty">
                    <br><br>
                    <button type="submit">Add to cart!!</button>
                </form>
            </div>
        </div>
        <?php //endwhile ?>
    </section>
    <?php //else : ?>
    <div class="notice">
        No data to display!!
    </div>
    <?php //endif ?>
    <?php
        include "./partials/footer.php";