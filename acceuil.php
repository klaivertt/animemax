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

// Récupérer les fichiers HTML dans le répertoire
$files = glob('*.html');
$series = array();

foreach ($files as $file) {
    // Vérifier si le fichier ne contient pas le mot "saison" ou "film"
    if (strpos($file, 'saison') === false && strpos($file, 'film') === false) {
        // Ajouter le nom du fichier à la liste des séries
        $series[] = $file;
    }
}

?>

<html>
<head>
    <title>Accueil - Anime Max</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/acceuil.css">
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
        <form method="GET" action="recherche.php">
            <input type="text" name="query" placeholder="Rechercher..." />
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
        <p><i class="fas fa-bell"></i></p>
        <p>
            <a href="profil.php" class="compte" style="display: flex; align-items: center;">
                <img src="img/avatars/<?php echo $data['profile_image']; ?>" alt="Image de profil" class="round-image" height="50" width="50">
                <span style="margin-left: 5px;"><?php echo $data['pseudo']; ?></span>
            </a>
        </p>
    </div>
</nav>

<section class="embed">
    <div class="video-conteneur">
        <div id="video-container"></div>
    </div>
</section>

<section class="seriefonctionne">
    <h2>Série fonctionnelle</h2>
    <div class="liste">
        <?php foreach ($series as $serie): ?>
            <?php $thumbnail = 'img/thumbnails/' . pathinfo($serie, PATHINFO_FILENAME) . '.jpg'; ?>
            <a href="<?php echo $serie; ?>"><img src="<?php echo $thumbnail; ?>" alt=""></a>
        <?php endforeach; ?>
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
    // Liste des vidéos
    var videos = [
        '<video width="100%" height="400" autoplay muted><source src="videos/jojotrailer.mp4" type="video/mp4"></video>',
        '<video width="100%" height="400" autoplay muted><source src="videos/nagatorotrailer.mp4" type="video/mp4"></video>',
        '<video width="100%" height="400" autoplay muted><source src="videos/KimetsunoYaibaTRAILER.mp4" type="video/mp4"></video>',
    ];

    // Sélection aléatoire d'une vidéo
    var randomIndex = Math.floor(Math.random() * videos.length);
    var randomVideo = videos[randomIndex];

    // Affichage de la vidéo sélectionnée dans le conteneur
    document.getElementById('video-container').innerHTML = randomVideo;
</script>

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
        .compte img.profile-image {
            height: 20px;
            width: 20px;
            margin-right: 5px;
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
