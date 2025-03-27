<div class="left-sidebar box-shadow 
" style="
    background-color: #172541; ">
                        <div class="sidebar-content">
                            <div class="user-info">
                                <img src="assets/images/avatar.jfif" alt="Profile" class="img-circle profile-img" width="40px">
                                <p style="color: white;"><?php echo $_SESSION['alogin']; ?></p>
                            </div>
                            <!-- /.user-info -->

                            <div class="sidebar-nav">
                                <ul class="side-nav color-gray">
                                    <li class="nav-header">
                                        <span class="">Catégories</span>
                                    </li>
                                    <li>
                                        <a href="dashboard.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span> </a>
                                     
                                    </li>

                                    <li class="nav-header">
                                        <span class="">Apparition</span>
                                    </li>

   <li class="has-children">
                                        <a href="#"><i class="fa fa-users"></i> <span>Étudiants</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li><a href="add-students.php"><i class="fa fa-plus"></i> <span>Ajouter Étudiants</span></a></li>
                                            <li><a href="manage-students.php"><i class="fa fa-bars"></i> <span>Gérer Les Étudiants</span></a></li>
                                           
                                        </ul>
                                    </li>
<li class="has-children">
 
                                        <a href="#"><i class="fa fa-file-o"></i> <span>Résultats</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li><a href="add-result.php"><i class="fa fa-plus"></i> <span>Ajouter Résultats</span></a></li>
                                            <li><a href="manage-results.php"><i class="fa fa-bars"></i> <span>Gérer Les Résultats</span></a></li>
                                           
                                        </ul>        </li>
<?php if ($_SESSION["is_admin"]) { ?>
                                    <li class="has-children">
                                        <a href="#"><i class="fa fa-file-text"></i> <span>Les Classes</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li><a href="create-class.php"><i class="fa fa-plus"></i> <span>Ajouter Classe</span></a></li>
                                            <li><a href="manage-classes.php"><i class="fa fa-bars"></i> <span>Gérer Les Classes</span></a></li>
                                           
                                        </ul>
                                    </li>
  <li class="has-children">
                                        <a href="#"><i class="fa fa-book"></i> <span>Cours</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li><a href="create-subject.php"><i class="fa fa-plus"></i> <span>Ajouter Cours</span></a></li>
                                            <li><a href="manage-subjects.php"><i class="fa fa-bars"></i> <span>Gérer Les Cours</span></a></li>
                                           <li><a href="add-subjectcombination.php"><i class="fa fa-plus"></i> <span>Relier cours à l'année </span></a></li>
                                           <a href="manage-subjectcombination.php"><i class="fa fa-bars"></i> <span>Gérer les cours des années</span></a></li>
                                        </ul>
                                    </li>
<li class="has-children">
                                        <a href="#"><i class="fa fa-bell"></i> <span>Notices</span> <i class="fa fa-angle-right arrow"></i></a>
                                        <ul class="child-nav">
                                            <li><a href="add-notice.php"><i class="fa fa-plus"></i> <span>Ajouter Notifications</span></a></li>
                                            <li><a href="manage-notices.php"><i class="fa fa-bars"></i> <span>Gérer les Notifications</span></a></li>
                                           
                                        </ul>        </li>

<?php } ?>

                                        <li><a href="change-password.php"><i class="fa fa-key"></i> <span> Changer mot de passe</span></a></li>

                                           
                            
                            </div>
                            <!-- /.sidebar-nav -->
                        </div>
                        <!-- /.sidebar-content -->
                    </div>