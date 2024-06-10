<?php
session_start();
include 'connexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $check_query = "SELECT * FROM client WHERE email = ?";
    $stmt = $conn->prepare($check_query);

    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['CustomerID'];
            $_SESSION['username'] = $user['username'];
            echo "<h2>Enregistrement réussi</h2>";        
            echo "<a href='index.php'><button>Retour à la page d'accueil</button></a>";
            exit();
        } else {
            if (isset($_SESSION['login_error'])) {
                echo "<p style='color:red'>" . $_SESSION['login_error'] . "</p>";
                unset($_SESSION['login_error']);
            }
        }
    } else {
        if (isset($_SESSION['login_error'])) {
            echo "<p style='color:red'>" . $_SESSION['login_error'] . "</p>";
            unset($_SESSION['login_error']);
        }
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    if (isset($_SESSION['login_error'])) {
        echo "<p style='color:red'>" . $_SESSION['login_error'] . "</p>";
        unset($_SESSION['login_error']);
    }
    exit();
}
?>
