<?php 

    $sql = "SELECT * FROM posts";

    // statement
    $statement = oci_parse($conn, $sql);

    // execute
    $result = oci_execute($statement);

    $post_count = 0;

    if(!$result):
      $err = oci_error($statement); 
      echo "⭕ Query execution failed: " .$err['message']; 
    endif;

    $post_count = oci_fetch_all($statement, $posts, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);
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
            $post_created_at = DateTime::createFromFormat('d-M-y h.i.s.u a', $post['CREATED_AT'])->format('M d, Y h:i A');
            $post_status = $post['STATUS'];
            $post_featured = (int) $post['FEATURED'];
        ?>

    <tr>
      
        <!-- <a href="../components/admin/feature.php?id=<?= $post_id?>" class="feature">

          <svg
            fill="#000000"
            width="24px"
            height="24px"
            viewBox="0 0 1920 1920"
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
                d="M1306.181 1110.407c-28.461 20.781-40.32 57.261-29.477 91.03l166.136 511.398-435.05-316.122c-28.686-20.781-67.086-20.781-95.66 0l-435.05 316.122 166.25-511.623c10.842-33.544-1.017-70.024-29.591-90.805L178.577 794.285h537.825c35.351 0 66.523-22.701 77.365-56.245l166.25-511.51 166.136 511.397a81.155 81.155 0 0 0 77.365 56.358h537.939l-435.276 316.122Zm609.77-372.819c-10.956-33.656-42.014-56.244-77.365-56.244h-612.141l-189.064-582.1C1026.426 65.589 995.367 43 960.017 43c-35.351 0-66.523 22.588-77.365 56.245L693.475 681.344H81.335c-35.351 0-66.41 22.588-77.366 56.244-10.842 33.657 1.017 70.137 29.591 90.918l495.247 359.718-189.29 582.211c-10.842 33.657 1.017 70.137 29.704 90.918 14.23 10.39 31.059 15.586 47.661 15.586 16.829 0 33.657-5.195 47.887-15.699l495.248-359.718 495.02 359.718c28.575 20.894 67.088 20.894 95.775.113 28.574-20.781 40.433-57.261 29.59-91.03l-189.289-582.1 495.247-359.717c28.687-20.781 40.546-57.261 29.59-90.918Z"
                fill-rule="evenodd"
              ></path>
            </g>
          </svg>
        </a>  -->

        <!-- ------------------------------ -->
              
      <td><?= $post_title ?></td>
      <td><?= $post_status ?></td>
      <td><?= $post_created_at ?></td>
      <td>
        <div class="actions">
          <a
            href="http://localhost/post-engine/posts/view.php?id=<?= $post_id?>"
            class="button button-blue" >View</a
          >
          <?php if ($post_status === 'pending'): ?>
            <a href="../components/admin/publish.php?id=<?= $post_id?>" class="button button-success" >Publish</a>
          <?php endif; ?>
          <a href="" class="button button-delete">Delete</a>

          <?php if($post_status === 'published'): ?>
            <?php if($post_featured === 0): ?>
              <a href="../components/admin/feature.php?id=<?= $post_id?>&action=feature" class="button button-success">Feature</a>
            <?php else: ?>
              <a href="../components/admin/feature.php?id=<?= $post_id?>&action=unfeature" class="button button-warn">Unfeature</a>
            <?php endif; ?>
          <?php endif; ?>


        </div>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
