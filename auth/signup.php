<?php 

    # Database Connection
    require_once '../config/db.php';

    
    if($_SERVER['REQUEST_METHOD'] == 'POST'):

        $fullname = $_POST['fullname'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];
        $role_id = (int) $_POST['role_id'];
        
        if(empty($fullname) || empty($username) || empty($email) || empty($password) || empty($password_confirm) || empty($role_id)):
            die("⭕ All fields are required!");
        endif;

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)):
            die("⭕ Invalid email format!");
        endif;

        if($password !== $password_confirm):
             die("⭕ Passwords do not match!");
        endif;

        # Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        # Query : Create user
        $sql = "INSERT INTO users (fullname, username, email, password, role_id) VALUES (:fullname, :username, :email, :password, :role_id)";


        // statement
        $signupQuery = oci_parse($conn, $sql);

        # Bind parameters
        oci_bind_by_name($signupQuery, ':fullname', $fullname);
        oci_bind_by_name($signupQuery, ':username', $username);
        oci_bind_by_name($signupQuery, ':email', $email);
        oci_bind_by_name($signupQuery, ':password', $hashedPassword);
        oci_bind_by_name($signupQuery, ':role_id', $role_id);

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
                <form class="form form-signup" action="signup.php" method="post">
                    <h2 class="form-title">Sign up</h2>
                    <input type="text" name="fullname" id="" placeholder="Enter fullname...">
                    <input type="text" name="username" id="" placeholder="Enter username...">
                    <input type="email" name="email" id="" placeholder="Enter email...">
                    <label for="role_id" style="margin-bottom: -18px; ">Role</label>
                    <select name="role_id" id="">
                        <option value="2">moderator</option>
                        <option value="3">author</option>
                    </select>
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