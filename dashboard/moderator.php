<?php 
    require './../middlewares/auth.php';

    authorize([2]);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../public/css/style.css">
        <title>DASHBOARD</title>
    </head>
    <body>
        <?php 

    require('./../header.php');
    ?>
        <div class="container">
            Moderator dashboard
        </div>
    </body>
</html>