<?php
// Démarrage de la session de manière sécurisée
session_start();
error_reporting(1);
require_once('includes/config.php');

// Génération d'un token CSRF s'il n'existe pas déjà
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Traitement du formulaire d'inscription
if (isset($_POST['register'])) {
    $username = trim($_POST['nom_utilisateur'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_verif = $_POST['password_verif'] ?? '';
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    
    // Validation des champs
    if (empty($username) || empty($email) || empty($password) || empty($password_verif)) {
        $error_message = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Format d'email invalide.";
    } elseif ($password !== $password_verif) {
        $error_message = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($password) < 8) {
        $error_message = "Le mot de passe doit contenir au moins 8 caractères.";
    } else {
        // Vérifier si l'utilisateur ou l'email existent déjà
        $sql = "SELECT COUNT(*) FROM users WHERE NameUser = ? OR UserName = ?";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$username, $email]);
        $exists = $stmt->fetchColumn();
        
        if ($exists) {
            $error_message = "Nom d'utilisateur ou email déjà utilisé.";
        } else {
            // Hachage du mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insérer l'utilisateur dans la base de données
            $sql = "INSERT INTO users (NameUser, UserName, Password) VALUES (?, ?, ?)";
            $stmt = $dbh->prepare($sql);
            if ($stmt->execute([$username, $email, $hashed_password])) {
                $success_message = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                // Redirection sécurisée
                header('Location: admin-login.php');
                exit();
            } else {
                $error_message = "Une erreur est survenue lors de l'inscription.";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Login</title>
      <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css" media="screen">
      <link rel="stylesheet" href="assets/css/font-awesome.min.css" media="screen">
      <link rel="stylesheet" href="assets/css/animate-css/animate.min.css" media="screen">
      <link rel="stylesheet" href="assets/css/prism/prism.css" media="screen">
      <link rel="stylesheet" href="assets/css/main.css" media="screen">
      <script src="assets/js/modernizr/modernizr.min.js"></script>
      <style>
      .error-message {
          background-color: #fce4e4;
          border: 1px solid #fcc2c3;
          float: left;
          padding: 0px 30px;
          clear: both;
      }
      </style>
   </head>
   <body class="" style="background-image: url(assets/images/back2.jpg);
      background-color: #ffffff;
      background-size: cover;
      height: 100%;
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;">
  
      <div class="main-wrapper">
         <div class="">
            <div class="row">
               <div class="col-md-offset-7 col-lg-5">
                  <section class="section">
                     <div class="row mt-40">
                        <div class="col-md-offset-2 col-md-10 pt-50">
                           <div class="row mt-30 ">
                              <div class="col-md-11">
                                <div class="panel login-box" style="background: #172541;">
                                    <div class="panel-heading">
                                       <div class="text-center"><br>
                                          <a href="#">
                                          <img style="height: 70px" src="assets/images/footer-logo.png" alt="Logo"></a>
                                          <br>
                                          <h3 style="color: white;"> <strong>S'inscrire</strong></h3>
                                       </div>
                                    </div>
                                    <?php if (isset($error_message)): ?>
                                    <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p><br><br>
                                    <?php endif; ?>
                                    <div class="panel-body p-20">
                                       <form class="admin-login" method="post">
                                          <div class="form-group">
                                             <label for="nom_utilisateur" class="control-label">Nom d'utilisateur</label>
                                             <input type="text" name="nom_utilisateur" class="form-control" id="nom_utilisateur" placeholder="Nom d'utilisateur" required>
                                          </div>
                                          <div class="form-group">
                                             <label for="email" class="control-label">Email</label>
                                             <input type="text" name="email" class="form-control" id="email" placeholder="Email" required>
                                          </div>
                                          <div class="form-group">
                                             <label for="inputPassword3" class="control-label">Mot de passe</label>
                                             <input type="password" name="password" class="form-control" id="inputPassword3" placeholder="Mot de passe" required>
                                          </div>
                                          <div class="form-group">
                                             <label for="password_verif" class="control-label">Confirmer le mot de passe</label>
                                             <input type="password" name="password_verif" class="form-control" id="password_verif" placeholder="Mot de passe" required>
                                          </div><br>
                                          <div class="form-group mt-20">
                                             <button type="submit" name="register" class="btn login-btn">S'inscrire</button>
                                          </div>
                                          <div class="col-sm-6">
                                             <a href="index.php" class="text-white">Retour à l'accueil</a>
                                          </div>
                                          <br>
                                       </form>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </section>
               </div>
            </div>
         </div>
      </div>
      
      <!-- Scripts -->
      <script src="assets/js/jquery/jquery-2.2.4.min.js"></script>
      <script src="assets/js/jquery-ui/jquery-ui.min.js"></script>
      <script src="assets/js/bootstrap/bootstrap.min.js"></script>
      <script src="assets/js/pace/pace.min.js"></script>
      <script src="assets/js/lobipanel/lobipanel.min.js"></script>
      <script src="assets/js/iscroll/iscroll.js"></script>
      <script src="assets/js/main.js"></script>
   </body>
</html>