<?php 

    require('../../config/db.php');
    require('./../../app/auth.php');
    const AUTHOR_ROLE_ID = 1;
    authorize([AUTHOR_ROLE_ID]);

    if(!isset($_GET['id']) || !is_numeric($_GET['id'])):
        die("⭕ Invalid comment id");
    endif;

    $comment_id = $_GET['id'];

    # Query 
    $sql = "UPDATE comments 
            SET status = 'removed' where id =:id";
    # Statement 
    $statement = oci_parse($conn, $sql);

    # Bind
    oci_bind_by_name($statement, ':id', $comment_id);

    # Execute 
    $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);

    if(!$result):
        $err = oci_error($statement);
        echo "⭕ Error approving comment: " . $err['message'] . "<br>";
    else:
        header('Location:' . $_SERVER['HTTP_REFERER']);
    endif;

?>