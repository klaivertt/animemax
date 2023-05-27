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
    <link rel="stylesheet" href="css/choix.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
    <link rel="icon" href="https://i.ibb.co/x5VrKjd/animemaxred1.png">
    <link rel="stylesheet" href="css/all.css">
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

<section class="films-et-series">
    <div class="vidéo-conteneur">
        <iframe class="vidéo" src="https://www.youtube.com/embed/Ogr9pOY8Wew" title="Demon Slayer: Kimetsu no Yaiba | TRAILER OFFICIEL" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    </div>
    <div class="episode-buttons">
        <div class="dropdown">
            <button class="dropbtn" onclick="toggleDropdown('myDropdown1')">Episodes VO <i class="fas fa-caret-down"></i></button>
            <div class="dropdown-content" id="myDropdown1">
                <a href="KimetsunoYaibaVosaison1.html">Saison 1</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn" onclick="toggleDropdown('myDropdown2')">Episodes VF <i class="fas fa-caret-down"></i></button>
            <div class="dropdown-content" id="myDropdown2">
                <a href="KimetsunoYaibaEP1.html">Pas encore disponible</a>
            </div>
        </div>
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
