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
    <title>Anime Max - Politique de confidentialité</title>
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
    <h2>Politique de confidentialité d'Anime Max</h2>
    <div class="texte">
        <p>Chez Anime Max, nous nous engageons à protéger la confidentialité des informations personnelles de nos utilisateurs. Cette politique de confidentialité explique comment nous collectons, utilisons, divulguons et protégeons les informations que vous nous fournissez lorsque vous utilisez notre site web.</p>
        <h3>Collecte d'informations</h3>
        <p>Lorsque vous visitez notre site web, nous pouvons collecter certaines informations personnelles vous concernant, telles que votre nom, votre adresse e-mail et d'autres informations que vous choisissez de nous fournir. Nous utilisons ces informations pour répondre à vos demandes, améliorer votre expérience sur notre site et vous envoyer des informations pertinentes.</p>
        <h3>Utilisation des informations</h3>
        <p>Nous utilisons les informations nécessaires pour vous fournir les services et les fonctionnalités de notre site web. Nous pouvons également utiliser ces informations pour vous contacter concernant des mises à jour, des offres promotionnelles ou d'autres informations importantes liées à Anime Max.</p>
        <h3>Divulgation des informations</h3>
        <p>Nous ne divulguons pas vos informations personnelles à des tiers, sauf dans les cas où la loi l'exige ou lorsque nous avons votre consentement explicite. Cependant, nous pouvons partager des informations agrégées ou anonymisées qui ne permettent pas votre identification personnelle.</p>
        <h3>Sécurité des informations</h3>
        <p>Nous prenons des mesures de sécurité adéquates pour protéger vos informations personnelles contre tout accès non autorisé, utilisation abusive ou divulgation. Cependant, veuillez noter qu'aucune méthode de transmission sur Internet ou de stockage électronique n'est totalement sécurisée, et Anime Max ne peut garantir la sécurité absolue de vos informations.</p>
        <h3>Cookies</h3>
        <p>Notre site web peut utiliser des cookies pour améliorer votre expérience de navigation. Les cookies sont de petits fichiers texte stockés sur votre appareil qui nous permettent de reconnaître votre navigateur et de vous offrir un contenu personnalisé. Vous avez la possibilité de désactiver les cookies dans les paramètres de votre navigateur, mais cela peut limiter certaines fonctionnalités de notre site.</p>
        <h3>Liens externes</h3>
        <p>Notre site web peut contenir des liens vers des sites web externes. Nous ne sommes pas responsables de la collecte, de l'utilisation ou de la divulgation de vos informations personnelles par ces sites web. Nous vous encourageons à consulter les politiques de confidentialité de ces sites web avant de fournir vos informations personnelles.</p>
        <h3>Modifications de la politique de confidentialité</h3>
        <p>Nous nous réservons le droit de modifier cette politique de confidentialité à tout moment. Toute modification sera publiée sur cette page. Nous vous encourageons à consulter régulièrement cette politique de confidentialité pour rester informé des mises à jour.</p>
        <h3>Contact</h3>
        <p>Si vous avez des questions ou des préoccupations concernant notre politique de confidentialité, veuillez nous contacter à l'adresse suivante : [adresse e-mail de contact].</p>
        <p>En utilisant le site web d'Anime Max, vous acceptez les termes de cette politique de confidentialité.</p>
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
    <div class="clearfix"></div>
    <p class="full-width">Anime Max, France</p>
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
            height: 50px;
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
