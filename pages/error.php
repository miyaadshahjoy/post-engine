<?php
  if (session_status() === PHP_SESSION_NONE):
    session_start();
  endif;
  $message = $_SESSION['fatal_error'] ?? "Something went wrong.";
  unset($_SESSION['fatal_error']);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../public/css/style.css" />
    <title>Error ⭕</title>
  </head>
  <body>
    <div class="error">

      <h1>Oops ;(</h1>
      <p><?= $message ?></p>
      <a href="http://localhost/post-engine/index.php" class="button">Go Back Home</a>
    </div>
  </body>
</html>
