<!-- posts/create.php -->
<?php 
    // Database connection
    require '../config/db.php';
    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // session_start();

    // TODO: Authorization


    echo $_SESSION['username'] . "<br>";
    $author = $_SESSION['username'];

    // Handle form submission
    if($_SERVER['REQUEST_METHOD'] === 'POST'):
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $categories = (int) $_POST['categories'];

        // Validate inputs
        if($title === '' || $description === '' || $categories === ''):
            die("⭕ All fields are required.");
        endif;

        // Handle image upload 
        $fileName = null;
        if(isset($_FILES['image']) && $_FILES['image']['error'] === 0):
            
            $uploadDIR = '../images/';
            $extension = pathinfo($_FILES['image']['name'] , PATHINFO_EXTENSION);
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            // in_array(needle, haystack)
            if(!in_array($extension, $allowedExtensions)):
                die("⭕ Invalid file type. Only JPG, JPEG, and PNG files are allowed.");
            endif;

            // time() - returns current timestamp
            $fileName = 'post-' . time() . '-'. rand(1000, 9999) . '.' . $extension;
            $uploadFile = $uploadDIR . $fileName;

            if(!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)):
                die('⭕ Failed to upload file.');
            endif;

        endif;

        // Insert post into database
        $sql = "INSERT INTO posts(title, description, categories_id, image, author) VALUES (:title, :description, :categories, :image, :author)";
                // statement
                $statement = oci_parse($conn, $sql);
                // bind parameters
                oci_bind_by_name($statement, ':title', $title);
                oci_bind_by_name($statement, ':description', $description);
                oci_bind_by_name($statement, ':categories', $categories);
                oci_bind_by_name($statement, ':image', $fileName);
                oci_bind_by_name($statement, ':author', $author);

                // execute statement
                $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);

                if($result) {
                    echo "✅Post created successfully. <br>";
                } else {
                    $err = oci_error($statement);
                    echo "⭕ Error creating post: " . $err['message'] . "<br>";
                }    
            
    endif;    

?>


<div class="post-create">
    
    <h2 class="form-title">
        Create new post
    </h2>

    <form class="form form-create" action="http://localhost/post-engine/dashboard/author.php?page=create" method="post" enctype="multipart/form-data">
        <input type="text" name="title" id="" placeholder="Enter title...">
        <textarea name="description" id="" placeholder="Enter description..."></textarea>
        <label for="categories" style="margin-bottom: -18px; ">Categories</label>
        <select name="categories" id="">
            <option value="1">Travel & Adventure</option>
            <option value="21">Culture & Sprituality</option>
            <option value="62">Travel & Nature</option>
        </select>
        <input type="file" name="image" id="" placeholder="Enter image...">
        <input class="btn btn-submit" type="submit" value="create post">
    </form>
    
</div>