<?php
// Start session
session_start();

// MySQLi OBJECT ORIENTED METHOD
$serverName = "localhost";
$serverUsername = "root";
$serverPassword = "";
$dbName = "registeration";

// initializing variables to prevent error message of undefined variables
$username = "";
$email = "";
$password = "";
$errors = array();

// create connection to server
$conn = new mysqli($serverName, $serverUsername, $serverPassword, $dbName);

// check if connection successful.
$conn->connect_error ? die("Connection failed: " . $conn->connect_error . "<br>") : " ";

// Create DB called "registeration" and check if creation is successful
$sql = "CREATE DATABASE registeration";

// echo $conn->query($sql) ? "Database created successfuly <br>" : die("Error creating database: " . $conn->error) . "<br>";


// Create Table "users"
$sqlTable = "CREATE TABLE users(
id INT(11) AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(100) NOT NULL,
email VARCHAR(100) NOT NULL,
password VARCHAR(100) NOT NULL
)";

// Create table
// echo $conn->query($sqlTable) ? "Table USERS created successfully" : die("Error creating table: " . $conn->error);

///////////////////////////////////////////
// HELPER FUNCTIONS
function toLowerC ($string) {
    $lowerCase = strtolower($string);
    return $lowerCase;
}
function trimInput ($string) {
    return trim($string);
}
function cap_first_letter($data) {
    $capitalize_data = ucwords($data);
    return $capitalize_data;
}
///////////////////////////////////////////

//////////////////////////////////////////
// REGISTER USER
if (isset($_POST['reg_user'])) {
    $raw_username = mysqli_real_escape_string($conn, $_POST['username']);
    $raw_email = mysqli_real_escape_string($conn, $_POST['email']);
    $password_1 = mysqli_real_escape_string($conn, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($conn, $_POST['password_2']);

    // convert input to lowercase
    $username = toLowerC($raw_username);
    $email = toLowerC($raw_email);

    // check if fields are empty and if characters other than alphabets and white space are inputted.
    if (empty($username)) {
     array_push($errors, "Username is required");
    } else if (!preg_match("/^[a-zA-Z ]*$/", $username)) { 
        //ensure username input is only letters
        array_push($errors, "Only letters and white space allowed");
    }

   if (empty($email)) {
    array_push($errors, "Email is required");
    }  else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //ensure email input is valid
        array_push($errors, "invalid email format");
    }

    //    check if either password 1 or 2 is < 6 characters.
   strlen($password_1) < 6 || strlen($password_2) < 6 ? array_push($errors, "Password must be at least 6 characters") : " ";

   $password_1 !== $password_2 ? array_push($errors, "The two passwords do not match") : " ";

   // check DB to make sure another user does not exist with the same username and/or password.
    $user_check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email' LIMIT 1";

    $result = mysqli_query($conn, $user_check_query);

    $user = mysqli_fetch_assoc($result); //returns an array of the user data.

    if ($user) { //if user already exist

        $user["username"] === $username ? array_push($errors, "Username already exists") : " ";

        $user["email"] === $email ? array_push($errors, "Email already exists") : " ";
        
    }
} 

// Register user if there is no error
// FORM SUBMITTED? && NO ERROR? === EXECUTE CODE BELOW.
if (  isset($_POST['reg_user']) && count($errors) === 0) {
    // encrypt password before saving to database
   $password = password_hash($password_1, PASSWORD_DEFAULT);

   $query = "INSERT INTO users (username, email, password) 
    VALUES('$username', '$email', '$password')";
    mysqli_query($conn, $query);

    // Capitalize first character(s) in the username before displaying it on UI
    $cap_username = cap_first_letter($username);

    $_SESSION['username'] = $cap_username;
    $_SESSION['success'] = "Great job!!! You have sucessfully registered and now logged in 😊.";

    // Upon successful completion and addition to DB, move on to index.php page (Home Page)
    header('location: index.php');
}

///////////////////////////////////////
// LOGIN USER

//Upon submition check if fields are filled
if (isset($_POST["login_user"])) {
    // Get username and password from user.
    $raw_username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    //Trim input by cutting out extra space.
    $trimed_username = trimInput($raw_username);
    $password = trimInput($password);

    // convert username to lowercase
    $username = toLowerC($trimed_username);

    // Check if either field is empty.
    empty($username) ? array_push($errors, "Username is required") : ' ';
    empty($password) ? array_push($errors, "Password is required") : ' ';

    // FORM FILLED ? Check if no error exists in the $errors array
    if (count($errors) === 0) {
        // Search user in DB.
        $query = "SELECT * FROM users WHERE username='$username'";
        $results = mysqli_query($conn, $query);

        if(mysqli_num_rows($results)) { //if user exist in DB return their details. Else, user      doesn't exist and needs to signup.
            $user = mysqli_fetch_assoc($results);
            
            if ($user && password_verify($password, $user['password'])) { //verify password above if it corresponds with the user's password on the DB. If it does store their details in the SESSIONS Global variable and move on to Home Page

                // Capitalize first character(s) in the username before displaying it on UI
                $cap_username = cap_first_letter($username);

                $_SESSION['username'] = $cap_username;
                $_SESSION['success'] = "You are now logged in 😊.";

                
                header('location: index.php');
                
            } else {
                array_push($errors, "Wrong username/password combination");
            }
        } else {
            array_push($errors, "Username does not exist. Register as a new user by clicking the Sign up button below");
        }   
    } 

}


$conn->close();
?>