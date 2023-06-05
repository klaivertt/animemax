<?php 
    require_once 'config.php'; // On inclut la connexion à la BDD

    // Si les variables existent et qu'elles ne sont pas vides
    if (!empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_retype'])) {
        // Patch XSS
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $password_retype = htmlspecialchars($_POST['password_retype']);

        // On vérifie si l'utilisateur existe
        $check = $bdd->prepare('SELECT pseudo, email, password FROM utilisateurs WHERE email = ?');
        $check->execute(array($email));
        $data = $check->fetch();
        $row = $check->rowCount();

        $email = strtolower($email); // On transforme toutes les lettres majuscules en minuscules pour éviter que Foo@gmail.com et foo@gmail.com soient deux comptes différents.

        // Si la requête renvoie un 0 alors l'utilisateur n'existe pas 
        if ($row == 0) {
            if (strlen($pseudo) <= 100) { // On vérifie que la longueur du pseudo <= 100
                if (strlen($email) <= 100) { // On vérifie que la longueur de l'email <= 100
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) { // Si l'email est de la bonne forme
                        if ($password === $password_retype) { // Si les deux mots de passe saisis sont bons

                            // On hash le mot de passe avec Bcrypt, via un coût de 12
                            $cost = ['cost' => 12];
                            $password = password_hash($password, PASSWORD_BCRYPT, $cost);

                            // On stocke l'adresse IP
                            $ip = $_SERVER['REMOTE_ADDR'];

                            /*
                             ATTENTION
                             Vérifiez bien que le champ "token" est présent dans votre table "utilisateurs", il a été rajouté APRÈS la vidéo.
                             Le fichier .sql est disponible, pensez à l'importer !
                             ATTENTION
                            */

                            // Avatar par défaut
                            $defaultAvatar = 'avatar1.png';

                            // Abonnement par défaut
                            $defaultSubscription = 'Abonnement gratuit';

                            // On insère dans la base de données
                            $insert = $bdd->prepare('INSERT INTO utilisateurs(pseudo, email, password, profile_image, ip, token, subscription) VALUES(:pseudo, :email, :password, :profile_image, :ip, :token, :subscription)');
                            $insert->execute(array(
                                'pseudo' => $pseudo,
                                'email' => $email,
                                'password' => $password,
                                'profile_image' => $defaultAvatar,
                                'ip' => $ip,
                                'token' => bin2hex(openssl_random_pseudo_bytes(64)),
                                'subscription' => $defaultSubscription
                            ));
                            // On redirige avec le message de succès
                            header('Location:inscription.php?reg_err=success');
                            die();
                        } else { 
                            header('Location: inscription.php?reg_err=password');
                            die();
                        }
                    } else { 
                        header('Location: inscription.php?reg_err=email');
                        die();
                    }
                } else { 
                    header('Location: inscription.php?reg_err=email_length');
                    die();
                }
            } else { 
                header('Location: inscription.php?reg_err=pseudo_length');
                die();
            }
        } else { 
            header('Location: inscription.php?reg_err=already');
            die();
        }
    }
?>
