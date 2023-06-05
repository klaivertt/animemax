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

// Vérifier si le paramètre de succès est présent
$successMessage = "";
if (isset($_GET['success']) && $_GET['success'] === 'avatar') {
    $successMessage = "L'avatar a bien été modifié !";
}
$idUtilisateur = 1; // ID de l'utilisateur à récupérer
$sql = "SELECT subscription FROM utilisateurs WHERE id = :idUtilisateur";
$stmt = $bdd->prepare($sql);
$stmt->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer l'abonnement actuel de l'utilisateur à partir de la base de données


if ($result) {
    $subscriptionActuelle = $result["subscription"];
}

// Mettre à jour l'abonnement de l'utilisateur si un nouvel abonnement est sélectionné
if (isset($_POST['souscrire']) && isset($_POST['nouvel_abonnement'])) {
    $nouvelleSubscription = $_POST['nouvel_abonnement'];

    // Mettre à jour la base de données avec la nouvelle subscription
    $updateSql = "UPDATE utilisateurs SET subscription = :nouvelleSubscription WHERE id = :idUtilisateur";
    $stmt = $bdd->prepare($updateSql);
    $stmt->bindParam(':nouvelleSubscription', $nouvelleSubscription, PDO::PARAM_STR);
    $stmt->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
    $stmt->execute();

    // Mettre à jour la subscription actuelle
    $subscriptionActuelle = $nouvelleSubscription;
}
?>


<!doctype html>
<html lang="en">

<head>
    <title>Espace membre</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="icon" href="https://i.ibb.co/pzb7pxM/animemaxred.png">
    <link rel="stylesheet" href="css/profil.css">
</head>


