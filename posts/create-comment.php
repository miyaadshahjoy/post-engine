<?php 

  # Database Connection
  require '../config/db.php';

  if(session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  
  # Handle comment submission

  if ($_SERVER['REQUEST_METHOD'] === 'POST' ):
    
    $comment = $_POST['comment'];
    $post_id = $_POST['post_id'];

    if(empty($comment)):
      die("⭕ Comment cannot be empty");
    endif;

    # Query 
    $sql = "INSERT INTO comments (post_id, user_id, comment_text) VALUES (:post_id, :user_id, :comment_text)";

    # Statement
    $statement = oci_parse($conn, $sql);

    # Bind
    oci_bind_by_name($statement, ':post_id', $post_id);
    oci_bind_by_name($statement, ':user_id', $_SESSION['id']);
    oci_bind_by_name($statement, ':comment_text', $comment);

    # Execute
    $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);

    if(!$result):
      $err = oci_error($statement);
      echo "⭕ Error inserting comment: " . $err['message'] . "<br>";
    endif;

    // header("Location: http://localhost/post-engine/posts/post.php?id=$post_id");
    header ("Location: $_SERVER[HTTP_REFERER]");

  endif;

?>