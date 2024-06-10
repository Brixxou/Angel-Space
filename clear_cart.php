<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Veuillez vous connecter pour vider le panier.";
    header("Location: login.php");
    exit();
}

include 'connexion.php';

$userID = $_SESSION['user_id'];

$clearCartQuery = "DELETE FROM Cart WHERE CustomerID = $userID";

if ($conn->query($clearCartQuery) === TRUE) {
    $_SESSION['success_message'] = "Le panier a été vidé avec succès.";
} else {
    $_SESSION['error_message'] = "Erreur lors de la suppression du panier: " . $conn->error;
}

$conn->close();
header("Location: panier.php");
exit();
?>