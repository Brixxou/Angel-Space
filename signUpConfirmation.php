<?php 
session_start();

include 'connexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Vérifiez la connexion à la base de données
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Préparez la requête pour vérifier si l'utilisateur existe déjà
    $check_query = "SELECT * FROM client WHERE username = ?";
    $stmt = $conn->prepare($check_query);

    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['registration_error'] = "Ce nom d'utilisateur est déjà utilisé.";
        $stmt->close();
        header("Location: register.php");
        exit();
    } else {
        // Préparez la requête pour insérer un nouvel utilisateur
        $insert_query = "INSERT INTO client (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_query);

        if ($stmt === false) {
            die("Error preparing the statement: " . $conn->error);
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "<h2>Enregistrement réussi</h2>";        
            echo "<a href='index.php'><button>Retour à la page d'accueil</button></a>";
        } else {
            echo "Error: " . $stmt->error;
            $_SESSION['registration_error'] = "Erreur lors de l'inscription.";
            $stmt->close();
            header("Location: register.php");
            exit();
        }

        $stmt->close();
    }

    $conn->close();
} else {
    header("Location: register.php");
    exit();
}
?>
