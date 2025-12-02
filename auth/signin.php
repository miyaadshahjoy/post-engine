<?php 

    # Database Connection
    require_once '../config/db.php';

    require_once '../app/errors.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST'):

        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email) || empty($password)):
            flash_error("⭕ Email and Password are required");
            header("Location: signin.php");
            exit();
        endif;

        # Query : Get user
        $sql = "SELECT * FROM users WHERE email = :email";

        # Statement
        $signinQuery = oci_parse($conn, $sql);
        
        # Bind Parameters
        oci_bind_by_name($signinQuery, ':email', $email);
        
        # Execute
        $result = oci_execute($signinQuery);

        if(!$result):
            $err = oci_error($signinQuery);
            flash_error("⭕ Query execution failed: " . $err['message']);
            header("Location: signin.php");
            exit();
        endif;
        
        $user = oci_fetch_assoc($signinQuery);

        if (!$user):
            flash_error("⭕ User not found!");
            header("Location: signin.php");
            exit();
        endif;

        if ($user['STATUS'] === 'pending'):
            flash_error("⭕ Your account is not active yet! Please wait for admin approval.");
            header("Location: signin.php");
            exit();
        endif;

        if ($user['STATUS'] === 'removed'):
            flash_error("⭕ Your account is blocked! Please contact admin.");
            header("Location: signin.php");
            exit();
        endif;
        
        if(!$user || ! password_verify($password, $user['PASSWORD'])):
            flash_error("⭕ Invalid email or password.");
            header("Location: signin.php");
            exit();
        endif;

        if (session_status() === PHP_SESSION_NONE):            
            session_start();
        endif;
        
        $_SESSION['id'] = $user['ID'];
        $_SESSION['fullname'] = $user['FULLNAME'];
        $_SESSION['username'] = $user['USERNAME'];
        $_SESSION['email'] = $user['EMAIL'];
        $_SESSION['role_id'] = $user['ROLE_ID'];

        flash_success("✅ Signed in successfully!");
        header("Location: ../index.php");
        exit();
    endif;
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
            require('./../components/layout/header.php');
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

