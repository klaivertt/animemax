<?php
session_start();
require_once 'config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    die();
}

// Récupérer les données de l'utilisateur
$req = $bdd->prepare('SELECT * FROM utilisateurs WHERE token = ?');
$req->execute(array($_SESSION['user']));
$data = $req->fetch();

if (isset($_POST['submit'])) {
    // Récupérer les valeurs du formulaire
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Construction du corps de l'e-mail
    $body = "Pseudo: " . $data['pseudo'] . "\n";
    $body .= "Adresse e-mail: " . $email . "\n";
    $body .= "Sujet: " . $subject . "\n";
    $body .= "Message:\n" . $message;

    // En-têtes de l'e-mail
    $headers = "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";

    // Envoyer l'e-mail
    $success = mail("anteionstudio@gmail.com", $subject, $body, $headers);

    // Vérifier si l'e-mail a été envoyé avec succès
    if ($success) {
        header('Location: Nouscontacter.php?success=true');
        exit();
    } else {
        echo "Une erreur s'est produite lors de l'envoi du message.";
    }
} else {
    header('Location: index.php');
    die();
}
