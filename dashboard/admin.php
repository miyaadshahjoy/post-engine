<?php 
    require('../config/db.php');
    require('./../app/auth.php');
    const USER_ROLE_ID = 1;
    authorize([USER_ROLE_ID]);

    $currentPage = $_GET['page'] ?? 'dashboard';
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../public/css/style.css">
        <title>ADMIN DASHBOARD</title>
    </head>
    <body>
    <?php 
        require('./../components/layout/header.php');
    ?>

        <section class="dashboard">
            <div class="container">
                <div class="dashboard-wrapper">

                    <aside class="dashboard-sidebar">
                        <div class="sidebar-links">
                            <a class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>" 
                            href="admin.php?page=dashboard">Dashboard</a>

                            <a class="<?= $currentPage === 'users' ? 'active' : '' ?>" 
                            href="admin.php?page=users">Users</a>

                            <a class="<?= $currentPage === 'posts' ? 'active' : '' ?>" 
                            href="admin.php?page=posts">Posts</a>

                            <a class="<?= $currentPage === 'comments' ? 'active' : '' ?>" 
                            href="admin.php?page=comments">Comments</a>

                            <a class="<?= $currentPage === 'settings' ? 'active' : '' ?>" 
                            href="admin.php?page=settings">Account Settings</a>
                        </div>
                    </aside>
                         
                    <main class="main author-main">
                        <!-- components here  -->
                        <?php 
                            $page = $_GET['page'] ?? 'dashboard';

                            switch ($page):
                                case 'dashboard':
                                    require('../components/admin/dashboard-overview.php');
                                    break;
                                case 'users':
                                    require('../components/admin/userslist.php');
                                    break;

                                case 'posts':
                                    require('../components/admin/postslist.php');
                                    break;
                                    
                                case 'update':
                                    require('../posts/update.php');
                                    break;

                                case 'settings':
                                    $id = $_SESSION['id'];
                                    require('../components/admin/profile-setting.php');
                                    break;
                                default:
                                    break;
                            endswitch;
                            
                                    
                        ?>
                        
                    </main>
                </div>
            </div>
        </section>
    
    </body>
</html>