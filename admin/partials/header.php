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
    $select = "SELECT * FROM user WHERE id=$current_user";
    $result = mysqli_query($connection, $select);
    $user = mysqli_fetch_assoc($result);
    // count carts
    $count_cart = "SELECT COUNT(*) AS total_cart FROM cart WHERE status = 'active' AND customer_id=$current_user";
    $result_cart = mysqli_query($connection, $count_cart);
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
            <a href="dashboard.php"><img src="../images/users/<?php echo $user['picture']; ?>"></a>
            <a href="cart.php"><ion-icon name="cart-outline"></ion-icon>(<?= $total_active_carts ?>)</a>
            <?php else : ?>
            <a href="<?php echo root_url ?>login.php">Log in</a>
            <?php endif ?>
        </div>
    </section>