<?php

$host = 'localhost';
$db = 'wham_ecommerce'; 
$user = 'root';        
$pass = '';             

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); 
} catch (PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    die("Could not connect to the database."); 
}

