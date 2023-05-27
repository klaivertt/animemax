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
<html lang="en">
<head>
    <title>Anime Max</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/video.css">
    <link rel="icon" href="https://i.ibb.co/x5VrKjd/animemaxred1.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" crossorigin="anonymous" />
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

<section class="episode-buttons">
    <div class="dropdown">
        <button class="dropbtn" onclick="toggleDropdown('myDropdown1')">Episodes <i class="fas fa-caret-down"></i></button>
        <div class="dropdown-content" id="myDropdown1">
            <a href="#" onclick="changeEpisode('https://vidmoly.to/embed-x023v1eh87hx.html')">Episode 1</a>
            <a href="#" onclick="changeEpisode('https://vidmoly.to/embed-fl9v0rpconti.html')">Episode 2</a>
            <a href="#" onclick="changeEpisode('https://vidmoly.to/embed-fa6w6ciur469.html')">Episode 3</a>
            <a href="#" onclick="changeEpisode('https://vidmoly.to/embed-soq9swqnvqla.html')">Episode 4</a>
        </div>
    </div>
</section>

<section class="films-et-series">
    <div class="vidéo-conteneur">
        <iframe class='vidéo' loading="lazy" src="https://vidmoly.to/embed-x023v1eh87hx.html" scrolling="no" frameborder="5" width="700" height="430" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe>
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
    function toggleDropdown(dropdownId) {
        var dropdown = document.getElementById(dropdownId);
        dropdown.classList.toggle("show");
    }

    window.addEventListener("click", function(event) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains("show") && !event.target.matches(".dropbtn")) {
                openDropdown.classList.remove("show");
            }
        }
    });

    function changeEpisode(episodeUrl) {
        const videoContainer = document.querySelector('.vidéo-conteneur');
        videoContainer.innerHTML = `<iframe class='vidéo' loading="lazy" src="${episodeUrl}" scrolling="no" frameborder="0" width="700" height="430" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe>`;
    }
</script>

<script>
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
