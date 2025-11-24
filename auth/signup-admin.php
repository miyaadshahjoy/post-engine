<?php 

    require_once '../config/db.php';

    $env = parse_ini_file(__DIR__ . '/../.env');

    $fullname = $env['ADMIN_FULLNAME'];
    $username = $env['ADMIN_USERNAME'];
    $email = $env['ADMIN_EMAIL'];
    $password = $env['ADMIN_PASSWORD'];
    $role_id = 1;
    $status = 'active';

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (fullname, username, email, password, role_id, status) VALUES (:fullname, :username, :email, :password, :role_id, :status)";

    //statement
    $signupQuery = oci_parse($conn, $sql);

    oci_bind_by_name($signupQuery, ':fullname', $fullname);
    oci_bind_by_name($signupQuery, ':username', $username);
    oci_bind_by_name($signupQuery, ':email', $email);
    oci_bind_by_name($signupQuery, ':password', $hashedPassword);
    oci_bind_by_name($signupQuery, ':role_id', $role_id);
    oci_bind_by_name($signupQuery, ':status', $status);

    $result = oci_execute($signupQuery);

    if($result){
        echo "✅ Admin created successfully! <br>";
    } else {
        $err = oci_error($signupQuery);
        echo "⭕ Query execution failed: " . $err['message'];
    }


?>