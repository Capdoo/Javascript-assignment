<?php
    $host = 'localhost';
    $port = 3306;
    $db_name = 'misc';
    $db_user = 'root';
    $db_pass = 'root';

    $conn = new PDO("mysql:host=$host;port=$port;dbname=$db_name", $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>