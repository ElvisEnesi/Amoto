<?php
    include "./configuration/constant.php";
    include "./configuration/database.php";
    // secure admin pages 
    if (!isset($_SESSION['user_id'])) {
        header("location: " . root_url . "login.php");
        die();
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
        if (isset($_SESSION['add_category'])) {
            echo "<div class='notice'>";
            echo $_SESSION['add_category'];
            echo "</div>";
        }
        unset($_SESSION['add_category']);
    ?>
    <section class="form">
        <form action="add_category_logic.php" method="post">
            <h1>Add Category</h1>
            <input type="text" name="title" placeholder="Name">
            <textarea name="description" placeholder="Description"></textarea>
            <button type="submit" name="submit">Submit</button>
        </form>
    </section>
</body>
</html>