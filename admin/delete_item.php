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
        <section class="form">
        <form>
            <h1>Delete Product??</h1>
            <div class="links">
                <a href="<?= root_url ?>admin/delete_item_logic.php?id=<?= htmlspecialchars($edit['id'], ENT_QUOTES, 'UTF-8') ?>">Yes</a>
                <a href="manage_items.php">No</a>
            </div>
        </form>
    </section>

</body>
</html>