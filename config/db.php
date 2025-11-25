<?php

    $env = parse_ini_file(__DIR__ . '/../.env');

    $host = 'localhost/XEPDB1';
    $user = $env['DB_USERNAME'];
    $password = $env['DB_PASSWORD'];
    $conn = oci_connect($user, $password , $host, 'AL32UTF8');

    if (!$conn):
        $err = oci_error();
        echo "⭕ Connection failed: " . $err['message']. " <br>";
    endif;
?>