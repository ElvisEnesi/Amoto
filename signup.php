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
        if (isset($_SESSION['sign_up'])) {
            echo "<div class='notice'>";
            echo $_SESSION['sign_up'];
            echo "</div>";
        }
        unset($_SESSION['sign_up']);
    ?>
    <section class="form">
        <form action="signup_logic.php" method="post" enctype="multipart/form-data">
            <h1>Sign up</h1>
            <input type="text" name="fname" placeholder="First name">
            <input type="text" name="lname" placeholder="Last name">
            <input type="text" name="username" placeholder="Username">
            <input type="email" name="email" placeholder="Email">
            <input type="text" name="address" placeholder="Campus Address">
            <input type="password" name="create" placeholder="Create password">
            <input type="password" name="confirm" placeholder="Confirm password">
            <input type="file" name="avatar">
            <button type="submit" name="submit">Submit</button>
        </form>
        <div class="note">
            Already have an account? <a href="<?php echo root_url ?>login.php">Login!!</a>
        </div>
    </section>
</body>
</html>