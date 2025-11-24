<?php 

    session_start();
    unset ($_SESSION['id']);
    unset ($_SESSION['email']);
    unset ($_SESSION['role_id']);

   session_destroy();
   header('Location: ./../auth/signin.php');
?>
