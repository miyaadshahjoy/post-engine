<?php

    if (session_status() === PHP_SESSION_NONE):
        session_start();
    endif;

    $post_author = $_SESSION['username'];
    

    # Query : Fetch all published posts
    $sql = "SELECT * FROM (
                SELECT p.id, p.title, p.image, p.likes, c.name AS categories, p.author, p.created_at, p.status,
                ROW_NUMBER() OVER (ORDER BY p.created_at DESC) AS row_number
                FROM posts p JOIN categories c 
                ON p.categories_id = c.id 
                WHERE p.author = :author
                )
            WHERE row_number <= 5";

    # Statement
    $statement = oci_parse($conn, $sql);

    # Bind
    oci_bind_by_name($statement, ':author', $post_author);

    $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);

    if(!$result):
        $err = oci_error($statement);
        echo "⭕ Query execution failed: " . $err['message'];
    endif;

    oci_fetch_all($statement, $posts, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);

    $sql = "SELECT 
                SUM(likes) AS total_likes,
                COUNT( CASE WHEN status = 'published' THEN 1 END) AS published_post,
                COUNT( CASE WHEN status = 'pending' THEN 1 END) AS pending_post,
                COUNT( CASE WHEN featured = 1 THEN 1 END) AS featured_post
            FROM posts
            WHERE author = :author";

    $statement = oci_parse($conn, $sql);

    oci_bind_by_name($statement, ':author', $post_author);

    $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);

    if(!$result):
        $err = oci_error($statement);
        echo "⭕ Query execution failed: " . $err['message'];
    endif;   

    $analytics = oci_fetch_assoc($statement);

    $total_posts = $analytics['PUBLISHED_POST'] + $analytics['PENDING_POST'];
    $published_posts = $analytics['PUBLISHED_POST'];
    $pending_posts = $analytics['PENDING_POST'];
    $featured_posts = $analytics['FEATURED_POST'];
    $total_likes = $analytics['TOTAL_LIKES'];

?>

<div class="dashboard-overview">
    <p class="dashboard-welcome">Welcome back <span style="text-transform: capitalize; font-weight: 600"><?= $_SESSION['fullname']?></span> 😊</p>

    <div class="analytics">
        <div class="analytics-card">
            <h3>Total Posts</h3>
            <p><?= $total_posts ?></p>
        </div>
        
        <div class="analytics-card">
            <h3>Published Posts</h3>
            <p><?= $published_posts ?></p>
        </div>

        <div class="analytics-card">
            <h3>Pending Posts</h3>
            <p><?= $pending_posts ?></p>
        </div>

        <div class="analytics-card">
            <h3>Featured Posts</h3>
            <p><?= $featured_posts ?></p>
        </div>

        <div class="analytics-card">
            <h3>Total Likes</h3>
            <p><?= $total_likes ?></p>
        </div>
    </div>

    <div class="recent">
        <h2 class="recent-heading" >Recent Posts</h2>
        <div class="recent-wrapper">
            
            <div class="recent-post">
                <?php foreach($posts as $post): 
                    $post_id = $post['ID'];
                    $post_title = $post['TITLE'];
                    $post_status = $post['STATUS'];
                    $post_likes = $post['LIKES'];
                ?>
                    <a href="../posts/view.php?id=<?= $post_id ?>" class="recent-title"><?= $post_title ?></a>
                    <div class="recent-data">
                        <p>status: <span><?= $post_status ?></span></p>
                        <p>likes: <span><?= $post_likes ?></span></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>