
<?php 

  require '../config/db.php';
  
  if(session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  

  if(!isset($_GET['id']) || !is_numeric($_GET['id'])):
    die("⭕ Invalid user id");
  endif;

  $user_id = $_GET['id'];

  // Query 
  $sql = "SELECT * FROM users WHERE id = :id";
  // statement 
  $statement = oci_parse($conn, $sql);
  // Bind
  oci_bind_by_name($statement, ':id', $user_id);
  // Execute
  $result = oci_execute($statement);

  if(!$result):
    $err = oci_error($statement);
    echo "⭕ Error fetching user: " . $err['message'] . "<br>";
  endif;

  $user = oci_fetch_assoc($statement);

  $user_fullname = $user['FULLNAME'];
  $user_username = $user['USERNAME'];
  $user_email = $user['EMAIL'];
  $user_password = $user['PASSWORD'];
  $user_image = $user['IMAGE'] ?? 'user.svg';
  $user_role_id = $user['ROLE_ID'];
  $created_at = DateTime::createFromFormat('d-M-y h.i.s.u a', $user['CREATED_AT'])->format('M d, Y h:i A');
  $status = $user['STATUS'];
  
  $roles = Array (
    1 => 'Admin',
    2 => 'Moderator',
    3 => 'Author',
    4 => 'Viewer'
  );

  $user_role = $roles[$user_role_id];

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../public/css/style.css" />
    <title><?= $user_fullname ?></title>
  </head>
  <body>
    <!-- header  -->
    <?php 
      require_once('../components/layout/header.php');
    ?>

    <section class="user">
      <div class="container">
        <h2 class="user-heading">Profile</h2>
        <div class="user-info">
          <div class="user-image">
            <img src="../images/users/<?= $user_image ?>" alt="User Image" />
          </div>
          <div class="user-details">
            <div>
              Fullname: <span><?= $user_fullname ?></span>
            </div>
            <div>
              Username: <span><?= $user_username ?></span>
            </div>
            <div>
              Email: <span><?= $user_email ?></span>
            </div>
            <div>
              Role: <span><?= $user_role ?></span>
            </div>
            <div>
              Created At: <span><?= $created_at ?></span>
            </div>
            <div>
              Status: <span><?= $status ?></span>
            </div>
            <div>
              <a class="button button-edit" href="http://localhost/post-engine/users/profile.php?id=<?= $_SESSION['id']; ?>">Edit Profile</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="recent">
      <div class="container">
        <h2 class="recent-heading">Recent Activities</h2>
      </div>
    </section>
  </body>
</html>