<body>
    <div class="container">
        <div class="col-md-12">
            <?php
            if (isset($_GET['err'])) {
                $err = htmlspecialchars($_GET['err']);
                switch ($err) {
                    case 'current_password':
                        echo "<div class='alert alert-danger'>Le mot de passe actuel est incorrect</div>";
                        break;

                    case 'success_password':
                        echo "<div class='alert alert-success'>Le mot de passe a bien été modifié ! </div>";
                        break;
                }
            }
            ?>

            <div class="text-center">
                <div class="d-flex justify-content-center align-items-center flex-wrap-reverse">
                    <div class="profile-image-container">
                        <img src="img/avatars/<?php echo $data['profile_image']; ?>" alt="Image de profil" class="profile-image">
                        <div class="profile-image-overlay">
                            <button type="button" class="btn btn-custom btn-lg custom-btn" data-toggle="modal" data-target="#change_avatar">
                                Changer mon avatar
                            </button>
                        </div>
                    </div>
                    <div class="welcome-message-container">
                        <h1 class="p-5 welcome-message" style="color: white; font-weight: bold;">Bonjour <?php echo $data['pseudo']; ?> !</h1>
                        <hr />
                        <div class="d-flex flex-wrap justify-content-start">
                            <a href="deconnexion.php" class="btn btn-custom btn-lg custom-btn mb-3">Déconnexion</a>

                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-custom btn-lg custom-btn mb-3" data-toggle="modal" data-target="#change_password">
                                Changer mon mot de passe
                            </button>
                            <button type="button" class="btn btn-custom btn-lg custom-btn mb-3" data-toggle="modal" data-target="#abonnement_modal">
                                Mon abonnement
                            </button>
                            <button type="button" class="btn btn-custom btn-lg custom-btn mb-3" data-toggle="modal" data-target="#profileModal">Profil</button>

                            <!-- Bouton Retour -->
                            <button onclick="history.back()" class="btn btn-secondary btn-lg mb-3">Retour</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="change_password" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Changer mon mot de passe</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                         </div>
                            <div class="modal-body">
                                <form action="layouts/change_password.php" method="POST">
                                    <label for='current_password'>Mot de passe actuel</label>
                                    <input type="password" id="current_password" name="current_password" class="form-control" required/>
                                    <br />
                                    <label for='new_password'>Nouveau mot de passe</label>
                                    <input type="password" id="new_password" name="new_password" class="form-control" required/>
                                    <br />
                                    <label for='new_password_retype'>Re tapez le nouveau mot de passe</label>
                                    <input type="password" id="new_password_retype" name="new_password_retype" class="form-control" required/>
                                    <br />
                                    <button type="submit" class="btn btn-success">Sauvegarder</button>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>

            <form action="enregistrer_avatar.php" method="POST">
                <div class="modal fade" id="change_avatar" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content custom-modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Sélectionner un nouvel avatar</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <h5>Sélectionnez votre nouvel avatar :</h5>
                                <div class="avatar-list">
                                    <?php
                                    $avatars = array(
                                        'avatar1.png',
                                        'avatar2.png',
                                        'avatar3.png',
                                        'avatar4.png',
                                        // kimetsu//
                                        'avatar5.png',
                                        'avatar9.gif',
                                        'avatar12.png',
                                        "avatar22.gif",
                                        "avatar23.gif",
                                        'avatar24.png',
                                        "avatar25.gif",
                                        //endKimetsu//
                                        'avatar6.png',
                                        //TokyoGhoul//
                                        'avatar7.png',
                                        "avatar18.gif",
                                        //endTokyoGhoul//
                                        //onepiece//
                                        'avatar8.png',
                                        "avatar17.gif",
                                        //onepiece//
                                        'avatar10.png',
                                        'avatar11.png',
                                        "avatar13.png",
                                        //MHA//
                                        "avatar14.png",
                                        //mha//
                                        "avatar15.png",                                        
                                        "avatar19.gif",
                                        "avatar20.gif",
                                        //pokémon//
                                        "avatar16.gif",
                                        "avatar26.gif",
                                        //pokémon//
                                        "avatar21.gif",

                                    );
                                    foreach ($avatars as $avatar) {
                                        $avatarChecked = ($data['profile_image'] === $avatar) ? 'checked' : '';
                                        $avatarNumber = str_replace(array('avatar', '.png'), '', $avatar);
                                        $avatarSelectedClass = ($avatarChecked !== '') ? 'avatar-selected' : '';

                                        echo '<div class="avatar-item d-flex flex-wrap">
                                            <div class="mr-3 mb-3">
                                                <input type="radio" name="avatar" id="avatar-' . $avatarNumber . '" value="' . $avatar . '" class="avatar-input visually-hidden" ' . $avatarChecked . ' required>
                                                <label for="avatar-' . $avatarNumber . '" class="avatar-label">
                                                    <img src="img/avatars/' . $avatar . '" alt="' . $avatar . '" class="avatar-image ' . $avatarSelectedClass . '">
                                                </label>
                                            </div>
                                        </div>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-success">Modifier</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

   <!-- Modal HTML -->
<div class="modal fade" id="abonnement_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content custom-modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mon abonnement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-deck">
                    <!-- Abonnement gratuit -->
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Abonnement gratuit</h5>
                            <p class="card-text">Limite la qualité vidéo à 720p</p>
                            <p class="card-text">Affiche des publicités</p>
                        </div>
                        <div class="card-footer">
                            <?php if ($subscriptionActuelle == "Abonnement gratuit") : ?>
                                <button type="button" class="btn btn-custom btn-lg custom-btn" disabled>Abonnement actuel</button>
                            <?php else : ?>
                                <form method="post">
                                    <input type="hidden" name="nouvel_abonnement" value="Abonnement gratuit">
                                    <button type="submit" name="souscrire" class="btn btn-custom btn-lg custom-btn">Souscrire</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>

                   <!-- Abonnement Standard HD -->
<div class="card text-center">
    <div class="card-body">
        <h5 class="card-title">Abonnement Standard HD</h5>
        <p class="card-text">Qualité vidéo jusqu'à 1080p</p>
    </div>
    <div class="card-footer">
        <?php if ($subscriptionActuelle != "Abonnement Standard HD") : ?>
            <form method="post" action="paiement.php"> <!-- Modifier "page_paiement.php" avec le nom de votre page de paiement -->
                <input type="hidden" name="nouvel_abonnement" value="Abonnement Standard HD">
                <button type="submit" name="souscrire" class="btn btn-custom btn-lg custom-btn">Souscrire</button>
            </form>
        <?php else : ?>
            <button type="button" class="btn btn-custom btn-lg custom-btn" disabled>Abonnement actuel</button>
        <?php endif; ?>
    </div>
</div>

<!-- Abonnement Premium -->
<div class="card text-center">
    <div class="card-body">
        <h5 class="card-title">Abonnement Premium</h5>
        <p class="card-text">Qualité vidéo jusqu'à 4K</p>
    </div>
    <div class="card-footer">
        <?php if ($subscriptionActuelle != "Abonnement Premium") : ?>
            <form method="post" action="paiement.php"> <!-- Modifier "page_paiement.php" avec le nom de votre page de paiement -->
                <input type="hidden" name="nouvel_abonnement" value="Abonnement Premium">
                <button type="submit" name="souscrire" class="btn btn-custom btn-lg custom-btn">Souscrire</button>
            </form>
        <?php else : ?>
            <button type="button" class="btn btn-custom btn-lg custom-btn" disabled>Abonnement actuel</button>
        <?php endif; ?>
    </div>
</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content custom-modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Profil Utilisateur</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Pseudo: <?php echo $data['pseudo']?></p>
        <p>Email: <?php echo $data['email']?></p>
        <p>Date d'inscription: <?php echo $data['date_inscription']?></p>
        <p>Abonnement: <?php echo $data['subscription']?></p>
      </div>
    </div>
  </div>
</div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

    <!-- Popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>

</html>
