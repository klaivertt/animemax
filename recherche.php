<?php
session_start();
require_once 'config.php';
require_once 'simple_html_dom.php';
// Récupérer le terme de recherche saisi par l'utilisateur
$query = $_GET['query'];

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    die();
}

// Récupérer les données de l'utilisateur
$req = $bdd->prepare('SELECT * FROM utilisateurs WHERE token = ?');
$req->execute(array($_SESSION['user']));
$data = $req->fetch();

// Chemin vers le répertoire contenant les images des miniatures
$thumbnailsDirectory = 'img/thumbnails/';

// Fonction pour récupérer la description du film ou de la série à partir de l'API OMDB
function getMovieDescription($title) {
    $apiKey = '6f41fb65'; // Remplacez par votre véritable clé d'API OMDB
    $url = "http://www.omdbapi.com/?apikey=$apiKey&t=" . urlencode($title);

    // Effectuer la requête HTTP vers l'API OMDB
    $response = file_get_contents($url);

    // Vérifier si la requête a réussi
    if ($response !== false) {
        // Convertir la réponse JSON en tableau associatif
        $data = json_decode($response, true);

        // Vérifier si la réponse contient la description
        if (isset($data['Plot'])) {
            return $data['Plot']; // Retourner la description
        }
    }

    // Si la description n'est pas trouvée dans l'API OMDB, effectuer une recherche sur Internet
    return '';
}

function getMovieDescriptionFromHTML($filename) {
    $filepath = $filename . '.html';

    // Vérifier si le fichier existe
    if (file_exists($filepath)) {
        // Charger le contenu HTML du fichier
        $html = file_get_contents($filepath);

        // Créer un objet DOM à partir du contenu HTML
        $dom = new DOMDocument();
        libxml_use_internal_errors(true); // Désactiver les erreurs libxml

        // Charger le contenu HTML dans l'objet DOM
        $dom->loadHTML($html);
        libxml_clear_errors(); // Effacer les erreurs libxml

        // Créer un objet DOMXPath pour effectuer des requêtes XPath
        $xpath = new DOMXPath($dom);

        // Rechercher le paragraphe contenant le synopsis
        $synopsisNode = $xpath->query("//p[contains(@class, 'plot') or contains(@id, 'plot') or contains(@class, 'summary')]")->item(0);

        // Vérifier si le nœud du synopsis existe
        if ($synopsisNode) {
            $synopsis = trim($synopsisNode->nodeValue);
            return $synopsis; // Retourner le synopsis
        }
    }

    return 'Description not found'; // Si le synopsis n'est pas trouvé, retourner un message par défaut
}



// Rechercher les fichiers HTML correspondant au terme de recherche
$htmlFiles = glob('*.html');
$matchingFiles = [];

foreach ($htmlFiles as $file) {
    // Vérifier si le fichier correspond au terme de recherche
    $filename = pathinfo($file, PATHINFO_FILENAME);
    if (stripos($filename, $query) !== false) {
        $matchingFiles[] = $filename;
    }
}

// Vérifier s'il y a des résultats de recherche
if (empty($matchingFiles)) {
    $errorMessage = 'Aucun résultat trouvé pour la recherche : ' . $query;
}

?>


<html>
<head>
    <title>Résultats de recherche - Anime Max</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/recherche.css">
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

<section class="resultats">
    <?php if (isset($errorMessage)) : ?>
        <div class="erreur"><?php echo $errorMessage; ?></div>
    <?php else : ?>
        <?php foreach ($matchingFiles as $file) : ?>
            <?php if (stripos($file, 'saison') === false) : ?>
                <div class="resultat">
                    <div class="affiche">
                        <a href="<?php echo $file . '.html'; ?>">
                            <img src="<?php echo $thumbnailsDirectory . $file . '.jpg'; ?>" alt="Miniature">
                        </a>
                    </div>
                    <div class="infos">
                        <h3><a href="<?php echo $file . '.html'; ?>"><?php echo $file; ?></a></h3>
                        <p><?php echo getMovieDescriptionFromHTML($file); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
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

</body>
</html>
