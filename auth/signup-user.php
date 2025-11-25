<?php 

    # Database Connection
    require_once '../config/db.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST'):

        # Form validation
        $fullname = $_POST['fullname'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];

        if(empty($fullname) || empty($username) || empty($email) || empty($password) || empty($password_confirm)):
            die( "⭕ All fields are required!");
        endif;

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)):
            die("⭕ Invalid email format!");
        endif;

        $role_id = 4; # viewer 
        $status = 'active'; 

        if($password !== $password_confirm):
             die("⭕ Passwords do not match!");
        endif;

        # Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        # Query : Create user
        $sql = "INSERT INTO users (fullname, username, email, password, role_id, status) VALUES (:fullname, :username, :email, :password, :role_id, :status)";

        # Statement
        $signupQuery = oci_parse($conn, $sql);

        # Bind parameters
        oci_bind_by_name($signupQuery, ':fullname', $fullname);
        oci_bind_by_name($signupQuery, ':username', $username);
        oci_bind_by_name($signupQuery, ':email', $email);
        oci_bind_by_name($signupQuery, ':password', $hashedPassword);
        oci_bind_by_name($signupQuery, ':role_id', $role_id);
        oci_bind_by_name($signupQuery, ':status', $status);

        # Execute query
        $result =oci_execute($signupQuery);

        if(!$result):
            $err = oci_error($signupQuery);
            echo "⭕ Error creating user: " . $err['message'] . "<br>";
        endif;

    endif;

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../public/css/style.css">
        <title>SIGN UP</title>
    </head>
    <body>

        <?php 
            require('./../components/layout/header.php');
        ?>
        
        <div class="container">
            <div class="form-wrapper">
                <form class="form form-signup" action="signup-user.php" method="post">
                    <h2 class="form-title">Sign up</h2>
                    <input type="text" name="fullname" id="" placeholder="Enter fullname...">
                    <input type="text" name="username" id="" placeholder="Enter username...">
                    <input type="email" name="email" id="" placeholder="Enter email...">
                    <input type="password" name="password" id="" placeholder="Enter password...">
                    <input type="password" name="password_confirm" id="" placeholder="Confirm password...">
                    <input class="button button-signup" type="submit" value="sign up">
                </form>
                <div class="form-link">
                    already have an account.
                    <a href="http://localhost/post-engine/auth/signin.php">sign in</a>
                </div>
            </div>
        </div>
    </body>
</html>