<?php

define('DB_HOST', '......');
define('DB_USER', '.....');
define('DB_PASSWORD', '...');
define('DB_NAME', '...');
define('DB_PORT', 3306);
define('CA_CERT_PATH', __DIR__ . '....');


function getDBConnection()
{
    static $conn = null;
    if ($conn === null) {
        $conn = mysqli_init();
        mysqli_ssl_set($conn, NULL, NULL, CA_CERT_PATH, NULL, NULL);
        mysqli_options($conn, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);

        if (!mysqli_real_connect($conn, DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT, NULL, MYSQLI_CLIENT_SSL)) {
            error_log('Connection error: ' . mysqli_connect_error());
            $conn = null;
            return false;
        }
    }
    return $conn;
}


