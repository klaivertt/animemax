// script.js
function validateLoginForm() {
    var username = document.getElementById("loginUsername").value;
    var password = document.getElementById("loginPassword").value;
  
    // Effectuez la validation des informations ici, par exemple :
    // Vérifiez si le nom d'utilisateur et le mot de passe sont corrects en les comparant avec les données de la base de données.
  
    // Si les informations de connexion sont valides, vous pouvez rediriger l'utilisateur vers index.html
    // window.location.href = "index.html";
  
    return false; // Retourne false pour empêcher l'envoi du formulaire (à des fins de démonstration)
  }
  
  function validateSignupForm() {
    var username = document.getElementById("signupUsername").value;
    var email = document.getElementById("signupEmail").value;
    var password = document.getElementById("signupPassword").value;
    var confirmPassword = document.getElementById("signupConfirmPassword").value;
  
    // Effectuez la validation des informations ici, par exemple :
    // Vérifiez si les champs sont remplis correctement.
    // Vérifiez si le mot de passe et la confirmation du mot de passe correspondent.
    // Insérez les données dans la base de données.
  
    // Si l'inscription est réussie, vous pouvez rediriger l'utilisateur vers index.html
    // window.location.href = "index.html";
  
    return false; // Retourne false pour empêcher l'envoi du formulaire (à des fins de démonstration)
  }
  