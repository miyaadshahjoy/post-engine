<?php 
  require('./../config/db.php');

  $post_id = $_GET['id'];
  
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
  p.categories_id = c.id WHERE p.categories_id = :id ) WHERE row_number <= 6";

  # Statement
  $statement = oci_parse($conn, $sql);

  # Bind
  oci_bind_by_name($statement, ':id', $post_categories_id);
  $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);

  if(!$result): 
    $err = oci_error($statement);
    echo "⭕ Query execution failed: " . $err['message'];
  endif;

  oci_fetch_all($statement, $related_posts, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);

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
                href="http://localhost/post-engine/post.php?id=<?= $post_id ?>"
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
