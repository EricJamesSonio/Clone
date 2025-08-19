<?php
$HOSTNAME = 'localhost';
$USERNAME = 'root';
$PASSWORD = ''; 
$DATABASE = 'softeng2';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$con = mysqli_connect($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);

if (!$con) {
    die(json_encode(['error' => 'Database connection failed', 'details' => mysqli_connect_error()]));
}

mysqli_set_charset($con, "utf8mb4");

