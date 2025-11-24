<?php 

    require_once '../config/db.php';

    if(isset($_POST['fullname']) &&
    isset($_POST['username']) && 
    isset($_POST['email']) && 
    isset($_POST['password']) && 
    isset($_POST['password_confirm'])) {

        $fullname = $_POST['fullname'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];
        $role_id = 4;
        $status = 'active';

        if($password !== $password_confirm) {
            echo "⭕ Passwords do not match! <br>";
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (fullname, username, email, password, role_id, status) VALUES (:fullname, :username, :email, :password, :role_id, :status)";

        // statement
        $signupQuery = oci_parse($conn, $sql);

        oci_bind_by_name($signupQuery, ':fullname', $fullname);
        oci_bind_by_name($signupQuery, ':username', $username);
        oci_bind_by_name($signupQuery, ':email', $email);
        oci_bind_by_name($signupQuery, ':password', $hashedPassword);
        oci_bind_by_name($signupQuery, ':role_id', $role_id);
        oci_bind_by_name($signupQuery, ':status', $status);

        $result =oci_execute($signupQuery);

        if($result){
            echo "✅ User created successfully! <br>";
           
        } else {
            $err = oci_error($signupQuery);
            echo "⭕ Error creating user: " . $err['message'] . "<br>";
        }

    }
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
            require('./../header.php');
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