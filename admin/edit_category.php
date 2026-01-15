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
        $edit_search = "SELECT * FROM category WHERE id=$id";
        $result = mysqli_query($connection, $edit_search);
        $edit = mysqli_fetch_assoc($result);
    }
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
        if (isset($_SESSION['edit_category'])) {
            echo "<div class='notice'>";
            echo $_SESSION['edit_category'];
            echo "</div>";
        }
        unset($_SESSION['edit_category']);
    ?>
    <section class="form">
        <form action="edit_category_logic.php" method="post">
            <h1>Edit Category</h1>
            <input type="hidden" name="id" value="<?= $edit['id'] ?>">
            <input type="text" name="title" value="<?= $edit['title'] ?>">
            <textarea name="description" placeholder="Description"></textarea>
            <button type="submit" name="submit">Submit</button>
        </form>
    </section>
</body>
</html>