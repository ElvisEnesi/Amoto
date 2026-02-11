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
            <h1>Delete Cart??</h1>
            <div class="links">
                <a href="<?= root_url ?>admin/delete_cart_logic.php?id=<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>">Yes</a>
                <?php if (isset($_SESSION['i_am_admin'])) : ?>
                    <a href="<?= root_url ?>admin/manage_carts.php">No</a>
                <?php else : ?>
                    <a href="<?= root_url ?>admin/cart.php">No</a>
                <?php endif ?>
            </div>
        </form>
    </section>

</body>
</html>