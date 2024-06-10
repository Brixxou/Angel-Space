<?php
include 'connexion.php';
include 'nav.php';
include 'session.php';

$customerID = $_SESSION['user_id'];

// Récupérer les commandes de l'utilisateur
$query = "
    SELECT o.OrderID, o.OrderDate, o.TotalAmount, o.OrderStatus, 
           od.ProductID, od.Quantity, od.Price, 
           p.Name AS ProductName, p.image_url AS ProductImage
    FROM orders o
    INNER JOIN orderdetails od ON o.OrderID = od.OrderID
    INNER JOIN products p ON od.ProductID = p.ProductID
    WHERE o.CustomerID = ?
    ORDER BY o.OrderDate DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customerID);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[$row['OrderID']]['OrderDate'] = $row['OrderDate'];
    $orders[$row['OrderID']]['TotalAmount'] = $row['TotalAmount'];
    $orders[$row['OrderID']]['OrderStatus'] = $row['OrderStatus'];
    $orders[$row['OrderID']]['Products'][] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Angle Space - Orders</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="css/orders.css">
</head>
<body>
    <div class="container">
        <h1>Mes Commandes</h1>
        <?php if (empty($orders)) : ?>
            <p>Vous n'avez pas encore passé de commande.</p>
        <?php else : ?>
            <?php foreach ($orders as $orderID => $order) : ?>
                <div class="order">
                    <h2>Commande #<?php echo $orderID; ?></h2>
                    <p>Date : <?php echo $order['OrderDate']; ?></p>
                    <p>Total : <?php echo number_format($order['TotalAmount'], 2); ?> €</p>
                    <p>Statut : <?php echo $order['OrderStatus']; ?></p>
                    <div class="order-products">
                        <?php foreach ($order['Products'] as $product) : ?>
                            <div class="order-product">
                                <img src="<?php echo htmlspecialchars($product['ProductImage']); ?>" alt="<?php echo htmlspecialchars($product['ProductName']); ?>" class="product-image">
                                <div class="product-details">
                                    <p class="product-name"><?php echo htmlspecialchars($product['ProductName']); ?></p>
                                    <p>Quantité : <?php echo $product['Quantity']; ?></p>
                                    <p>Prix : <?php echo number_format($product['Price'], 2); ?> €</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
<?php include 'footer.php'; ?>
