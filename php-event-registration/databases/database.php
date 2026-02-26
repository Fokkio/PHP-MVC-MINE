<?php
require_once INCLUDES_DIR . '/env.php';

function db_connect(): mysqli {
    $required = ['DB_HOST','DB_PORT','DB_USERNAME','DB_PASSWORD','DB_DATABASE','IMAGE_CLOUD_URL','CLOUDINARY_CLOUD_NAME'];

    foreach ($required as $key) {
        if (!isset($_ENV[$key])) {
            die("Missing ENV: $key");
        }
    }

    $conn = mysqli_init();

    mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

    $success = mysqli_real_connect(
        $conn,
        $_ENV['DB_HOST'],
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD'],
        $_ENV['DB_DATABASE'],
        $_ENV['DB_PORT'],
        NULL,
        MYSQLI_CLIENT_SSL
    );

    if (!$success) {
        error_log("Connect Error: " . mysqli_connect_error());
        die("Database connection failed.");
    }

    mysqli_set_charset($conn, "utf8mb4");

    return $conn;
}
