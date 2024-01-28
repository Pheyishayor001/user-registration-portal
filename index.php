<?php
session_start();

if(!isset($_SESSION['username'])) { // if Username is not stored in the SESSION global variable.
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
}

if (isset($_GET['logout'])) { //upon the click of the logout button log out event is to be triggered.
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    
    <div class="header">
        <h2>Home Page</h2>
    </div>
    <div class="content">
        <!-- notification message -->
        <?php if (isset($_SESSION['success'])) : ?>
            <div class="error success">
                <h3>
                    <?php
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                    ?>
                </h3>
            </div>
        <?php endif ?>

        <!-- logged in user's information -->
        <?php if (isset($_SESSION['username'])) : ?>
            <p>Welcome to our portal <strong><?php echo $_SESSION['username']; ?></strong>! Do have a lovely day 😉!</p>
            <p><a href="index.php?logout='1'" style="color: red">logout</a></p>
        <?php endif ?>
    </div>
</body>
</html>