<?php

define('DB_HOST', 'db-mysql-nyc3-59693-do-user-14434449-0.h.db.ondigitalocean.com');
define('DB_USER', 'doadmin');
define('DB_PASSWORD', 'AVNS__t4S4Zc_r5NESLIxbYW');
define('DB_NAME', 'defaultdb');
define('DB_PORT', 25060);

function getDBConnection()
{
    static $conn = null;
    if ($conn === null) {
        $conn = mysqli_init();

        mysqli_options($conn, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);

        if (!mysqli_real_connect($conn, DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT, NULL, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT)) {
            error_log('Connection error: ' . mysqli_connect_error());
            $conn = null;
            return false;
        }
    }
    return $conn;
}
