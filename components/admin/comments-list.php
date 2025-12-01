<!-- components/admin/postslist.php -->
<?php 

    $sql = "SELECT c.id, c.comment_text, c.status, c.created_at, u.fullname
            FROM comments c JOIN users u
            ON c.user_id = u.id
            ORDER BY c.created_at DESC";

    // statement
    $statement = oci_parse($conn, $sql);

    // execute
    $result = oci_execute($statement);

    $comments_count = 0;

    if(!$result):
      $err = oci_error($statement); 
      echo "⭕ Query execution failed: " .$err['message']; 
    endif;

    $comments_count = oci_fetch_all($statement, $comments, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
?>

<!-- comments list -->
<table class="comments-list">
  <thead>
    <tr>
      <th>Comment</th>
      <th>Status</th>
      <th>Commented At</th>
      <th>Commented By</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($comments as $comment):
            $comment_id = $comment['ID'];
            $comment_text = $comment['COMMENT_TEXT'];
            $comment_created_at = DateTime::createFromFormat('d-M-y h.i.s.u a', $comment['CREATED_AT'])->format('M d, Y h:i A');
            $comment_status = $comment['STATUS'];
            $commented_by = $comment['FULLNAME'];
    ?>

        <tr>
              
            <td><?= $comment_text ?></td>
            <td><?= $comment_status ?></td>
            <td><?= $comment_created_at ?></td>
            <td><?= $commented_by ?></td>
            <td>
                <div class="actions">
                    <?php if ($comment_status === 'pending'): ?>
                        <a href="../components/admin/publish-comment.php?id=<?= $comment_id?>" class="button button-success" >Approve</a>
                    <?php endif; ?>
                    <a href="" class="button button-delete">Delete</a>


                </div>
            </td>
        </tr>
    <?php endforeach; ?>
  </tbody>
</table>
