<?php
error_reporting(0);
include('includes/config.php'); 
?>


<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Notes Esigelec</title>
<link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
      <link rel="stylesheet" type="text/css" href="assets/css/style.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" type="text/css" href="assets/css/owl.carousel.min.css">
        <link rel="stylesheet" type="text/css" href="assets/css/owl.theme.default.min.css">
     
   </head>
   <body>
      <header>
         <nav class="navbar navbar-expand-lg bg-white">
          <div class="container">
            <a class="navbar-brand" href="#"><img src="assets/images/logo.png" style="height: 70px"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="index.php">Acceuil</a>
                </li>
               
                <li class="nav-item">
                  <a class="nav-link" href="find-result.php">Etudiant</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link"  href="admin-login.php">Admin</a>
                </li>
              </ul>
              <form class="d-flex" role="search">
                <button class="btn btn-danger" type="submit" href="">Recherche</button>
              </form>
            </div>
          </div>
        </nav>
      </header>
      <section class="main-slider">
        <div class="owl-carousel owl-theme" id="slider">
            <div class="item">
              <div class=" banner-view banner-top1 d-flex align-items-center justify-content-center text-center">
                <div class="banner-info-bg">
                  <h5>Gestion des résultats des étudiants</h5>
                  <p class="mt-md-4 mt-3"> </p>
                  <a class="btn btn-style btn-danger mt-sm-5 mt-4 mr-2" href=""> En savoir plus</a>
                </div>
              </div>
            </div>
             <div class="item">
              <div class=" banner-view banner-top2 d-flex align-items-center justify-content-center text-center">
                <div class="banner-info-bg">
                  <h5>Gestion des résultats des étudiants</h5>
                  <p class="mt-md-4 mt-3"></p>
                  <a class="btn btn-style btn-danger mt-sm-5 mt-4 mr-2" href=""> En savoir plus</a>
                </div>
              </div>
            </div>
         
            
        </div>

      </section>
      <section class="py-5 about"> 
        <div class="container">
          <div class="row ">
           <div class="col-md-6 ">
            <h3 class="title-big mx-0">Flash Infos</h3>
                 <marquee direction="up"  onmouseover="this.stop();" onmouseout="this.start();" class="mt-5">
                   <ul class="">
 <?php $sql = "SELECT * from tblnotice";
$query = $dbh->prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{   ?>                      
<li><a href="notice-details.php?nid=<?php echo htmlentities($result->id);?>" target="_blank" class="text-dark"><?php echo htmlentities($result->noticeTitle);?></li>
<?php }} ?>

                   </ul>
               </marquee>
                </div>
            <div class="col-md-6">
              <img src="assets/images/about.jpg" class="img-fluid">
            </div>
          </div>
        </div>
      </section>
      <section class="py-5 service"> 
        <div class="container-fluid">
           <h3 class="title-big text-center pb-5">Formations</h3>
            <div class="row ">
                  <div class="col-md-2">
                    <div class="grids5-info">
                        <a href="#service" class="d-block zoom"><img src="assets/images/s1.jpg" alt="" class="img-fluid service-image"></a>
                        <div class="blog-info">
                            <h4><a href="#service">Cycle Prépa </a></h4>
                        </div>
                    </div>
                </div>
            <div class="col-md-2">
                    <div class="grids5-info">
                        <a href="#service" class="d-block zoom"><img src="assets/images/s2.jpg" alt="" class="img-fluid service-image"></a>
                        <div class="blog-info">
                            <h4><a href="#service">Cycle ingénieur classique</a></h4>
                        </div>
                    </div>
            </div>
            <div class="col-md-2">
                    <div class="grids5-info">
                        <a href="#service" class="d-block zoom"><img src="assets/images/s3.jpg" alt="" class="img-fluid service-image"></a>
                        <div class="blog-info">
                            <h4><a href="#service">Cycle ingénieur apprentissage</a></h4>
                        </div>
                    </div>
                </div>
				            <div class="col-md-2">
                    <div class="grids5-info">
                        <a href="#service" class="d-block zoom"><img src="assets/images/s4.jpg" alt="" class="img-fluid service-image"></a>
                        <div class="blog-info">
                            <h4><a href="#service">15 Dominantes</a></h4>
                        </div>
                    </div>
                </div>
				            <div class="col-md-2">
                    <div class="grids5-info">
                        <a href="#service" class="d-block zoom"><img src="assets/images/s5.jpg" alt="" class="img-fluid service-image"></a>
                        <div class="blog-info">
                            <h4><a href="#service">Formation continue</a></h4>
                        </div>
                    </div>
                </div>
				            <div class="col-md-2">
                    <div class="grids5-info">
                        <a href="#service" class="d-block zoom"><img src="assets/images/s6.jpg" alt="" class="img-fluid service-image"></a>
                        <div class="blog-info">
                            <h4><a href="#service">Formation doctorale</a></h4>
                        </div>
                    </div>
                </div>
        </div>
      </section>

      <section class="py-5" id="services">
    <div class="features-main py-lg-5 py-md-3">
        <div class="container">
            <div class="header-section text-center mx-auto">
                <h3 class="title-big">Actualités</h3>
            </div>
            <div class="row features mt-lg-4">
                <div class="col-lg-4 col-md-6 feature-grid">
                    <div class="feature-body service1"><a href="#url">
                            <div class="feature-img">
                                <span class="fa fa-sun" aria-hidden="true"></span>
                            </div>
                            </a><div class="feature-info mt-4"><a href="#url">
                                <h3 class="feature-titel mb-3">Futurs ingénieurs </h3>
                                <p class="feature-text">Bouygues Energies & Services parraine les futurs ingénieurs ESIGELEC
                                </p>
                                </a><a href="#read" class="read mt-4 d-block"> En savoir plus</a>
                            </div>
                        </div>
                    
                </div>
                <div class="col-lg-4 col-md-6 feature-grid">
                    <div class="feature-body service2"><a href="#url">
                            <div class="feature-img">
                                <span class="fa fa-wrench icon-fea" aria-hidden="true"></span>
                            </div>
                            </a><div class="feature-info mt-4"><a href="#url">
                                <h3 class="feature-titel mb-3">Forum</h3>
                                <p class="feature-text">Forum Normand Entreprises-Etudiants
                                </p>
                                </a><a href="#read" class="read mt-4 d-block"> En savoir plus</a>
                            </div>
                        </div>
                    
                </div>
                <div class="col-lg-4 col-md-6 feature-grid">
                   <div class="feature-body service3"><a href="#url">
                            <div class="feature-img">
                                <span class="fa fa-flask" aria-hidden="true"></span>
                            </div>
                            </a><div class="feature-info mt-4"><a href="#url">
                                <h3 class="feature-titel mb-3">Bourse de l'alternance</h3>
                                <p class="feature-text">Lorem ipsum dolor sit amet elit et. Debitis nam, minima iste
                                    ipsum.
                                </p>
                                </a><a href="#read" class="read mt-4 d-block"> En savoir plus</a>
                            </div>
                        </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>
    <section class="customers py-md-4 py-3">
      <div class="container">
      <div class="owl-carousel owl-theme" id="customer">
        <div class="item"><img src="assets/images/logo2.png"></div>
        <div class="item"><img src="assets/images/logo3.jpg"></div>
        <div class="item"><img src="assets/images/logo4.jpg"></div>
        <div class="item"><img src="assets/images/logo5.jpg"></div>
      </div>
    </div>
    </section>
