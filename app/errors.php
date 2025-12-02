<?php 

  if (session_status() === PHP_SESSION_NONE):
      session_start();
  endif;
    
  # Store an error message to show as a popup toast notification
  function flash_error($message){
    $_SESSION['error'] = $message;
    
  }

  # Store a success message to show as a popup toast notification
  function flash_success($message){
    $_SESSION['success'] = $message;

  }

  # Throw a full page fatal error
  function fatal_error($message, $code = 500){
    http_response_code($code);
    $_SESSION['fatal_error'] = $message;
    header("Location: /post-engine/pages/error.php");
    exit();
  }

?>