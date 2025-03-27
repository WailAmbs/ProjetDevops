<?php
   session_start();
   error_reporting(1);
   include('includes/config.php');
   
   if($_SESSION['alogin']!=''){
		$_SESSION['alogin']='';
   }
   if(isset($_POST['login']))
   {
      $uname = $_POST['username'];
      $password = $_POST['password'];
      $ip = $_SERVER['REMOTE_ADDR'];
      
      // Vérifier le nombre de tentatives par IP dans les 30 dernières minutes
      $sql = "SELECT COUNT(*) as attempts FROM login_attempts 
              WHERE ip_address = ? AND attempt_time > DATE_SUB(NOW(), INTERVAL 30 MINUTE)";
      $stmt = $dbh->prepare($sql);
      $stmt->execute([$ip]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if ($result['attempts'] >= 5) {
          $_SESSION['msgErreur'] = "Trop de tentatives de connexion. Veuillez réessayer dans 30 minutes.";
          header('Location: admin-login.php');
          exit();
      }

      // Utilisation de requêtes préparées pour éviter l'injection SQL
      $sql = "SELECT UserName, Password, is_admin FROM users WHERE UserName = ?";
      $stmt = $dbh1->prepare($sql);
      $stmt->bind_param("s", $uname);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
          $row = $result->fetch_array();
          $stored_password = $row['Password'];  // Récupérer le mot de passe haché stocké

          // Vérification du mot de passe avec password_verify
          if (password_verify($password, $stored_password)) {
             // Mot de passe correct
             $_SESSION['alogin'] = $row['UserName'];
             $_SESSION['is_admin'] = $row['is_admin'];
             
             // Supprimer les tentatives précédentes de cette IP en cas de succès
             $sql = "DELETE FROM login_attempts WHERE ip_address = ?";
             $stmt = $dbh->prepare($sql);
             $stmt->execute([$ip]);
             
             header('Location: dashboard.php');
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
             
             $_SESSION['msgErreur'] = "Mauvais identifiant / mot de passe. Tentatives restantes : " . (5 - $result['attempts']);
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
          
          $_SESSION['msgErreur'] = "Mauvais identifiant / mot de passe. Tentatives restantes : " . (5 - $result['attempts']);
      }
   }
?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Login</title>
      <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css" media="screen" >
      <link rel="stylesheet" href="assets/css/font-awesome.min.css" media="screen" >
      <link rel="stylesheet" href="assets/css/animate-css/animate.min.css" media="screen" >
      <link rel="stylesheet" href="assets/css/prism/prism.css" media="screen" >

      <link rel="stylesheet" href="assets/css/main.css" media="screen" >
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


 
 
  /* Center and scale the image nicely */
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;">
  
      <div class="main-wrapper">
         <div class="">
            <div class="row">
               <div class="col-md-offset-7 col-lg-5">
                  <section class="section">
                     <div class="row mt-40">
                        <div class="col-md-offset-2 col-md-10  pt-50">
                           <div class="row mt-30 ">
                              <div class="col-md-11">
                                <div class="panel login-box" style="    background: #172541;">
                                    <div class="panel-heading">

                                       <div class="text-center"><br>
                                          <a href="#">
                    <img style="height: 70px" src="assets/images/footer-logo.png"></a>
                    <br>
                                          <h3 style="color: white;"> <strong>Login</strong></h3>
                                       </div>
                                    </div>
									<?php if (isset($_SESSION['msgErreur'])) { ?>
																	<p class="error-message"><?php echo $_SESSION['msgErreur']; unset($_SESSION['msgErreur']);?> </p><br><br>
									<?php } ?>
                                    <div class="panel-body p-20">
                                       <form class="admin-login" method="post">
                                          <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                          <div class="form-group">
                                             <label for="inputEmail3" class="control-label">Identifiant</label>
                                             <input type="text" name="username" class="form-control" id="inputEmail3" placeholder="Identifiant">
                                          </div>
                                          <div class="form-group">
                                             <label for="inputPassword3" class="control-label">Mot de passe</label>
                                             <input type="password" name="password" class="form-control" id="inputPassword3" placeholder="Mot de passe">
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
                              <!-- /.col-md-11 -->
                           </div>
                           <!-- /.row -->
                        </div>
                        <!-- /.col-md-12 -->
                     </div>
                     <!-- /.row -->
                  </section>
               </div>
               <!-- /.col-md-6 -->
            </div>
            <!-- /.row -->
         </div>
         <!-- /. -->
      </div>
      <!-- /.main-wrapper -->
      <!-- ========== COMMON JS FILES ========== -->
      <script src="assets/js/jquery/jquery-2.2.4.min.js"></script>
      <script src="assets/js/jquery-ui/jquery-ui.min.js"></script>
      <script src="assets/js/bootstrap/bootstrap.min.js"></script>
      <script src="assets/js/pace/pace.min.js"></script>
      <script src="assets/js/lobipanel/lobipanel.min.js"></script>
      <script src="assets/js/iscroll/iscroll.js"></script>
      <!-- ========== PAGE JS FILES ========== -->
      <!-- ========== THEME JS ========== -->
      <script src="assets/js/main.js"></script>
      <script>
         $(function(){
         
         });
      </script>
 
   </body>
</html>