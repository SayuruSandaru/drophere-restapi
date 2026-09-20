<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'drophere');
define('DB_PORT', 3306);

function getDBConnection()
{
    static $conn = null;
    if ($conn === null) {
        $conn = mysqli_init();

        if (!mysqli_real_connect($conn, DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT)) {
            error_log('Connection error: ' . mysqli_connect_error());
            $conn = null;
            return false;
        }
    }
    return $conn;
}
