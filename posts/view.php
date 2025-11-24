<?php 
  require('../config/db.php');
  $post_id = $_GET['id'];

  $sql = "SELECT posts.TITLE, posts.AUTHOR, posts.IMAGE, posts.DESCRIPTION, posts.CREATED_AT, categories.NAME AS CATEGORIES FROM posts JOIN categories ON posts.CATEGORIES_ID = categories.ID where posts.ID = :id";
  $statement = oci_parse($conn, $sql);

  oci_bind_by_name($statement, ':id', $post_id);
  $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);

  if($result){
    echo "✅ Query execution successful! <br>";
    $post = oci_fetch_assoc($statement);
    // echo "<pre>";
    // print_r($post);
    $post_title = $post['TITLE'];
    $post_author = $post['AUTHOR'];
    $post_image = $post['IMAGE'];
    $post_description = $post['DESCRIPTION']->load();
    $post_categories = $post['CATEGORIES'];
    $post_created_at = $post['CREATED_AT'];

  } else {
    $err = oci_error($statement);
    echo "⭕ Query execution failed: " . $err['message'];
  }
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../public/css/style.css" />
    <title><?php echo $post_title ?></title>
  </head>
  <body>
    <?php 
        require('./../header.php');
    ?>

    <section class="post">
      <div class="container">
        <h1 class="post-title"><?= $post_title ?></h1>
        <p class="post-categories"><?= $post_categories ?></p>
        <div class="post-image">
          <img src="../images/<?= $post_image ?>" alt=""/>
        </div>
        <div class="post-date"><?= $post_created_at ?></div>
        <div class="post-author"><?= $post_author ?></div>
        <p class="post-description"><?= nl2br(htmlspecialchars($post_description)); ?></p>
      </div>
    </section>
  </body>
</html>
