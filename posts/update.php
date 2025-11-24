<!-- posts/update.php -->
<?php 

    require '../config/db.php';
    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // TODO: Authorization

    if(!isset($_GET['id']) || !is_numeric($_GET['id'])):
        die("⭕ Invalid post id");
    endif;

    $post_id = (int) $_GET['id'];
    echo "post id: " . $post_id . "<br>";

    // Fetch existing post 
    $sql = "SELECT posts.TITLE, posts.AUTHOR, posts.IMAGE, posts.DESCRIPTION, posts.STATUS, posts.CREATED_AT, categories.NAME AS CATEGORIES FROM posts JOIN categories ON posts.CATEGORIES_ID = categories.ID where posts.ID = :id";

    // statement
    $statement = oci_parse($conn, $sql);

    // bind
    oci_bind_by_name($statement, ':id', $post_id);

    // execute
    $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);

    if(!$result):
        die("⭕ Query execution failed");
    endif;
    
    $post_count = oci_fetch_all($statement, $post, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);

    if($post_count === 0):
        die("⭕ Post not found");
    endif;

    $post_title = $post[0]['TITLE'];
    $post_description = $post[0]['DESCRIPTION'];
    $post_categories = $post[0]['CATEGORIES'];
    $post_image = $post[0]['IMAGE'];
    $post_author = $post[0]['AUTHOR'];
    $post_status = $post[0]['STATUS'];
    
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'):
        $title = $_POST['title'];
        $description = $_POST['description'];
        $categories = $_POST['categories'];

        if($title === '' || $description === '' || $categories === ''):
            die("⭕ All fields are required.");
        endif;


        $newFileName = $post_image;

        if(isset($_FILES['image']) && $_FILES['image']['error'] === 0):
        
            $uploadDIR = '../images/';
            $extension = strtolower( pathinfo($_FILES['image']['name'] , PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            // in_array(needle, haystack)
            if(!in_array($extension, $allowedExtensions)):
                die("⭕ Invalid file type. Only JPG, JPEG, and PNG files are allowed.");
            endif;

            // time() - returns current timestamp
            $newFileName = 'post-' . time() . '-'. rand(1000, 9999) . '.' . $extension;
            $uploadFile = $uploadDIR . $newFileName;

            if(!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)):
                die('⭕ Failed to upload file.');
            endif;
        endif;

        // Update post
        $sql = "UPDATE posts 
        SET title = :title, 
        description = :description, 
        categories_id = :categories_id, 
        image = :image 
        WHERE id = $post_id
        ";

        // statement
        $statement = oci_parse($conn, $sql);
        // bind
        oci_bind_by_name($statement, ':title', $title);
        oci_bind_by_name($statement, ':description', $description);
        oci_bind_by_name($statement, ':categories_id', $categories);
        oci_bind_by_name($statement, ':image', $newFileName);
        // oci_bind_by_name($statement, ':author', $author);

        $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);

            if($result) {
                echo "✅Post updated successfully. <br>";
            } else {
                $err = oci_error($statement);
                echo "⭕ Error updating post: " . $err['message'] . "<br>";
            }
        
    endif;

?>

<div class="post-update">
    <h2 class="form-title">
        Edit post
    </h2>
    
    <form class="form form-update"  action="http://localhost/post-engine/dashboard/admin.php?page=update&id=<?= $post_id; ?>" method="post" enctype="multipart/form-data">
        <input type="text" name="title" id="" value="<?= $post_title; ?>" placeholder="Enter title...">
        <textarea name="description" id="" placeholder="Enter description..."><?= $post_description; ?></textarea>
        <label for="categories" style="margin-bottom: -18px; ">Categories</label>
        <select name="categories" id="">
            <option value="1">Travel & Adventure</option>
        </select>
        <input type="file" name="image" id="" placeholder="Enter image...">        
        <input class="btn btn-submit" type="submit" value="Update Post">
    </form>

</div>