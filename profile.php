<?php
include 'session.php';
include 'nav.php';
include 'connexion.php';
$user_id = $_SESSION['user_id'];
$query = "SELECT username, email FROM client WHERE CustomerID = ?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die("Error preparing the statement: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Angel Space - Profile</title>
    <link rel="stylesheet" type="text/css" href="css/profile.css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>  
    <div class="container">
        <h1>Profile de l'utilisateur</h1>
        <p>Email : <?php echo htmlspecialchars($user['email']); ?></p>
        <p>Pseudo : <?php echo htmlspecialchars($user['username']); ?></p>
        <div class="button-container">
            <a href="logout.php"><button>Se déconnecter</button></a>
            <a href="orders.php"><button>Voir les commandes</button></a>
            <a href="stats.php"><button>Produits le plus vendu</button></a>
        </div>
    </div>
</body>
</html>
<?php include 'footer.php'; ?>