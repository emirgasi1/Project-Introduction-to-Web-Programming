<?php
$host = '127.0.0.1';
$dbname = 'sandwich_shop';
$port = '3306'; // ili 3305 ako ti je drugačije
$username = 'root';
$password = ''; // ostavi prazno ako nema lozinke

try {
    $db = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    Flight::set('db', $db);
    // echo "Connected to the database successfully!";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
