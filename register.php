<?php include("server.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resisteration System using PHP and MySQL</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <h2>Register User</h2>
    </div>

    <form method="post" action="register.php">
        <!-- to display error msgs upon error -->
        <?php include('errors.php'); ?> 
        
        <div class="input-group">
            <label>Username: </label>
            <input type="text" name="username" value="<?php echo $username;  ?>">
        </div>
        <div class="input-group">
            <label for="">Email: </label>
            <input type="email" name="email" value="<?php echo $email;  ?>">
        </div>
        <div class="input-group">
            <label for="">Password: </label>
            <input type="password" name="password_1" value="">
        </div>
        <div class="input-group">
            <label for="">Confirm Password: </label>
            <input type="password" name="password_2" value="">
        </div>

        <!-- submit button -->
        <div class="input-group">
            <button type="submit" class="btn input-group" name="reg_user">Register</button>
        </div>

        <!-- already a member? -->
        <p>
            Already a member? <a href="login.php">Sign in</a>
        </p>
    </form>



</body>
</html>