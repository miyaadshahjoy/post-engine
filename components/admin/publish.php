<?php 
    
    require('../../config/db.php');
    require('./../../app/auth.php');
    const AUTHOR_ROLE_ID = 1;
    authorize([AUTHOR_ROLE_ID]);

    if(!isset($_GET['id']) || !is_numeric($_GET['id'])):
        die("⭕ Invalid post id");
    endif;

    $post_id = (int) $_GET['id'];

    $sql = "UPDATE posts SET status = 'published' WHERE id = :id";

    // statement
    $statement = oci_parse($conn, $sql);

    // bind
    oci_bind_by_name($statement, ':id', $post_id);

    // execute
    $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);

    if(!$result):
        $err = oci_error($statement);
        echo "⭕ Error updating post: " . $err['message'] . "<br>";
    else:
        header('Location:' . $_SERVER['HTTP_REFERER']);
    endif;
?>