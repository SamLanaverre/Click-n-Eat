<?php
$host = '127.0.0.1';
$db   = 'clickneat';
$user = 'laravelsam';
$pass = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion à la base de données réussie!";
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}