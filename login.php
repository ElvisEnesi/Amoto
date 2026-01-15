<?php
        include "./configuration/constant.php";
        include "./configuration/database.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppy</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php  
        if (isset($_SESSION['login'])) {
            echo "<div class='notice'>";
            echo $_SESSION['login'];
            echo "</div>";
        }
        unset($_SESSION['login']);
    ?>
    <?php  
        if (isset($_SESSION['sign_up_success'])) {
            echo "<div class='notice'>";
            echo $_SESSION['sign_up_success'];
            echo "</div>";
        }
        unset($_SESSION['sign_up_success']);
    ?>
    <?php  
        if (isset($_SESSION['delete_success'])) {
            echo "<div class='notice'>";
            echo $_SESSION['delete_success'];
            echo "</div>";
        }
        unset($_SESSION['delete_success']);
    ?>
    <section class="form">
        <form action="login_logic.php" method="post">
            <h1>Login</h1>
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password">
            <button type="submit" name="submit">Submit</button>
        </form>
        <div class="note">
            Don't have an account? <a href="<?php echo root_url ?>signup.php">Sign up!!</a>
        </div>
    </section>
</body>
</html>