<footer class="footer pt-5">
          <div class="container py-5">
            <div class="row footer-top-29">
              <div class="col-lg-4 col-md-6 col-sm-7 footer-list-29 footer-1 pr-lg-5">
                <div class="footer-logo mb-4">
                  <a class="navbar-brand" href="index.html">
                    <img style="height: 70px" src="assets/images/footer-logo.png"></a>
                </div>
                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas porttitor congue massa. Fusce posuere, magna sed pulvinar ultricies, purus lectus malesuada libero, sit amet commodo magna eros quis urna.</p>
                <div class="main-social-footer-29 mt-4">
                  <a href="#facebook" class="facebook"><span class="fab fa-facebook-f"></span></a>
                  <a href="#twitter" class="twitter"><span class="fab fa-twitter"></span></a>
                  <a href="#instagram" class="instagram"><span class="fab fa-instagram"></span></a>
                  <a href="#linkedin" class="linkedin"><span class="fab fa-linkedin-in"></span></a>
                </div>
              </div>
              <div class="col-lg-2 col-md-6 col-sm-5 col-6 footer-list-29 footer-2 mt-sm-0 mt-5">

                <ul>
                  <h6 class="footer-title-29">Navigation</h6>
                  <li><a href="about.html">Plan du site</a></li>
                  <li><a href="blog.html"> Mentions légales</a></li>
                  <li><a href="contact.html">Presse</a></li>
                  <li><a href="components.html"> Marchés publics</a></li>
                </ul>
              </div>
              <div class="col-lg-2 col-md-6 col-sm-5 col-6 footer-list-29 footer-3 mt-lg-0 mt-5">
                <h6 class="footer-title-29">Pages</h6>
                <ul>
                  <li><a href="#">Acceuil</a></li>
                  <li><a href="#marketplace">Etudiant</a></li>
                  <li><a href="#experts">Admin</a></li>
                </ul>

              </div>
              <div class="col-lg-4 col-md-6 col-sm-7 footer-list-29 footer-4 mt-lg-0 mt-5">
                <h6 class="footer-title-29">Contact Info </h6>
                <p>Address : Technopôle du Madrillet Avenue Galilée - BP 10024 76801 Saint-Etienne du Rouvray Cedex</p>
                <p class="mt-2">Tél : (+33) 02 32 91 58 58</p>
              </div>
            </div>
          </div>
        <!-- copyright -->
        <section class="w3l-copyright text-center">
          <div class="container">
            <p class="copy-footer-29">Copyright &copy; Gestion des résultats des étudiants <?php echo date('Y');?></p>
          </div>
      </section>
      </footer>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
      <script src="assets/js/owl.carousel.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

      <script src="assets/js/main.js"></script>
   </body>
</html>