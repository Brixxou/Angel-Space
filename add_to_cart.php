<html>
    <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Angel Space - Products</title>
    <link rel="stylesheet" type="text/css" href="css/add.css">
</head>
<body>
<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if (!isset($_SESSION['user_id'])) {
    echo "Please login to add items to cart.";
    exit(); 
}

include 'connexion.php';

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productID = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $customerID = $_SESSION['user_id'];

    $checkCartItemSql = "SELECT * FROM Cart WHERE CustomerID = $customerID AND ProductID = $productID";
    $checkCartItemResult = $conn->query($checkCartItemSql);

    echo "<div class='card'>";

    if ($checkCartItemResult->num_rows > 0) {
        $updateCartItemSql = "UPDATE Cart SET Quantity = Quantity + $quantity WHERE CustomerID = $customerID AND ProductID = $productID";
        if ($conn->query($updateCartItemSql) === TRUE) {
            $_SESSION['cart'] = $updateCartItemSql;
            echo "Quantité de produit modifiée avec succès.";
        } else {
            echo "Erreur de modification de quantité du produit: " . $conn->error;
        }
    } else {
        $addCartItemSql = "INSERT INTO Cart (CustomerID, ProductID, Quantity) VALUES ($customerID, $productID, $quantity)";
        if ($conn->query($addCartItemSql) === TRUE) {
            echo "Produit ajouté avec succès.";
        } else {
            echo "Erreur lors de l'ajout du produit au panier: " . $conn->error;
        }
    }
    
    echo "</div>";
    
    echo "<div class='card'>";
    echo "<a href='product.php?id=" . $productID . "' class='button'>Retourner au Produit</a>";
    echo "<a href='panier.php' class='button'>Voir le panier</a>";
    echo "</div>";
} else {
    echo "<div class='card'>";
    echo "Invalid product details.";
    echo "</div>";
}

$conn->close();
?>
</body>
</html>