<?php 

    # Database Connection
    require('./config/db.php');


    # Query : Fetch all published posts
    // $sql = "SELECT p.id, p.title, p.image, c.name AS categories, p.author, p.created_at FROM posts p JOIN categories c ON p.categories_id = c.id WHERE p.status = 'published' ORDER BY p.created_at DESC";
    $sql = "SELECT COUNT(*) AS total_posts FROM posts WHERE status = 'published'";
    # statement
    $statement = oci_parse($conn, $sql);
    # Execute
    $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);
    if(!$result):
        $err = oci_error($statement);
        echo "⭕ Query execution failed: " . $err['message'];
    endif;
    $posts_count = (int) oci_fetch_assoc($statement)['TOTAL_POSTS'];


    # Query : Fetch all featured posts
    $sql = "SELECT p.id, p.title, p.image, c.name AS categories FROM posts p JOIN categories c ON p.categories_id = c.id WHERE p.featured = 1 AND p.status = 'published' ORDER BY p.created_at DESC";

    # Statement
    $statement = oci_parse($conn, $sql);

    # Execute
    $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);
    if(!$result):
        $err = oci_error($statement);
        echo "⭕ Query execution failed: " . $err['message'];
    endif;

    $featured_count =oci_fetch_all($statement, $featured_posts, 0, -1, OCI_FETCHSTATEMENT_BY_ROW); 

    # Implementing pagination

    # Pagination settings
    const POST_PER_PAGE = 9;

    $limit = POST_PER_PAGE;     
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $page = max($page, 1);
    $offset = ($page - 1) * $limit;
    $total_posts = $posts_count;
    $total_pages = ceil($total_posts / $limit);

    # Fetch paginated posts

    $sql = "SELECT * FROM (
                SELECT p.id, p.title, p.image, c.name AS categories, p.author, p.created_at, 
                ROW_NUMBER() OVER (ORDER BY p.created_at DESC) AS rn
                FROM posts p JOIN categories c 
                ON p.categories_id = c.id 
                WHERE p.status = 'published' 
                )
            WHERE rn BETWEEN :row_start AND :row_end";

    # Statement 
    $statement = oci_parse($conn, $sql);
    $start = $offset + 1;
    $end = $offset + $limit;
    
    oci_bind_by_name($statement, ':row_start', $start);
    oci_bind_by_name($statement, ':row_end', $end);

    # Execute
    $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);
    if(!$result):
        $err = oci_error($statement);
        echo "⭕ Query execution failed: " . $err['message'];
    endif;
    oci_fetch_all($statement, $posts, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="public/css/style.css">        
        <title>Post Engine</title>
    </head>
    <body>
        <!-- Header -->
        <?php 
            require('./header.php');
        ?>
        
        <!-- Hero -->
        <section class="hero">
            <div class="container">
                <div class="hero-wrapper">
                    <div class="hero-content">
                        <div class="hero-category">Marketing</div>
                        <h1 class="hero-heading">The Majesty of the Himalayas: Where Earth Touches the Sky</h1>
                    </div>

                    <!-- Featured posts  -->
                    <div class="featured">
                        <h2 class="featured-heading">Other featured posts</h2>

                        <?php 
                            $limit = $featured_count > 5 ? 5 : $featured_count;
                            for($i = 0; $i < $limit; $i++):
                                $post_id = $featured_posts[$i]['ID'];
                                $post_title = $featured_posts[$i]['TITLE'];
                                $post_image = $featured_posts[$i]['IMAGE'];
                                $post_category = $featured_posts[$i]['CATEGORIES'];
                        ?>
                            <div class="featured-post">
                                <div class="post-content">
                                    <img src="images/<?= $post_image ?>" alt=<?= $post_title?> class="featured-image">
                                    <div class="featured-text">
                                        <a class="featured-title" href="http://localhost/post-engine/post.php?id=<?= $post_id ?>"><?= $post_title ?></a>
                                        <div class="featured-category"><?= $post_category ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Posts -->
        <section class="posts">
            <div class="container">
                <h2 class="posts-heading">Recent Posts</h2>
                <div class="posts-wrapper">

                    <?php 
                        foreach($posts as $post):
                            $post_id = $post['ID'];
                            $post_title = $post['TITLE'];
                            $post_image = $post['IMAGE'];
                            $post_category = $post['CATEGORIES'];
                            $post_author = $post['AUTHOR'];
                            $post_date = DateTime::createFromFormat('d-M-y h.i.s.u A', $post['CREATED_AT'])->format('M d, Y');

                            $sql = "SELECT fullname, image FROM users WHERE username = :username";
                            $statement = oci_parse($conn, $sql);
                            oci_bind_by_name($statement, ':username', $post_author);
                            $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);
                            if(!$result):
                                $err = oci_error($statement);
                                echo "⭕ Query execution failed: " . $err['message'];
                            endif;
                            $author = oci_fetch_assoc($statement);
                            $post_author_fullname = $author['FULLNAME'];
                            $post_author_image = $author['IMAGE'];
                    ?>
                        <div class="post-card">
                            <div class="post-card-categories"><?= $post_category ?></div>
                            <img src="images/<?= $post_image ?>" alt=<?= $post_title ?> class="post-card-image">
                            <div class="post-card-content">
                                <div class="post-card-date">
                                    <?= $post_date ?>
                                </div>
                                <a class="post-card-title" href="http://localhost/post-engine/post.php?id=<?= $post_id ?>">
                                    <?= $post_title ?>
                                </a>
                                <div class="post-card-author">
                                    <?php if ($post_author_image !== null): ?>
                                        <img src="images/users/<?= $post_author_image ?>" alt="">
                                    <?php endif; ?>
                                    <?= $post_author_fullname ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                
                </div>

                <!-- Pagination  -->
                <div class="pagination">
                    <?php if($page > 1): ?>
                        <a class="button button-pagination"href="http://localhost/post-engine/index.php?page=<?= $page - 1 ?>">Prev</a>
                    <?php endif; ?>
                    
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <a class="button button-pagination <?= $page == $i ? 'active' : '' ?>"href="http://localhost/post-engine/index.php?page=<?= $i ?>"><?= $i ?></a>
                    <?php endfor; ?>
                        
                    <?php if($page < $total_pages): ?>
                        <a class="button button-pagination"href="http://localhost/post-engine/index.php?page=<?= $page + 1 ?>">Next</a>
                    <?php endif; ?>
                </div>

            </div>
        </section>

        <!-- Footer -->
        <?php 
            require('./footer.php');
        ?>
        

    </body>
</html>