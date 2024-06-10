<?php

define('DB_HOST', 'mysql-3bd28758-sayurusadaru12-0dba.i.aivencloud.com');
define('DB_USER', 'avnadmin');
define('DB_PASSWORD', 'AVNS__0q6915Ik6PDb1O9ZxD');
define('DB_NAME', 'drophere');
define('DB_PORT', 10967);
define('CA_CERT_PATH', __DIR__ . '/ca.pem'); // Using __DIR__ to ensure correct path

function getDBConnection()
{
    $conn = mysqli_init();

    mysqli_ssl_set($conn, NULL, NULL, CA_CERT_PATH, NULL, NULL);
    mysqli_options($conn, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);


    if (!mysqli_real_connect($conn, DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT, NULL, MYSQLI_CLIENT_SSL)) {
        error_log('Connection failed: ' . mysqli_connect_error());
        return false;
    }
    echo "Connected to MySQL with SSL\n";
    return $conn;
}
