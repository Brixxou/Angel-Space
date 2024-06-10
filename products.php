<html>
    <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Angel Space - Products</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
<?php include 'nav.php'; ?>
    <main>
        <?php
        include 'connexion.php';
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);
        ?>
        <div class="product-container">
            <?php
            while ($row = $result->fetch_assoc()) {
                echo '<a href="product.php?id=' . $row["ProductID"] . '" class="product-card">';
                echo '<img src="' . $row["image_url"] . '" alt="' . $row["Name"] . '">';
                echo '<p>' . $row["Name"] . '</p>';
                echo '<span>' . $row["Price"] . '€</span>';
                echo '</a>';
            }
            ?>
        </div>
        <?php   
        $conn->close();
        ?>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
</html>