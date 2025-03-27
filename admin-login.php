<?php
// Démarrage de la session de manière sécurisée
session_start();
error_reporting(1);
require_once('includes/config.php');

// Génération d'un token CSRF s'il n'existe pas déjà
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Déconnexion si déjà connecté
if (isset($_SESSION['alogin']) && $_SESSION['alogin'] !== '') {
    $_SESSION['alogin'] = '';
}

// Traitement du formulaire de connexion
if (isset($_POST['login'])) {
    // Récupération et filtrage des entrées
    $uname = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    
    // Vérifier le nombre de tentatives par IP dans les 30 dernières minutes
    $sql = "SELECT COUNT(*) as attempts FROM login_attempts 
            WHERE ip_address = ? AND attempt_time > DATE_SUB(NOW(), INTERVAL 30 MINUTE)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$ip]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (isset($result['attempts']) && $result['attempts'] >= 5) {
        $error_message = "Trop de tentatives de connexion. Veuillez réessayer dans 30 minutes.";
    } else {
        // Utilisation de requêtes préparées pour éviter l'injection SQL
        $sql = "SELECT UserName, Password, is_admin FROM users WHERE UserName = ?";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$uname]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $stored_password = $row['Password'];  // Récupérer le mot de passe haché stocké

            // Vérification du mot de passe avec password_verify
            if (password_verify($password, $stored_password)) {
                // Mot de passe correct
                $_SESSION['alogin'] = htmlspecialchars($row['UserName']);
                $_SESSION['is_admin'] = (int)$row['is_admin'];
                
                // Supprimer les tentatives précédentes de cette IP en cas de succès
                $sql = "DELETE FROM login_attempts WHERE ip_address = ?";
                $stmt = $dbh->prepare($sql);
                $stmt->execute([$ip]);
                
                // Redirection sécurisée
                header('Location: dashboard.php');
                exit();
            } else {
                // Mot de passe incorrect - Enregistrer la tentative
                $sql = "INSERT INTO login_attempts (username, ip_address) VALUES (?, ?)";
                $stmt = $dbh->prepare($sql);
                $stmt->execute([$uname, $ip]);
                
                // Obtenir le nombre de tentatives restantes pour cette IP
                $sql = "SELECT COUNT(*) as attempts FROM login_attempts 
                        WHERE ip_address = ? AND attempt_time > DATE_SUB(NOW(), INTERVAL 30 MINUTE)";
                $stmt = $dbh->prepare($sql);
                $stmt->execute([$ip]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                $attempts_left = isset($result['attempts']) ? (5 - $result['attempts']) : 5;
                $error_message = "Identifiants incorrects. Tentatives restantes : " . $attempts_left;
            }
        } else {
            // Utilisateur non trouvé - Enregistrer la tentative
            $sql = "INSERT INTO login_attempts (username, ip_address) VALUES (?, ?)";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$uname, $ip]);
            
            // Obtenir le nombre de tentatives restantes pour cette IP
            $sql = "SELECT COUNT(*) as attempts FROM login_attempts 
                    WHERE ip_address = ? AND attempt_time > DATE_SUB(NOW(), INTERVAL 30 MINUTE)";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([$ip]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $attempts_left = isset($result['attempts']) ? (5 - $result['attempts']) : 5;
            $error_message = "Identifiants incorrects. Tentatives restantes : " . $attempts_left;
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
                                          <h3 style="color: white;"> <strong>Login</strong></h3>
                                       </div>
                                    </div>
                                    <?php if (isset($error_message)): ?>
                                    <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p><br><br>
                                    <?php endif; ?>
                                    <div class="panel-body p-20">
                                       <form class="admin-login" method="post">
                                          <div class="form-group">
                                             <label for="inputEmail3" class="control-label">Identifiant</label>
                                             <input type="text" name="username" class="form-control" id="inputEmail3" placeholder="Identifiant" required>
                                          </div>
                                          <div class="form-group">
                                             <label for="inputPassword3" class="control-label">Mot de passe</label>
                                             <input type="password" name="password" class="form-control" id="inputPassword3" placeholder="Mot de passe" required>
                                          </div><br>
                                          <div class="form-group mt-20">
                                             <button type="submit" name="login" class="btn login-btn">Se Connecter</button>
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