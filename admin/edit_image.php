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
        $edit_search = "SELECT * FROM user WHERE id=$id";
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
        if (isset($_SESSION['edit_image'])) {
            echo "<div class='notice'>";
            echo $_SESSION['edit_image'];
            echo "</div>";
        }
        unset($_SESSION['edit_image']);
    ?>
        <section class="form">
        <form action="edit_image_logic.php" method="post" enctype="multipart/form-data">
            <h1>Edit Profile Picture</h1>
            <input type="hidden" name="id" value="<?= $edit['id'] ?>">
            <input type="hidden" name="previous" value="<?= $edit['picture'] ?>">
            <input type="file" name="avatar">
            <button type="submit" name="submit">Submit</button>
        </form>
    </section>

</body>
</html>