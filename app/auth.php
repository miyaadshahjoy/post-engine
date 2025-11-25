<!-- middlewares/auth.php  -->

<?php 

    if(session_status() === PHP_SESSION_NONE):
        session_start();
    endif;

    if(!isset($_SESSION['id'])){
        die( "⭕ You are not logged in! Please log in first.");
        // header('Location: ./../auth/signin.php');
    }else {
        $user_role = $_SESSION['role_id'];
    }

    function authorize(array $allowed_roles){
        global $user_role;
        if(!in_array($user_role, $allowed_roles)){
            http_response_code(403);
            echo "Unauthorized access. You do not have permission to access this page. <br>";
            exit;
        }

    }

?>