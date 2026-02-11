<?php
        include "./configuration/constant.php";
        include "./configuration/database.php";
    // secure admin pages 
    if (!isset($_SESSION['user_id'])) {
        header("location: " . root_url . "login.php");
        die();
    }
    // get id from url
    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];
        $edit_search = "SELECT * FROM products WHERE id=?";
        $stmt = mysqli_prepare($connection, $edit_search);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $edit = mysqli_fetch_assoc($result);
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
        if (isset($_SESSION['edit_item'])) {
            echo "<div class='notice'>";
            echo $_SESSION['edit_item'];
            echo "</div>";
        }
        unset($_SESSION['edit_item']);
    ?>
    <section class="form">
        <form action="edit_item_logic.php" method="post" enctype="multipart/form-data">
            <h1>Edit Item</h1>
            <input type="hidden" name="id" value="<?= htmlspecialchars($edit['id'], ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="previous_image" value="<?= htmlspecialchars($edit['picture'], ENT_QUOTES, 'UTF-8') ?>">
            <input type="text" name="title" value="<?= htmlspecialchars($edit['product'], ENT_QUOTES, 'UTF-8') ?>">
            <input type="number" name="price" value="<?= htmlspecialchars($edit['price'], ENT_QUOTES, 'UTF-8') ?>">
            <select name="category">
                <?php while ($category = mysqli_fetch_assoc($query_category)) : ?>
                <option value="<?= htmlspecialchars($category['id'], ENT_QUOTES, 'UTF-8') ?>" <?= $category['id'] == $edit['category_id'] ? "selected" : "" ?>><?= htmlspecialchars($category['title'], ENT_QUOTES, 'UTF-8') ?></option>
                <?php endwhile ?>
            </select>
            <input type="file" name="avatar">
            <button type="submit" name="submit">Submit</button>
        </form>
    </section>
</body>
</html>