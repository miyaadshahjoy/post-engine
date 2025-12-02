<?php

    require_once __DIR__ . '/../app/errors.php';
    // require_once __DIR__ . '/../pages/error.php';

    $env = parse_ini_file(__DIR__ . '/../.env');


    $host = 'localhost/XEPDB1';
    $user = $env['DB_USERNAME'];
    $password = $env['DB_PASSWORD'];
    $conn = oci_connect($user, $password , $host, 'AL32UTF8');

    if (!$conn):
        $err = oci_error();
        fatal_error("⭕ Connection failed: Error connecting to database.", 500);
    endif;
?>