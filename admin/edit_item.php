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
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $edit_search = "SELECT * FROM products WHERE id=$id";
        $result = mysqli_query($connection, $edit_search);
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
            <input type="hidden" name="id" value="<?= $edit['id'] ?>">
            <input type="hidden" name="previous_image" value="<?= $edit['picture'] ?>">
            <input type="text" name="title" value="<?= $edit['product'] ?>">
            <input type="number" name="price" value="<?= $edit['price'] ?>">
            <select name="category">
                <?php while ($category = mysqli_fetch_assoc($query_category)) : ?>
                <option value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
                <?php endwhile ?>
            </select>
            <input type="file" name="avatar">
            <button type="submit" name="submit">Submit</button>
        </form>
    </section>
</body>
</html>