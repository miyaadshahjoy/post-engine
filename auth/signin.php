<?php 

    require '../config/db.php';

    if(isset($_POST['email']) && 
    isset($_POST['password'])) {

        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE email = :email";

        // statement
        $signinQuery = oci_parse($conn, $sql);

        oci_bind_by_name($signinQuery, ':email', $email);

        $result = oci_execute($signinQuery);
        if(!$result){
            $err = oci_error($signinQuery);
            echo "⭕ Query execution failed: " . $err['message'];
        }

        $user = oci_fetch_assoc($signinQuery);
        
        if($user && password_verify($password, $user['PASSWORD'])) {
            
            // echo $user['EMAIL'] . "<br>";
            echo "✅ Login successful! <br>";

            session_start();

            $_SESSION['id'] = $user['ID'];
            $_SESSION['fullname'] = $user['FULLNAME'];
            $_SESSION['username'] = $user['USERNAME'];
            $_SESSION['email'] = $user['EMAIL'];
            $_SESSION['role_id'] = $user['ROLE_ID'];

            /*
            switch ($user['ROLE_ID']) {
                case 1:
                    # code...
                    header('Location: ../dashboard/admin-dashboard.php');
                    break;
                case 2:
                    # code...
                    header('Location: ../dashboard/moderator-dashboard.php');
                    break;
                case 3:
                    # code...
                    header('Location: ../dashboard/author-dashboard.php');
                    break;
                case 4:
                    # code...
                    header('Location: ../index.php');
                    break;
                
                default:
                    # code...
                    header('Location: ../index.php');
                    break;
            }   
            */        
        } else {
            echo "⭕ Login failed! <br>";
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../public/css/style.css">
        <title>SIGN IN</title>
    </head>
    <body>
        <?php 
            require('./../header.php');
        ?>
        <section class="section">

            <div class="container">
                <div class="form-wrapper">
                
                    <h2 class="form-title">Sign in</h2>
                    <form class="form form-signin" action="signin.php" method="post">
                        <input type="text" name="email" id="" placeholder="Enter email...">
                        <input type="password" name="password" id="" placeholder="Enter password...">
                        <input class="button button-signin" type="submit" value="sign in">
                    </form>
                    <div class="form-link">
                        don't have an account.
                        <a href="http://localhost/post-engine/auth/signup-user.php">sign up</a>
                    </div>
                </div>
            </div>
        </section>
        
    </body>
</html>