<?php

    $env = parse_ini_file(__DIR__ . '/../.env');

    $host = 'localhost/XEPDB1';
    $user = $env['DB_USERNAME'];
    $password = $env['DB_PASSWORD'];
    $conn = oci_connect($user, $password , $host, 'AL32UTF8');

    if (!$conn) {
        $e = oci_error();
        echo "⭕ Connection failed: " . $e['message']. " <br>";
    } else {
        echo "✅ Connected to Oracle successfully! <br>";
    }
?>