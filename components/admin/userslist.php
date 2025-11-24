<?php 
   
    $sql = "SELECT u.id, u.fullname, r.role_name AS role, u.status, u.created_at FROM users u JOIN user_roles r ON u.role_id = r.id WHERE u.role_id != 1 AND u.status != 'removed' ORDER BY u.created_at DESC";

    // statement
    $statement = oci_parse($conn, $sql);

    // execute
    $result = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);

    if(!$result):
        $err = oci_error($statement);
        echo "⭕ Query execution failed: " . $err['message'];
    endif;

    oci_fetch_all($statement, $users, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);

?>



<!-- posts list -->
<table class="users-list">
    <thead>
        <tr>
            <th>User Name</th>
            <th>Role</th>
            <th>Status</th>
            <th>Active Since</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>

        <?php foreach($users as $user):
            $user_id = $user['ID'];
            $user_fullname = $user['FULLNAME'];
            $user_role = $user['ROLE'];
            $user_status = $user['STATUS'];
            $user_created_at = $user['CREATED_AT'];
            
        ?>

            <tr>
                <td> <?= $user_fullname ?> </td>
                <td>
                    <?= $user_role ?>
                </td>
                <td>
                    <?= $user_status ?>
                </td>
                <td><?= $user_created_at ?></td>
                <td>
                    <?php if ($user_status === 'active'): ?>
                        <a href='../components/admin/delete-user.php?id=<?= $user_id ?>' class='button button-delete'>Delete Account</a>
                    <?php else: ?>
                         <a href='../components/admin/approve-user.php?id=<?= $user_id ?>' class='button button-success'>Approve Account</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>

    </tbody>
</table>