<?php

$email = $_POST['email'];
$to = $email;
$subject = "Confirmation d'inscription à la newsletter";
$message = "Merci de vous être inscrit à notre newsletter !";;

if (mail($to, $subject, $message)) {
    echo "E-mail de confirmation envoyé avec succès !";
} else {
    echo "Une erreur s'est produite lors de l'envoi de l'e-mail de confirmation.";
}
?>