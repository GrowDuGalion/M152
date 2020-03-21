<?php
require "./dbutils.inc.php";
//Récupérer l'id du post sélectionné
$idModif = filter_input(INPUT_GET, 'identifiant', FILTER_VALIDATE_INT);

//Interdiction de modification sans $idModif
if(empty($idModif))
{
  header("Location: index.php");
}

//Interdiction de modification sans $idModif existant dans base
if(empty(getPostWithId($idModif)))
{
  header("Location: index.php");
}

//Récupérer les médias du post sélectionné pour les afficher plus tard
$mediasAAffich = getMediaWithIdPost($idModif);
//Récupérer le texte du post sélectionné pour l'afficher plus tard
$textAAffich = getPostWithId($idModif)[0]["commentaire"];

//Créer l'addresse de redirection pour updateProcess.php
$pageNom = "modification.php?identifiant=" . $idModif;

//Démarrer une session
if (session_status() == PHP_SESSION_NONE) 
{
     session_start();
}
$message = "Aucune note";
//Récupérer les message de succès et échecs de la session si elle existe
if(isset($_SESSION["noteModif"]))
{
  $message = $_SESSION["noteModif"];
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>Modification</title>
		<meta name="generator" content="Bootply" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="css/styles.css" rel="stylesheet">
	</head>
	<body>
<div class="wrapper">
    <div class="box">
        <div class="row row-offcanvas row-offcanvas-left">
                               
          
          <!-- main right col -->
          <div class="column col-sm-12 col-xs-12" id="main">
              
              <!-- top nav -->
              <div class="navbar navbar-blue navbar-static-top">  
                  <div class="navbar-header">
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                      <span class="sr-only">Toggle</span>
                      <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand logo">b</a>
                  </div>
                  <nav class="collapse navbar-collapse" role="navigation">
                  <ul class="nav navbar-nav">
                    <li>
                      <a href="#"><span class="badge">badge</span></a>
                    </li>
                  </ul>
                  <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-user"></i></a>
                      <ul class="dropdown-menu">
                        <li><a href="">More</a></li>
                        <li><a href="">More</a></li>
                        <li><a href="">More</a></li>
                        <li><a href="">More</a></li>
                        <li><a href="">More</a></li>
                      </ul>
                    </li>
                  </ul>
                  </nav>
              </div>
              <!-- /top nav -->
            
              <div class="padding">
                  <div class="full col-sm-9">
                    
                      <!-- content -->                      
                      <div class="row">
                        
                        <!-- main col left --> 
                          <div class="col-sm-5">                                                      
                                                        
                              <div class="panel panel-default">
                                  <div class="panel-heading"><h4>What Is Bootstrap?</h4></div>
                                  <div class="panel-body">
                                      Bootstrap is front end frameworkto build custom web applications that are fast, responsive &amp; intuitive. It consist of CSS and HTML for typography, forms, buttons, tables, grids, and navigation along with custom-built jQuery plug-ins and support for responsive layouts. With dozens of reusable components for navigation, pagination, labels, alerts etc..                          </div>
                              </div>
                              
                              <div class="well"> 
                                  <form class="form-horizontal" method="POST" role="form" action="updateProcess.php" enctype="multipart/form-data">
                                      <h4>Changer le texte</h4>
                                      <div class="form-group" style="padding:14px;">
                                      <textarea class="form-control" placeholder="Description" name="message"><?php echo $textAAffich; ?></textarea>
                                      </div>
                                      <input class="btn btn-primary pull-right" type="submit" name="btnModifText" value="Changer" />
                                      <ul class="list-inline">
                                      <li><input type="hidden" name="pageRedirect" value="<?php echo $pageNom; ?>"><input type="hidden" name="idModif" value="<?php echo $idModif; ?>"></li>
                                      </ul>
                                  </form>
                              </div>   

                              <div class="panel panel-default">
                                  <div class="panel-heading"><h4>Terminer la modification</h4></div>
                                  <div class="panel-body">
                                  <a class="btn btn-primary" href="index.php">Fin de la modification</a> 
                                  </div>
                              </div>                    		
                          
                          </div>
                        
                        <!-- main col right -->
                            <div class="col-sm-7">
                                                              
                              <div class="panel panel-default">                                  
                                  <div class="panel-body">
                                  <div class="clearfix"></div>
                                  <h2>MODIFICATION</h2>
                                  </div>
                              </div>    

                              <div class="well"> 
                                  <form class="form-horizontal" method="POST" role="form" action="updateProcess.php" enctype="multipart/form-data">
                                      <h4>Ajouter un média</h4>
                                      <input class="btn btn-primary pull-right" type="submit" name="btnAjoutMedia" value="Ajouter" />
                                      <ul class="list-inline">
                                          <li><input type="file" name="import[]" accept="image/png, image/jpeg, image/jpg, image/gif, video/mp4, video/webm, video/ogg, audio/ogg, audio/mpeg, audio/mp3" multiple></li>
                                      </ul>
                                      <input type="hidden" name="pageRedirect" value="<?php echo $pageNom; ?>">
                                      <input type="hidden" name="idModif" value="<?php echo $idModif; ?>">
                                  </form>
                              </div>  
                              
                              <div class="panel panel-default">
                                  <div class="panel-heading"> <h4>Supprimer un ou des médias</h4></div>
                                  <div class="panel-body">
                                    <form class="form-horizontal" method="POST" role="form" action="updateProcess.php">
                                      <?php affichageMediasModif($idModif); ?>
                                      <input type="hidden" name="pageRedirect" value="<?php echo $pageNom; ?>">
                                      <input type="hidden" name="idModif" value="<?php echo $idModif; ?>">
                                    </form>
                                  </div>
                              </div>                                                                                                         

                              <div class="panel panel-default">
                                  <div class="panel-heading"><h4>Notes</h4></div>
                                  <div class="panel-body">
                                    <?php echo $message;?>                  
                                  </div>
                              </div>
                              
                            </div>
                      </div><!--/row-->
                                       
                  </div><!-- /col-9 -->
              </div><!-- /padding -->
          </div>
          <!-- /main -->
          
        </div>
    </div>
</div>



	<!-- script references -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/scripts.js"></script>
	</body>
</html>