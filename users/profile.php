<?php
  require '../config/db.php';
  require('./../app/auth.php');
  
  if(session_status() === PHP_SESSION_NONE) :
    session_start();
  endif;
  
  # Authorization
  # const USER_ROLE_ID = 1;
  authorize([1, 3, 4]);

  if (!isset($_GET['id']) || !is_numeric($_GET['id'])):
    die("⭕ Invalid user id");
  endif;

  $user_id = $_GET['id'];

  // Query 
  $sql = "SELECT * FROM users WHERE id = :id";
  // statement 
  $statement = oci_parse($conn, $sql);
  // bind
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
  $user_image = $user['IMAGE'];


  if($_SERVER['REQUEST_METHOD'] === 'POST'):
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    if($fullname === '' || $username === '' || $email === ''):
      echo "⭕ All fields are required <br>";
    endif;

    $newFileName = $user_image;

    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0):

      $uploadDIR = '../images/users/';
      $extension = strtolower( pathinfo($_FILES['image']['name'] , PATHINFO_EXTENSION));
      $allowedExtensions = ['jpg', 'jpeg', 'png'];
      // in_array(needle, haystack)
      if(!in_array($extension, $allowedExtensions)):
          die("⭕ Invalid file type. Only JPG, JPEG, and PNG files are allowed.");
      endif;

      // time() - returns current timestamp
      $newFileName = 'user-' . time() . '-'. rand(1000, 9999) . '.' . $extension;
      $uploadFile = $uploadDIR . $newFileName;

      if(!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)):
          die('⭕ Failed to upload file.');
      endif;
    endif;

    // Update user info
    $sql = "UPDATE users SET fullname = :fullname, username = :username, email = :email, image = :image WHERE id = :id";

    // statement 
    $statement = oci_parse($conn, $sql);
    // bind
    oci_bind_by_name($statement, ':fullname', $fullname);
    oci_bind_by_name($statement, ':username', $username);
    oci_bind_by_name($statement, ':email', $email);
    oci_bind_by_name($statement, ':image', $newFileName);
    oci_bind_by_name($statement, ':id', $user_id);
    // Execute
    $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);

    if(!$result):
      $err = oci_error($statement);
      echo "⭕ Error updating user: " . $err['message'] . "<br>";
    endif;

  endif;

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
    <!-- Header here  -->
    <?php require('./../components/layout/header.php'); ?>

    <section class="profile">
      <div class="container">
        <!-- Update Profile -->
        <div class="form-wrapper">
          <h2 class="form-title">Your Account Settings</h2>
          <form
            class="form profile-update"
            action="profile.php?id=<?= $user_id ?>"
            method="post"
            enctype="multipart/form-data"
          >
            <label for="fullname">Fullname</label>
            <input
              type="text"
              name="fullname"
              id=""
              value="<?= $user_fullname ?>"
              placeholder="Enter Fullname..."
            />
            <label for="username">Username</label>
            <input
              type="text"
              name="username"
              id=""
              value="<?= $user_username ?>"
              placeholder="Enter username..."
            />
            <label for="email">Email</label>
            <input
              type="email"
              name="email"
              id=""
              value="<?= $user_email ?>"
              placeholder="Enter email..."
            />
            <label for="image">Choose new photo</label>
            <div class="image-upload">
              <svg
                width="96px"
                height="96px"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g
                  id="SVGRepo_tracerCarrier"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                ></g>
                <g id="SVGRepo_iconCarrier">
                  <path
                    opacity="1"
                    d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z"
                    fill="#0ca678"
                  ></path>
                  <path
                    d="M16.807 19.0112C15.4398 19.9504 13.7841 20.5 12 20.5C10.2159 20.5 8.56023 19.9503 7.193 19.0111C6.58915 18.5963 6.33109 17.8062 6.68219 17.1632C7.41001 15.8302 8.90973 15 12 15C15.0903 15 16.59 15.8303 17.3178 17.1632C17.6689 17.8062 17.4108 18.5964 16.807 19.0112Z"
                    fill="#c3fae8"
                  ></path>
                  <path
                    d="M12 12C13.6569 12 15 10.6569 15 9C15 7.34315 13.6569 6 12 6C10.3432 6 9.00004 7.34315 9.00004 9C9.00004 10.6569 10.3432 12 12 12Z"
                    fill="#c3fae8"
                  ></path>
                </g>
              </svg>
              <input
                type="file"
                name="image"
                id=""
                placeholder="Enter image..."
              />
            </div>
            <input class="button" type="submit" value="Update Profile" />
          </form>
        </div>

        <!-- Update Password -->
        <div class="form-wrapper">
          <form class="form password-update" action="update-password.php?id=<?= $user_id ?>" method="post">
            <h2 class="form-title">Password Change</h2>
            <label for="current-password">Current password</label>
            <input
              type="password"
              name="current-password"
              id=""
              value=""
              placeholder="********"
            />
            <label for="new-password">New password</label>
            <input
              type="password"
              name="new-password"
              id=""
              value=""
              placeholder="********"
            />
            <label for="confirm-password">Confirm password</label>
            <input
              type="password"
              name="confirm-password"
              id=""
              value=""
              placeholder="********"
            />
            <input class="button" type="submit" value="Update Password" />
          </form>
        </div>
      </div>
    </section>
  </body>
</html>
