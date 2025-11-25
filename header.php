<!-- header.php  -->

<?php

    // require './config/db.php';
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    
    $role = '';

    if(isset($_SESSION['role_id'])) {
        $role_id = (int) $_SESSION['role_id'];

        switch ($role_id) {
            case 1:
                # code...
                $role = 'admin';
                break;
            case 2:
                # code...
                $role = 'moderator';
                break;
            case 3:
                # code...
                $role = 'author';
                break;
            
            default:
                # code...

                break;
        }
    }

    if(isset($_SESSION['id'])):
        $fullname = $_SESSION['fullname'];
        
        $firstname = explode(' ', $fullname)[0];
        $user_id = $_SESSION['id'];

        $sql = "SELECT image FROM users WHERE id = :id";
        $statement = oci_parse($conn, $sql);
        oci_bind_by_name($statement, ':id', $user_id);
        $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);
        if(!$result):
            $err = oci_error($statement);
            die("⭕ Error fetching user: " . $err['message']);
        endif;
        $image = oci_fetch_assoc($statement)['IMAGE'];

        if($image === null):
            $user_image = 'user.svg';
        else:
            $user_image = $image;
        endif;
    endif;

?>

<header class="header">
    <div class="container">
        <div class="header-wrapper">

            <a class="header-logo" href='http://localhost/post-engine/index.php'>Post Engine</a>
            <div class="header-links">
            
                <!-- Visible only when logged in and not a viewer -->
                <?php if(isset($_SESSION['id']) && $role_id !== 4): ?>
                    <a href='http://localhost/post-engine/dashboard/<?= $role?>.php'>Dashboard</a>
                <?php endif; ?>
                
                
                    
                

                <!-- visible only when logged in  -->
                <?php if(isset($_SESSION['id'])): ?>
                    <a href="http://localhost/post-engine/auth/logout.php">Logout</a>

                    <?php if($role_id !== 4): ?>
                        <a href='#' class="user-link">
                            <img src="http://localhost/post-engine/images/users/<?= $user_image?>" alt="user image" class="user-image">
                            <span><?= $firstname ?></span>
                        </a>
                    <?php endif; ?>


                    
                <?php endif; ?>

                <!-- visible only when logged in and a viewer -->
                 <?php if(isset($_SESSION['id']) && $role_id === 4): ?>
                    <a href='http://localhost/post-engine/users/profile.php?id=<?= $_SESSION['id']; ?>' class="user-link">
                        <img src="http://localhost/post-engine/images/users/<?= $user_image?>" alt="user image" class="user-image">
                        <span><?= $firstname ?></span>
                    </a>
                <?php endif; ?>
                   
                <!-- visible only when not logged in  -->
                <?php if(!isset($_SESSION['id'])): ?>
                    <a href='http://localhost/post-engine/auth/signin.php'>Sign in</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
