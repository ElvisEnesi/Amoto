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
        if (isset($_SESSION['edit_password'])) {
            echo "<div class='notice'>";
            echo $_SESSION['edit_password'];
            echo "</div>";
        }
        unset($_SESSION['edit_password']);
    ?>
    <section class="form">
        <form action="edit_password_logic.php" method="post">
            <h1>Edit Password</h1>
            <input type="hidden" name="id" value="<?= $edit['id'] ?>">
            <input type="hidden" name="password" value="<?= $edit['password'] ?>">
            <input type="password" name="current" placeholder="Current Password">
            <input type="password" name="create" placeholder="Create password">
            <input type="password" name="confirm" placeholder="Confirm password">
            <button type="submit" name="submit">Submit</button>
        </form>
    </section>
</body>
</html>