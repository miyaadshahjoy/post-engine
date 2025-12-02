<?php 
  # Database Connection
  require('./../config/db.php');
  require '../app/errors.php';


  if ($_SERVER['REQUEST_METHOD'] === 'GET'):
    if (!isset($_GET['id']) || empty($_GET['id'])):
      flash_error("⭕ Post ID is required.");
      header("Location: http://localhost/post-engine/index.php");
      exit();
    endif;
    $post_id = $_GET['id'] ;
  endif;


  
  # Fetch post
  $sql = "SELECT posts.TITLE, posts.AUTHOR, posts.IMAGE, posts.DESCRIPTION, posts.CATEGORIES_ID, posts.CREATED_AT, categories.NAME AS CATEGORIES FROM posts JOIN categories ON posts.CATEGORIES_ID = categories.ID where posts.ID = :id";

  # Statement
  $statement = oci_parse($conn, $sql);

  # Bind
  oci_bind_by_name($statement, ':id', $post_id);

  # Execute
  $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);

  if(!$result):
    $err = oci_error($statement);

    echo "⭕ Query execution failed: " . $err['message'];

  endif;
  
  $post = oci_fetch_assoc($statement);

  $post_title = $post['TITLE']; 
  $post_author = $post['AUTHOR']; 
  $post_image = $post['IMAGE']; 
  $post_description = $post['DESCRIPTION']->load(); 
  $post_categories = $post['CATEGORIES']; 
  $post_date = DateTime::createFromFormat('d-M-y h.i.s.u A', $post['CREATED_AT'])->format('F
d, Y'); 

  # Fetch author Fullname 
  $sql = "SELECT fullname, image FROM users WHERE username = :username"; 
  # Statement 
  $statement = oci_parse($conn, $sql);
  # Bind
  oci_bind_by_name($statement, ':username', $post_author); 
  # Execute 
  $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);
  if(!$result): 
    $err = oci_error($statement);
    echo "⭕ Query execution failed: " . $err['message'];
  endif;

  $author = oci_fetch_assoc($statement);
  $post_author_fullname = $author['FULLNAME'];
  $post_author_image = $author['IMAGE'];
  $post_categories_id = $post['CATEGORIES_ID'];

  # Fetch related posts
  $sql = "SELECT * FROM ( SELECT p.id, p.title, p.image,
  c.name AS categories, p.author, p.created_at, ROW_NUMBER() OVER (ORDER BY
  p.created_at DESC) AS row_number FROM posts p JOIN categories c ON
  p.categories_id = c.id WHERE p.categories_id = :id AND p.id != :post_id) WHERE row_number <= 6";

  # Statement
  $statement = oci_parse($conn, $sql);

  # Bind
  oci_bind_by_name($statement, ':id', $post_categories_id);
  oci_bind_by_name($statement, ':post_id', $post_id);
  $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);

  if(!$result): 
    $err = oci_error($statement);
    echo "⭕ Query execution failed: " . $err['message'];
  endif;

  oci_fetch_all($statement, $related_posts, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);


  /////////////////////////////////////////
  function timePassed($oracleTime){
    // Create with timezone of your Oracle data
    $tz = new DateTimeZone("Asia/Dhaka");

    $dt = DateTime::createFromFormat(
        'd-M-y h.i.s.u A',
        $oracleTime,
        $tz
    );

    if (!$dt) {
        return "Invalid date";
    }

    // Convert to server timezone if needed
    $dt->setTimezone(new DateTimeZone(date_default_timezone_get()));

    $time = $dt->getTimestamp();
    $now  = time();
    $diff = $now - $time;

    if ($diff < 0) $diff = 0;

    if ($diff < 60) {
        return "just now";
    } elseif ($diff < 3600) {
        $m = floor($diff / 60);
        return "$m minute" . ($m > 1 ? "s" : "") . " ago";
    } elseif ($diff < 86400) {
        $h = floor($diff / 3600);
        return "$h hour" . ($h > 1 ? "s" : "") . " ago";
    } elseif ($diff < 604800) {
        $d = floor($diff / 86400);
        return "$d day" . ($d > 1 ? "s" : "") . " ago";
    } else {
        return $dt->format("M d, Y");
    }
  }

 ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../public/css/style.css" />
    <title><?= $post_title ?></title>
  </head>
  <body>
    <?php 
        require('../components/layout/header.php');

    ?>

    <!-- Post    -->
    <section class="post">
      <div class="container">
        <h1 class="post-title"><?= $post_title ?></h1>
        <p class="post-categories">
          <svg
            viewBox="0 0 24 24"
            fill="none"
            height="24px"
            width="24px"
            xmlns="http://www.w3.org/2000/svg"
          >
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g
              id="SVGRepo_tracerCarrier"
              stroke-linecap="round"
              stroke-linejoin="round"
            ></g>
            <g id="SVGRepo_iconCarrier">
              <path
                d="M3 12L21 12"
                stroke="#000000"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              ></path>
            </g></svg
          >
          <?= $post_categories ?>
        </p>
        <div class="post-image">
          <img src="../images/<?= $post_image ?>" alt="" />
        </div>
        <div class="post-date"><?= $post_date ?></div>
        <div class="post-author">
          <?php if ($post_author_image !== null): ?>
            <img src="../images/users/<?= $post_author_image ?>" alt="" />
          <?php endif; ?>
          <?= $post_author_fullname ?>
        </div>
        <p class="post-description">
          <?= nl2br(htmlspecialchars($post_description)); ?>
        </p>
      </div>
    </section>

    <!-- Comments List  -->
    
    <section class="comments-list">
      <div class="container">

        <?php
          # QUERY: Fetch comments
          $sql = "SELECT u.fullname, u.image, c.comment_text, c.created_at 
                  FROM comments c JOIN users u
                  ON c.user_id = u.id
                  WHERE c.post_id = :post_id AND c.status = 'approved' ORDER BY c.created_at DESC";

          # Statement
          $statement = oci_parse($conn, $sql);

          # Bind
          oci_bind_by_name($statement, ':post_id', $post_id);

          # Execute
          $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);

          if(!$result):
            $err = oci_error($statement);
            echo "⭕ Query execution failed: " . $err['message'];
          endif;

          # Fetch comments
          $comments_count = oci_fetch_all($statement, $comments, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);

          if($comments_count !== 0):
        ?>

          <h3>Comments</h3>

          <?php foreach($comments as $comment): 
            $comment_author = $comment['FULLNAME'];
            $comment_author_image = $comment['IMAGE'];
            $comment_text = $comment['COMMENT_TEXT'];
            $comment_date = $comment['CREATED_AT'];  
            
            
            
            
          ?>
            <div class="comment-item">
              <img src="../images/users/<?= $comment_author_image ?>" class="comment-avatar"/>

              <div class="comment-content">
                <span class="comment-author"><?= $comment_author ?></span>
                <span class="comment-date"><?= timePassed($comment_date) ?></span>
                <p><?= htmlspecialchars($comment_text) ?></p>
              </div>
            </div>
          <?php endforeach ?>

        <?php else: ?>
          <h3>No comments yet</h3>
        <?php endif; ?>
          
        </div>
      </section>



    <!-- Comment Section  -->
    <section class="comment">
      <div class="container">
        <h3>Leave a Comment</h3>
        
        <?php if (!isset($_SESSION['id'])): ?>
          <p class="comment-warning">You must be logged in to comment.</p>
        <?php else: ?>
          <form action="http://localhost/post-engine/posts/create-comment.php" method="POST" class="form form-comment">
            <input type="hidden" name="post_id" value="<?= $post_id ?>">
            <textarea name="comment" placeholder="Write your comment..." required></textarea>
            <button type="submit" class="button button-comment">Submit Comment</button>
          </form>
        <?php endif; ?>
      </div>
    </section>


    <section class="related">
      <div class="container">
        <h2 class="related-heading">Related Posts</h2>
        <div class="related-wrapper">
          <?php foreach($related_posts as $post): 
            $post_id = $post['ID'];
            $post_title = $post['TITLE'];
            $post_image = $post['IMAGE'];
            $post_category = $post['CATEGORIES'];
            $post_author = $post['AUTHOR'];
            $post_date = DateTime::createFromFormat('d-M-y h.i.s.u A', $post['CREATED_AT'])->format('F d, Y');
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
            <img src="../images/<?= $post_image ?>" alt="<?= $post_title ?>" class="post-card-image"/>
            <div class="post-card-content">
              <div class="post-card-date">
                <?= $post_date ?>
              </div>
              <a
                class="post-card-title"
                href="http://localhost/post-engine/pages/post.php?id=<?= $post_id ?>"
              >
                <?= $post_title ?>
              </a>
              <div class="post-card-author">
                <?php if ($post_author_image !== null): ?>
                  <img src="../images/users/<?= $post_author_image ?>" alt="" />
                <?php endif; ?>
                <?= $post_author_fullname ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php require('../components/layout/footer.php') ?>
  </body>
</html>
