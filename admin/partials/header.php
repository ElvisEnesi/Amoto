<?php
    include "./configuration/constant.php";
    include "./configuration/database.php";
    // secure admin pages 
    if (!isset($_SESSION['user_id'])) {
        header("location: " . root_url . "login.php");
        die();
    }
    // select user details
    $current_user = $_SESSION['user_id'];
    $select = "SELECT * FROM user WHERE id=?";
    $stmt = mysqli_prepare($connection, $select);
    mysqli_stmt_bind_param($stmt, "i", $current_user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    // count carts
    $count_cart = "SELECT COUNT(*) AS total_cart FROM cart WHERE status = ? AND customer_id=?";
    $stmt_cart = mysqli_prepare($connection, $count_cart);
    $status = "active";
    mysqli_stmt_bind_param($stmt_cart, "si", $status, $current_user);
    mysqli_stmt_execute($stmt_cart);
    $result_cart = mysqli_stmt_get_result($stmt_cart);
    $total_active_carts = 0;
    if ($result_cart && $row = mysqli_fetch_assoc($result_cart)) {
        $total_active_carts = $row['total_cart'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppy</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <section class="header">
        <form action="<?= root_url ?>search.php" method="GET">
            <div class="search">
                <input type="search" name="search" placeholder="search...">
                <button type="submit" name="submit">Search</button>
            </div>
        </form>
        <div onclick="window.location.href='index.php'" class="logo">SHOPPY..</div>
        <div class="socials">
            <a onclick="alert('URL unavailable')" href=""><ion-icon name="logo-facebook"></ion-icon></a>
            <a onclick="alert('URL unavailable')" href=""><ion-icon name="logo-instagram"></ion-icon></a>
            <a onclick="alert('URL unavailable')" href=""><ion-icon name="logo-twitter"></ion-icon></a>
            <a onclick="alert('URL unavailable')" href=""><ion-icon name="logo-pinterest"></ion-icon></a>
        </div>
        <div>
            <?php if (isset($_SESSION['user_id'])) : ?>
            <a href="dashboard.php"><img src="../images/users/<?php echo htmlspecialchars($user['picture'], ENT_QUOTES, 'UTF-8'); ?>"></a>
            <a href="cart.php"><ion-icon name="cart-outline"></ion-icon>(<?= htmlspecialchars($total_active_carts, ENT_QUOTES, 'UTF-8') ?>)</a>
            <?php else : ?>
            <a href="<?php echo root_url ?>login.php">Log in</a>
            <?php endif ?>
        </div>
    </section>