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
    <style>
        body {
            background-color: #242424;
        }

        .btn-custom {
            background-color: red;
            color: white;
        }

        .btn-custom:hover,
        .btn-custom:focus {
            background-color: darkred;
            color: white;
        }

        .custom-modal-content {
            background-color: #242424;
        }

        .custom-modal-content h5.modal-title,
        .custom-modal-content label {
            color: white;
        }

        hr {
            border-color: white;
        }

        .profile-image-container {
            position: relative;
            width: 300px;
            height: 300px;
            overflow: hidden;
            border-radius: 50%;
        }

        .profile-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s;
            cursor: pointer;
        }

        .profile-image-container:hover .profile-image-overlay {
            opacity: 1;
        }

        .profile-image-overlay .btn {
            color: white;
            font-size: 18px;
        }

        .avatar-item {
            margin: 10px;
        }

        .avatar-input {
            display: none;
        }

        .avatar-label {
            display: block;
            position: relative;
            cursor: pointer;
        }

        .avatar-image {
            width: 125px;
            height: 125px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.3s;
        }

        .avatar-image:hover {
            border-color: red;
        }

        .avatar-input:checked + .avatar-label .avatar-image {
            border-color: red;
        }

        .avatar-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
        }

        .avatar-item {
            margin: 10px 5px;
        }

        .container {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            height: 100vh;
            padding-left: 50px;
        }

        .welcome-message-container {
            margin-left: 50px;
            text-align: center;
        }

        @media (max-width: 576px) {
            .container {
                flex-direction: column;
                padding-left: 0;
                padding-bottom: 20px;
            }

            .welcome-message-container {
                margin-left: 0;
                margin-bottom: 20px;
            }

            .profile-image-container {
                width: 150px;
                height: 150px;
            }

            .profile-image-overlay .btn {
                font-size: 16px;
            }
        }

        .d-flex.justify-content-start > *:not(:last-child) {
            margin-right: 5px;
        }
    </style>
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

                            <!-- Bouton Retour -->
                            <button onclick="history.back()" class="btn btn-secondary btn-lg mb-3">Retour</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="change_password" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <!-- Modal content... -->
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
                                        'avatar5.png',
                                        'avatar6.png',
                                        'avatar7.png',
                                        'avatar8.png',
                                        'avatar9.gif',
                                        'avatar10.png',
                                        'avatar11.png',
                                        'avatar12.png',
                                        "avatar13.png",
                                        "avatar14.png",
                                        "avatar15.png",
                                        "avatar16.gif",
                                        "avatar17.gif",
                                        "avatar18.gif",
                                        "avatar19.gif",
                                        "avatar20.gif",
                                        "avatar21.gif"

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

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

    <!-- Popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>

</html>
