<?php

$host = "localhost";
$port = "5432";
$dbname = "tasksproject";
$username = "postgres";
$password = "karrota";

try {
    $conn = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $username,
        $password
    );
    $conn->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

     //echo "Database connected successfully!";
} catch(PDOException $e){
    die("Connection failed: " . $e->getMessage());
}

?>