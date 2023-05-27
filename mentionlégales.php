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

if (isset($_POST['avatar'])) {
    $selectedAvatar = $_POST['avatar'];

    // Mettre à jour l'avatar dans la base de données
    $updateAvatar = $bdd->prepare('UPDATE utilisateurs SET profile_image = ? WHERE token = ?');
    $updateAvatar->execute(array($selectedAvatar, $_SESSION['user']));

    // Rediriger vers la page de profil avec un message de succès
    header('Location: profil.php?success=avatar');
    die();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mentions légales - Anime Max</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/CGU.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
    <link rel="icon" href="https://i.ibb.co/pzb7pxM/animemaxred.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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


<section class="conditions-utilisation">
    <h2>Mentions légales d'Anime Max</h2>
    <div class="texte">
        <p>Ce site de streaming est exploité par Anime Max, une entité d'Anteion Studio.</p>
        <p>Raison sociale : Anime Max</p>
        <p>Adresse e-mail : anteionstudio@gmail.com</p>

        <h3>Collecte de données personnelles</h3>
        <p>Anime Max collecte des données personnelles dans le cadre de l'utilisation de ce site de streaming. Les seules données collectées sont les cookies et les informations fournies lors de l'inscription des utilisateurs. Cette collecte de données a pour but de sécuriser les utilisateurs et d'améliorer l'expérience sur le site. Les données collectées ne seront pas vendues ou partagées avec des tiers sans le consentement explicite de l'utilisateur, sauf dans les cas prévus par la loi.</p>

        <h3>Cookies</h3>
        <p>Anime Max utilise des cookies pour améliorer l'expérience utilisateur et optimiser le fonctionnement du site. Les cookies sont de petits fichiers texte stockés sur l'ordinateur ou le périphérique de l'utilisateur. Ils permettent de retenir les préférences de l'utilisateur, de suivre les statistiques de navigation et de personnaliser le contenu du site. Les cookies utilisés par Anime Max sont soumis à notre politique de confidentialité.</p>

        <h3>Sécurité des données</h3>
        <p>Anime Max met en place des mesures de sécurité appropriées pour protéger les données personnelles des utilisateurs. Cependant, il est important de noter qu'aucune méthode de transmission de données sur Internet ou de stockage électronique n'est totalement sécurisée. Anime Max ne peut garantir la sécurité absolue des données transmises ou stockées.</p>

        <h3>Modification des informations</h3>
        <p>Les utilisateurs inscrits sur Anime Max ont la possibilité de mettre à jour leurs informations personnelles à tout moment. Pour exercer ce droit, les utilisateurs peuvent accéder à leur compte et modifier les informations nécessaires.</p>

        <h3>Contact</h3>
        <p>Pour toute question ou demande concernant les mentions légales d'Anime Max, veuillez nous contacter à l'adresse e-mail suivante : anteionstudio@gmail.com.</p>

        <p>Dernière mise à jour : 26/05/2023</p>
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
    <p class="full-width">Anime Max, France</p> <!-- Ajout d'une classe "full-width" -->
</footer>
<script>
    // Ajouter les styles spécifiques aux iPhones
    var isiPhone = /iPhone/i.test(navigator.userAgent);
    if (isiPhone) {
        var style = document.createElement('style');
        style.innerHTML = `
          nav .onglets a {
            font-size: 12px;
            padding: 12px 14%;
          }
          nav .gauche img {
            height: 50px; /* Réduire la taille du logo */
          }
          footer {
            padding: 12px 14%;
            font-size: 10px;
          }
          footer .colonne p {
            font-size: 10px;
          }
          .compte {
            font-size: 12px;
          }
        `;
        document.head.appendChild(style);
    }
</script>
</body>
</html>
