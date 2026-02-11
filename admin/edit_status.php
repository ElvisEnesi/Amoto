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
        $edit_search = "SELECT * FROM orders WHERE id=?";
        $stmt = mysqli_prepare($connection, $edit_search);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
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
        if (isset($_SESSION['edit_status_logic'])) {
            echo "<div class='notice'>";
            echo $_SESSION['edit_status_logic'];
            echo "</div>";
        }
        unset($_SESSION['edit_status_logic']);
    ?>
    <section class="form">
        <form action="<?= root_url ?>admin/edit_status_logic.php" method="post">
            <h1>Edit Status</h1>
            <input type="hidden" name="id" value="<?= htmlspecialchars($edit['id'], ENT_QUOTES, 'UTF-8') ?>">
            <select name="category">
                <option value="pending">pending</option>
                <option value="approved">approved</option>
            </select>
            <button type="submit" name="submit">Submit</button>
        </form>
    </section>
</body>
</html>