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
if (isset($_GET['success']) && $_GET['success'] === 'avatar') {
    $successMessage = "L'avatar a bien été modifié !";
}

?>

<!doctype html>
<html lang="en">

<head>
    <title>Espace membre</title>
    <!-- Required meta tags -->
    <!-- Meta tags obligatoires -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

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
            width: 150px;
            height: 150px;
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

        .avatar-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .avatar-item {
            margin: 10px;
        }

        .avatar-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            cursor: pointer;
        }

        .avatar-image:hover {
            border: 2px solid red;
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
    <div class="d-flex justify-content-center align-items-center">
        <div class="profile-image-container">
            <img src="img/avatars/<?php echo $data['profile_image']; ?>" alt="Image de profil" class="profile-image">
        </div>
        <div class="ml-3">
            <h1 class="p-5 welcome-message" style="color: white; font-weight: bold;">Bonjour <?php echo $data['pseudo']; ?> !</h1>

            <hr />
            <a href="deconnexion.php" class="btn btn-danger btn-lg">Déconnexion</a>

            <!-- Button trigger modal -->
            <button type="button" class="btn btn-custom btn-lg custom-btn" data-toggle="modal" data-target="#change_password">
                Changer mon mot de passe
            </button>

            <!-- Bouton Retour -->
            <button onclick="history.back()" class="btn btn-secondary btn-lg">Retour</button>
        </div>
    </div>
</div>


            <!-- Modal -->
            <div class="modal fade" id="change_password" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content custom-modal-content">
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
    <div class="modal-body">
        <h5>Sélectionnez votre nouvel avatar :</h5>
        <div class="avatar-list">
            <?php
            $avatars = array(
                'avatar-1.png',
                'avatar-2.png',
                'avatar-3.png',
                'avatar-4.png',
                'avatar-5.jpeg',
                'avatar-6.jpg'
            );

            foreach ($avatars as $avatar) {
                $avatarChecked = ($data['profile_image'] === $avatar) ? 'checked' : '';
                echo '<div class="avatar-item">
                        <label>
                            <input type="radio" name="avatar" value="' . $avatar . '" class="avatar-input" required ' . $avatarChecked . '>
                            <img src="img/avatars/' . $avatar . '" alt="' . $avatar . '" class="avatar-image">
                        </label>
                    </div>';
            }
            ?>
        </div>
        <br />
        <button type="submit" class="btn btn-success">Modifier</button>
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

    <script>
        $(document).ready(function () {
            // Lorsque l'utilisateur sélectionne une image d'avatar, mettre à jour l'avatar à côté de "Bonjour"
            $('.avatar-input').on('change', function () {
                var selectedAvatar = $(this).val();
                var avatarNumber = selectedAvatar.split('-')[1].split('.')[0];
                var welcomeMessage = $('.welcome-message');
                var avatarImage = welcomeMessage.siblings('.profile-image-container').find('.profile-image');

                avatarImage.attr('src', 'img/avatars/' + selectedAvatar);
            });
        });
    </script>
</body>

</html>
