<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Récupérer les valeurs soumises par le formulaire
  $name = $_POST["name"];
  $email = $_POST["email"];
  $subject = $_POST["subject"];
  $message = $_POST["message"];

  // Construction du corps de l'e-mail
  $body = "Nom: " . $name . "\n";
  $body .= "Adresse e-mail: " . $email . "\n";
  $body .= "Sujet: " . $subject . "\n";
  $body .= "Message:\n" . $message;

  // En-têtes de l'e-mail
  $headers = "From: " . $email . "\r\n";
  $headers .= "Reply-To: " . $email . "\r\n";

  // Envoyer l'e-mail
  $success = mail("anteionstudio@gmail.com", $subject, $body, $headers);

  // Vérifier si l'e-mail a été envoyé avec succès
  if ($success) {
    http_response_code(200);
    echo "Le message a été envoyé avec succès.";
  } else {
    http_response_code(500);
    echo "Une erreur s'est produite lors de l'envoi du message.";
  }
} else {
  http_response_code(400);
  echo "Erreur: Requête invalide.";
}
?>