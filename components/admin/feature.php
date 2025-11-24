<?php 

    require '../../config/db.php';

    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // Authorization
    require('./../../middlewares/auth.php');
    const USER_ROLE_ID = 1;
    authorize([USER_ROLE_ID]);

    // check if id and action are set

    if(!isset($_GET['id']) || !isset($_GET['action'])):
        die("⭕ Invalid request");
    endif;

    $id = (int) $_GET['id'];
    $action = $_GET['action'];

    if(!in_array($action, ['feature', 'unfeature'])):
        die("⭕ Invalid action");
    endif;

    $new_value = $action === 'feature' ? 1 : 0;

    // Query
    $sql = "UPDATE posts SET featured = :featured WHERE id = :id";

    // statement 
    $statement = oci_parse($conn, $sql);

    // bind
    oci_bind_by_name($statement, ':featured', $new_value);
    oci_bind_by_name($statement, ':id', $id);

    // execute
    $result = oci_execute($statement);

    if(!$result){
        $err = oci_error($statement);
        echo "⭕ Query execution failed: " . $err['message'];
    } else{
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
?>