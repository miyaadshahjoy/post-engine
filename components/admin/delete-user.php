<?php 

    require('../../config/db.php');
    require('./../../middlewares/auth.php');
    const AUTHOR_ROLE_ID = 1;
    authorize([AUTHOR_ROLE_ID]);

    if(!isset($_GET['id']) || !is_numeric($_GET['id'])):
        die("⭕ Invalid user id");
    endif;

    $user_id = $_GET['id'];

    // Query 
    $sql = "UPDATE users SET status = 'removed' where id =:id";
    // statement 
    $statement = oci_parse($conn, $sql);

    // bind
    oci_bind_by_name($statement, ':id', $user_id);

    // Execute 
    $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);

    if(!$result):
        $err = oci_error($statement);
        echo "⭕ Error deleting user: " . $err['message'] . "<br>";
    else:
        header('Location:' . $_SERVER['HTTP_REFERER']);
    endif;

?>