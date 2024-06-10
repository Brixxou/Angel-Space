<?php
include 'connexion.php';
include 'session.php';

$customerID = $_SESSION['user_id'];
$totalAmount = 0;

$query = "
    SELECT p.ProductID AS ProductID, p.Name AS ProductName, p.Price AS ProductPrice, c.Quantity 
    FROM products p
    INNER JOIN cart c ON p.ProductID = c.ProductID
    WHERE c.CustomerID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customerID);
$stmt->execute();
$result = $stmt->get_result();

$orderItems = [];
if ($result instanceof mysqli_result) {
    while ($row = $result->fetch_assoc()) {
        $orderItems[] = $row;
        $totalAmount += $row['ProductPrice'] * $row['Quantity'];
    }
}

$orderQuery = "INSERT INTO orders (CustomerID, TotalAmount, OrderStatus) VALUES (?, ?, 'en attente')";
$orderStmt = $conn->prepare($orderQuery);
$orderStmt->bind_param("id", $customerID, $totalAmount);

if ($orderStmt->execute()) {
    $orderID = $orderStmt->insert_id;

    $orderDetailQuery = "INSERT INTO orderdetails (OrderID, ProductID, Quantity, Price) VALUES (?, ?, ?, ?)";
    $orderDetailStmt = $conn->prepare($orderDetailQuery);

    foreach ($orderItems as $item) {
        $productID = $item['ProductID'];
        $quantity = $item['Quantity'];
        $price = $item['ProductPrice'];
        $orderDetailStmt->bind_param("iiid", $orderID, $productID, $quantity, $price);
        $orderDetailStmt->execute();

        $updateStockQuery = "UPDATE products SET StockQuantity = StockQuantity - ? WHERE ProductID = ?";
        $updateStockStmt = $conn->prepare($updateStockQuery);
        $updateStockStmt->bind_param("ii", $quantity, $productID);
        $updateStockStmt->execute();
    }

    $clearCartQuery = "DELETE FROM cart WHERE CustomerID = ?";
    $clearCartStmt = $conn->prepare($clearCartQuery);
    $clearCartStmt->bind_param("i", $customerID);
    $clearCartStmt->execute();

    echo "<!DOCTYPE html>
    <html lang='fr'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Confirmation de Commande</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
            }
            h2 {
                color: #28a745;
            }
            .confirmation {
                background-color: #f9f9f9;
                padding: 20px;
                border: 1px solid #ccc;
                border-radius: 5px;
                text-align: center;
            }
            .confirmation p {
                font-size: 1.2em;
            }
            button {
                padding: 10px 20px;
                font-size: 1em;
                font-weight: bold;
                color: #fff;
                background-color: #28a745;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                display: inline-block;
                margin-top: 20px;
            }
            button:hover {
                background-color: #218838;
            }
        </style>
    </head>
    <body>
        <div class='confirmation'>
            <h2>Commande effectué avec succès</h2>
            <p>Votre Commande à été effectué avec succès. ID de commande: $orderID</p>
            <a href='index.php'><button>Returner à la page d'accueil</button></a>
        </div>
    </body>
    </html>";
} else {
    echo "<p>Erreur lors de la commande. Veuillez réessayer.</p>";
}

$conn->close();
?>
