<?php
include './include/Connect.inc.php'; // Fichier pour la connexion à la base de données

$query = $_GET['query'];
$query = "%$query%";

$stmt = $conn->prepare("SELECT idRelais, nomRelais, rueRelais, villeRelais FROM POINT_RELAIS WHERE villeRelais LIKE :query OR cdPostalRelais LIKE :query");
$stmt->execute(['query' => $query]);

$relais = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($relais);
?>