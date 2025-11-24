<!-- dashboard/author.php  -->
 <?php 
    require('../config/db.php');
    require('./../middlewares/auth.php');
    const USER_ROLE_ID = 3;
    authorize([USER_ROLE_ID]);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../public/css/style.css">
        <title>DASHBOARD</title>
    </head>
    <body>
        <?php 
            require('./../header.php');
        ?>

        <section class="dashboard">
            <div class="container">
                <div class="dashboard-wrapper">

                    <aside class="dashboard-sidebar">
                        <div class="sidebar-links">
                            <a href="author.php?page=dashboard">Dashboard</a>
                            <a href="author.php?page=create">New post ➕</a>
                            <a href="author.php?page=posts">Posts</a>
                            <a href="author.php?page=comments">Comments</a>
                            <a href="author.php?page=settings">Account Settings</a>

                        </div>
                        
                    </aside>                         
                    <main class="main author-main">
                        <!-- components here  -->
                         <?php 
                            $page = $_GET['page'] ?? 'dashboard';

                            switch ($page):
                                case 'dashboard':
                                    require('../components/author/dashboard-overview.php');
                                    break;
                                case 'create':

                                    require('../posts/create.php');
                                    break;

                                case 'posts':
                                    require('../components/author/postslist.php');
                                    break;
                                    
                                case 'update':
                                    require('../posts/update.php');
                                    break;

                                case 'settings':
                                    $id = $_SESSION['id'];
                                    require('../components/author/profile-setting.php');
                                    break;
                                default:
                                    # code...
                                    break;
                            endswitch;
                            
                                    
                         ?>
                        
                    </main>
                </div>
            </div>
        </section>
    </body>
</html>