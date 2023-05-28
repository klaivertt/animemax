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
        header('Location: confirmation.php');
        exit();
    } else {
        echo "Une erreur s'est produite lors de l'envoi du message.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Nous contacter - Anime Max</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/Contact.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
    <link rel="icon" href="https://i.ibb.co/pzb7pxM/animemaxred.png">

</head>
<body>
<nav>
    <div class="gauche">
        <a href="acceuil.php"><img src="https://i.ibb.co/x5VrKjd/animemaxred1.png" alt="logo" height="80%"></a>
        <div class="onglets">
            <a href="acceuil.php"><p>Accueil</p></a>
        </div>
    </div>
    <div class="droite">
        <p><i class="fas fa-search"></i></p>
        <p><i class="fas fa-bell"></i></p>
        <p>
            <a href="profil.php" class="compte" style="display: flex; align-items: center;">
                <img src="img/avatars/<?php echo $data['profile_image']; ?>" alt="Image de profil" class="round-image" height="50" width="50">
                <span style="margin-left: 5px;"><?php echo $data['pseudo']; ?></span>
            </a>
        </p>
    </div>
</nav>

<section class="contact">
    <h2>Nous contacter</h2>
    <div class="contact-info">
        <h3>Informations de contact</h3>
        <ul>
            <li><i class="fas fa-envelope"></i> E-mail : <a href="mailto:anteionstudio@gmail.com">anteionstudio@gmail.com</a></li>
        </ul>
    </div>

    <div class="contact-form">
        <h3>Formulaire de contact</h3>
        <form action="send_email.php" method="POST">
            <label for="email">Votre adresse e-mail :</label>
            <input type="email" id="email" name="email" value="<?php echo $data['email']; ?>" required>

            <label for="subject">Sujet :</label>
            <input type="text" id="subject" name="subject" required>

            <label for="message">Message :</label>
            <textarea id="message" name="message" required></textarea>

            <button type="submit" name="submit">Envoyer</button>
        </form>
    </div>
</section>

<footer>
    <div class="colonnes">
        <div class="colonne">
            <p><a href="Nouscontacter.php">Nous contacter</a></p>
        </div>
        <div class="colonne">
            <p><a href="CGU.php">Conditions d'utilisation</a></p>
        </div>
        <div class="colonne">
            <p><a href="mentionlégales.php">Mentions légales</a></p>
            <p><a href="Confidentialite.php">Confidentialité</a></p>
        </div>
        <div class="colonne">
            <p><a href="https://www.speedtest.net/">Test de vitesse</a></p>
        </div>
    </div>
    <div class="clearfix"></div> <!-- Ajout d'un élément de clearing -->
    <p class="full-width">Anime Max, France</p>
</footer>
</body>
</html>
