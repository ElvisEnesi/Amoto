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
        <section class="form">
        <form>
            <h1>Log out??</h1>
            <div class="links">
                <a href="logout_logic.php">Yes</a>
                <a href="dashboard.php">No</a>
            </div>
        </form>
    </section>

</body>
</html>