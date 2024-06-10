<html>
    <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Angel Space - Products</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="css/product.css">
</head>
<body>
<?php include 'nav.php'; ?>
<?php
session_start();

include 'connexion.php';
$productID = $_GET['id'];
$sql = "SELECT * FROM Products WHERE ProductID = {$productID}";
$result = $conn->query($sql);
?>
<div class="cart-container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='product'>";
            echo "<h2 class='product-name'>" . htmlspecialchars($row["Name"]) . "</h2>";
            echo "<img src='" . htmlspecialchars($row["image_url"]) . "' alt='" . htmlspecialchars($row["Name"]) . "' class='product-image'>";
            echo "<p class='product-description'>" . htmlspecialchars($row["description"]) . "</p>";
            echo "<p class='product-price'>Price: " . htmlspecialchars($row["Price"]) . "€</p>";
            echo "<div class='form-container'>";
            echo "<form action='add_to_cart.php' method='post'>";
            echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($row["ProductID"]) . "'>";
            echo "<input type='number' name='quantity' value='1' min='1'>";
            echo "<input type='submit' value='Ajouter au panier'>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "Produit non trouvé.";
    }
    ?>
</div>
<?php
$conn->close();
?>
<?php include 'footer.php'; ?>
</body>
</html>