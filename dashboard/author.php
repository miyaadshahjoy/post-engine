<!-- dashboard/author.php  -->
 <?php 
    require('../config/db.php');
    require('./../app/auth.php');
    const USER_ROLE_ID = 3;
    authorize([USER_ROLE_ID]);

    $currentPage = $_GET['page'] ?? 'dashboard';


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
            require('./../components/layout/header.php');
        ?>

        <section class="dashboard">
            <div class="container">
                <div class="dashboard-wrapper">

                    <aside class="dashboard-sidebar">
                        <div class="sidebar-links">
                            <a class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>" 
                            href="author.php?page=dashboard">Dashboard</a>

                            <a class="<?= $currentPage === 'create' ? 'active' : '' ?>flex flex-ai-c" 
                            href="author.php?page=create">New post
                                <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path opacity="0.1" d="M3 12C3 4.5885 4.5885 3 12 3C19.4115 3 21 4.5885 21 12C21 19.4115 19.4115 21 12 21C4.5885 21 3 19.4115 3 12Z" fill="#323232"></path> <path d="M9 12H15" stroke="#323232" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 9L12 15" stroke="#323232" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M3 12C3 4.5885 4.5885 3 12 3C19.4115 3 21 4.5885 21 12C21 19.4115 19.4115 21 12 21C4.5885 21 3 19.4115 3 12Z" stroke="#323232" stroke-width="2"></path> </g></svg>
                            </a>

                            <a class="<?= $currentPage === 'posts' ? 'active' : '' ?>" 
                            href="author.php?page=posts">Posts</a>

                            <a class="<?= $currentPage === 'comments' ? 'active' : '' ?>" 
                            href="author.php?page=comments">Comments</a>

                            <a class="<?= $currentPage === 'settings' ? 'active' : '' ?>" 
                            href="author.php?page=settings">Account Settings</a>
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
                                    break;
                            endswitch;
                            
                                    
                         ?>
                        
                    </main>
                </div>
            </div>
        </section>
    </body>
</html>