<?php
session_start();
include 'connexion.php';

if (!isset($_SESSION['user_id'])) {
    echo "Connectez vous pour voir votre panier.";
    exit();
}

// Récupérer l'ID de l'utilisateur à partir de la session
$customerID = $_SESSION['user_id'];

// Récupérer les produits du panier associés à cet utilisateur
$query = "
    SELECT p.ProductID AS ProductID, p.Name AS ProductName,p.image_url AS ProductImage,p.Price AS ProductPrice, c.Quantity 
    FROM products p
    INNER JOIN cart c ON p.ProductID = c.ProductID
    WHERE c.CustomerID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customerID);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/cart.css">
</head>
<body>
<?php include 'nav.php'; ?>

<h1>Panier</h1>
<div class="cart-container">
<?php
    if ($result instanceof mysqli_result) {
        if ($result->num_rows > 0) {
            $total = 0;

            while ($row = $result->fetch_assoc()) {
                $productID = $row['ProductID'];
                $productName = htmlspecialchars($row['ProductName']);
                $productImage = htmlspecialchars($row['ProductImage']);
                $productPrice = number_format($row['ProductPrice'], 2);
                $quantity = $row['Quantity'];
                $subtotal = number_format($row['ProductPrice'] * $quantity, 2);
                $total += $subtotal;

                echo "<div class='cart-item'>";
                echo "<img src='$productImage' alt='$productName' class='product-image'>";
                echo "<div class='product-details'>";
                echo "<span class='product-name'>$productName</span>";
                echo "<span class='product-price'>Prix: $productPrice €</span>";
                echo "<span class='quantity'>Quantité: $quantity </span>";
                echo "<span class='subtotal'>Sous-total: $subtotal €</span>";
                echo "</div>";
                echo "</div>";
            }

            echo "<div class='cart-total'>";
            echo "<span>Total: $total €</span>";
            echo "</div>";

            echo "<div class='checkout-button'>";
            echo "    <form action='checkout.php' method='post'>";
            echo "        <button type='submit'>Passer la commande</button>";
            echo "    </form>";
            echo "</div>";

            echo "<div style='margin-top: 20px;'>";
            echo "    <form action='clear_cart.php' method='post'>";
            echo "        <button type='submit'>Vider le panier</button>";
            echo "    </form>";
            echo "</div>";
        } else {
            echo "<p>Votre panier est vide.</p>";
        }
    } else {
        echo "<p>Erreur lors de l'affichage de votre panier.</p>";
    }

    $conn->close();
    ?>
</div>
</body>
</html>
