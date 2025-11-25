<?php 


    $current_user_username = $_SESSION['username']; 

    # Query
    $sql = "SELECT * FROM posts WHERE author = :author";

    # Statement
    $statement = oci_parse($conn, $sql);

    # Bind parameters
    oci_bind_by_name($statement, ':author', $current_user_username);

    # Execute
    $result = oci_execute($statement);

    $post_count = 0;

    if(!$result):
        $err = oci_error($statement);
        echo "⭕ Query execution failed: " . $err['message'];
    endif;
        
    $post_count =oci_fetch_all($statement, $posts, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
   
?>



<!-- posts list -->
<table class="posts-list">
    <thead>
        <tr>
            <th>Post title</th>
            <th>Status</th>
            <th>Created at</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>

        <?php foreach($posts as $post):
            $post_id = $post['ID'];
            $post_title = $post['TITLE'];
            $post_created_at = DateTime::createFromFormat('d-M-y h.i.s.u A', $post['CREATED_AT'])->format('M d, Y h:i A');
            $post_status = $post['STATUS'];
        ?>

            <tr>
                <td><?= $post_title ?></td>
                <td><?= $post_status ?></td>
                <td><?= $post_created_at ?></td>
                <td>
                    <div class='actions'>
                        <a class='button button-blue' href='http://localhost/post-engine/posts/view.php?id=<?= $post_id?>'>View</a>
                        <a class='button button-warn' href='author.php?page=update&id=<?= $post_id?>'>Edit</a>
                        <a class='button button-delete' href=''>Delete</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>

    </tbody>
</table>