<?php
  require '../config/db.php';
  require('./../middlewares/auth.php');

  if(session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  // Authorization
  // const USER_ROLE_ID = 4;
  authorize([1, 3, 4]);

  // if (!isset($_GET['id']) || !is_numeric($_GET['id'])):
  //   die("⭕ Invalid user id");
  // endif;

  $user_id = $_GET['id'] ?? $_SESSION['id'];

  if ($_SERVER['REQUEST_METHOD'] === 'POST'):
    $current_password = trim($_POST['current-password']);
    $new_password = trim($_POST['new-password']);
    $confirm_password = trim($_POST['confirm-password']);

    if($current_password === '' || $new_password === '' || $confirm_password === ''):
      die("⭕ All fields are required.");
    endif;

    $sql = "SELECT password FROM users WHERE id = :id";
    $statement = oci_parse($conn, $sql);
    oci_bind_by_name($statement, ':id', $user_id);
    $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);

    if (!$result):
      $err = oci_error($statement);
      die("⭕ Error fetching user: " . $err['message']);
    endif;

    $password = oci_fetch_assoc($statement)['PASSWORD'];

    if (!password_verify($current_password, $password)):
      die( "⭕ Invalid current password");
    endif;

    if ($new_password !== $confirm_password):
      die( "⭕ Passwords do not match");
    endif;

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $sql = "UPDATE users SET password = :password WHERE id = :id";
    $statement = oci_parse($conn, $sql);
    oci_bind_by_name($statement, ':password', $hashed_password);
    oci_bind_by_name($statement, ':id', $user_id);
    $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);

    if (!$result):
      $err = oci_error($statement);
      echo "⭕ Error updating user: " . $err['message'] . "<br>";
    endif;

    header("Location: " . $_SERVER['HTTP_REFERER']);

  endif;

?>