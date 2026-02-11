<?php
        include "./configuration/constant.php";
        include "./configuration/database.php";
    // secure admin pages 
    // ip address function
    function get_ip_address() {
        // declare ip_address
        $ip_address = '';
        // check various headers for potential ip address
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }  elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip_address = 'UNKNOWN';
        }
        return $ip_address;
    }
    // ip address
    $user_ip = get_ip_address();
    // get id from url
    if (isset($_GET['id']) && isset($_SESSION['user_id']) && isset($_SESSION['i_am_admin'])) {
        $id = (int) $_GET['id'];
        $edit_search = "SELECT * FROM category WHERE id=?";
        $stmt = mysqli_prepare($connection, $edit_search);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $edit = mysqli_fetch_assoc($result);
    } else {
        // insert into unauthorized
        $unauthorized = mysqli_prepare($connection, "INSERT INTO unauthorized (ip_address) VALUES(?)");
        mysqli_stmt_bind_param($unauthorized, "s", $user_ip);
        mysqli_stmt_execute($unauthorized);
        // redirect
        header("location: " . root_url . "kick_you_out.php");
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
        if (isset($_SESSION['edit_category'])) {
            echo "<div class='notice'>";
            echo $_SESSION['edit_category'];
            echo "</div>";
        }
        unset($_SESSION['edit_category']);
    ?>
    <section class="form">
        <form action="<?= root_url ?>admin/edit_category_logic.php?id=<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>" method="post">
            <h1>Edit Category</h1>
            <input type="text" name="title" value="<?= htmlspecialchars($edit['title'], ENT_QUOTES, 'UTF-8') ?>">
            <textarea name="description" placeholder="Description"><?= htmlspecialchars($edit['description'], ENT_QUOTES, 'UTF-8') ?></textarea>
            <button type="submit" name="submit">Submit</button>
        </form>
    </section>
</body>
</html>