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

?>

<!DOCTYPE html>
<html>
<head>
    <title>Anime Max - Conditions d'utilisation</title>
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
    <h2>Conditions d'utilisation d'Anime Max</h2>
    <div class="texte">
        <p>Les présentes conditions d'utilisation (ci-après désignées les "Conditions") régissent l'utilisation du site de streaming Anime Max (ci-après désigné le "Site"). En utilisant le Site, vous acceptez pleinement et sans réserve les présentes Conditions. Veuillez les lire attentivement.</p>
        <h3>Protection des données personnelles</h3>
        <p>Anime Max s'engage à protéger la vie privée de ses utilisateurs. En vous inscrivant sur le Site, vous consentez à ce que les cookies utilisés collectent des informations pour améliorer l'expérience utilisateur et les performances du site. Ces informations peuvent être utilisées de manière anonyme et agrégée pour des analyses statistiques internes. Vos données personnelles ne seront pas vendues ou partagées avec des tiers sans votre consentement explicite, sauf dans les cas prévus par la loi.</p>
        <h3>Qualité vidéo</h3>
        <p>La qualité vidéo sur Anime Max peut varier d'une plateforme à l'autre en raison de facteurs tels que la vitesse de votre connexion Internet, la capacité de votre appareil, la disponibilité des serveurs et d'autres conditions techniques. Anime Max mettra tout en œuvre pour offrir une expérience de streaming optimale, mais ne peut garantir une qualité vidéo constante sur toutes les plateformes.</p>
        <h3>Confidentialité et sécurité</h3>
        <p>Vous êtes responsable de la sécurité de votre compte Anime Max. Ne partagez pas votre mot de passe avec d'autres personnes et assurez-vous de ne pas divulguer vos informations d'identification à des tiers. L'accès à votre compte est réservé à un usage personnel et vous êtes entièrement responsable de toutes les activités qui se produisent sous votre compte.</p>
        <h3>Modification des conditions d'utilisation</h3>
        <p>Anime Max se réserve le droit de modifier les présentes Conditions à tout moment, sans préavis. Les modifications seront effectives dès leur publication sur le Site. Il est de votre responsabilité de consulter régulièrement les Conditions pour prendre connaissance des éventuelles mises à jour. En continuant à utiliser le Site après la publication des modifications, vous acceptez les Conditions modifiées.</p>
        <p>Nous vous encourageons à consulter régulièrement les Conditions d'utilisation d'Anime Max afin de rester informé des règles applicables à votre utilisation du Site.</p>
        <p>Si vous avez des questions ou des préoccupations concernant les Conditions d'utilisation, veuillez nous contacter via les coordonnées fournies sur le Site.</p>
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
