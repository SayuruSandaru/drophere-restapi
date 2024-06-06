<?php

define('DB_HOST', '34.105.109.150');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'drophere');


function getDBConnection()
{
    error_log("Execute getDBConnection");
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }
    return $conn;
}
