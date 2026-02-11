<?php
    include "./configuration/constant.php";
    include "./configuration/database.php";
    // secure admin pages 
    if (!isset($_SESSION['user_id'])) {
        header("location: " . root_url . "login.php");
        die();
    }
    // select all categories
    $select_category = "SELECT * FROM category";
    $query_category = mysqli_query($connection, $select_category);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php  
        if (isset($_SESSION['add_item'])) {
            echo "<div class='notice'>";
            echo $_SESSION['add_item'];
            echo "</div>";
        }
        unset($_SESSION['add_item']);
    ?>
    <section class="form">
        <form action="add_item_logic.php" method="post" enctype="multipart/form-data">
            <h1>Add Item</h1>
            <input type="text" name="title" placeholder="Product name">
            <input type="number" name="price" placeholder="Price">
            <select name="category">
                <?php while ($category = mysqli_fetch_assoc($query_category)) : ?>
                <option value="<?= htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($category['title'], ENT_QUOTES, 'UTF-8') ?></option>
                <?php endwhile ?>
            </select>
            <input type="file" name="avatar">
            <button type="submit" name="submit">Submit</button>
        </form>
    </section>
</body>
</html>