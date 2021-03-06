<?php
require "./dbutils.inc.php";

//Pour gérer l'appuie d'un bouton
$btnModifText = filter_input(INPUT_POST, 'btnModifText', FILTER_SANITIZE_STRING);
$btnAjoutMedia = filter_input(INPUT_POST, 'btnAjoutMedia', FILTER_SANITIZE_STRING);
$btnSupMedia = filter_input(INPUT_POST, 'btnSupMedia', FILTER_SANITIZE_STRING);

$pageRedirection = filter_input(INPUT_POST, 'pageRedirect', FILTER_SANITIZE_STRING);
$idModif = filter_input(INPUT_POST, 'idModif', FILTER_VALIDATE_INT);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
$tableValeursMediaInsert = array();

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

if (session_status() == PHP_SESSION_NONE) 
{
     session_start();
}

if(!isset($_SESSION['noteModif']))
{
     $_SESSION['noteModif'] = "";
}

//Changer le message du post
//Activer la modification à l'appuie du bouton changer
if(!empty($btnModifText))
{
     //Contrôler que le message ne soit pas vide
     if(!empty($message))
     {
          if(updPostWithIdPost($message, $idModif))
          {
               $_SESSION['noteModif'] .= 'Texte changé pour "' . $message . '" dans base de donnée ! </br>';
          }
          else 
          {
               $_SESSION['noteModif'] .= "Echec changement texte dans base de donnée ! </br>";
          }
     }
     else 
     {
          $_SESSION['noteModif'] .= "Aucun changement parce que votre texte est vide ! </br>";
     }   
}

//Supprimer les medias selectionnés d'un post choisi
//Activer la suppression à l'appuie du bouton supprimer
if(!empty($btnSupMedia))
{
     //vérifier la présence de médias cochés
     if(!empty($_POST['media']))
     {
          $tableIdASup = array();
          $tableNomASup = array();
     
          //Récupérer les noms et id des médias à supprimer
          foreach ($_POST['media'] as $idASup) 
          {
               //Récupérer le nom du média avec l'id dans value du checkbox
               $unMediaASup = getMediaWithIdMedia($idASup);
               $tableIdASup[] = $unMediaASup[0]['idMedia'];
               $tableNomASup[] = $unMediaASup[0]['nomMedia'];
          }
     
          //Vérifier qu'il y a des médias à supprimer
          if(!empty($tableIdASup) && !empty($tableNomASup))
          {         
               //Faire une transaction de suppression des médias sélectionnés dans la base de donnée
               if(transactionDeleteMedias($tableIdASup))
               {
                    $_SESSION['noteModif'] .= "Succès suppression dans base de donnée ! </br>";
     
                    //Parcourir les noms des médias à supprimer
                    foreach($tableNomASup as $nomASup)
                    {
                         //Supprimer les fichiers liés au média choisi
                         if(unlink($nomASup) == false)
                         {
                              $_SESSION['noteModif'] .= "Echec suppression fichier " . substr($nomASup,21) . " ! </br>";
                         }
                         else 
                         {
                              $_SESSION['noteModif'] .= "Succès suppression fichier " . substr($nomASup,21) . " ! </br>";
                         }
                    }
               }
               else 
               {
                    $_SESSION['noteModif'] .= "Echec suppression dans base de donnée ! </br>";
               }
          }  
          else 
          {
               $_SESSION['noteModif'] .= "Aucun média à supprimer ! </br>";
          }      
     }
     else
     {
          $_SESSION['noteModif'] .= "Vous n'avez coché aucun média ! </br>";        
     }
}

//Ajouter un média au post choisi
//Activer l'ajout à l'appuie du bouton ajouter
if(!empty($btnAjoutMedia))
{
     if(isset($_FILES['import']))
     {
          //Contrôler la présence de l'idModif pour lier les médias au bon post
          if($_FILES['import']['name'][0] != "")
          { 
               $countfiles = count($_FILES['import']['name']);
     
               //Controler la taille des fichiers
               for($l=0;$l<$countfiles;$l++)
               {
                    $taille_maxi = 30000000;
                    $taille = $_FILES['import']['size'][$l];
     
                    if($taille> $taille_maxi) //Si taille dépasse la taille maximum autorisé
                    {
                         $erreur = "Vous devez choisir des fichiers moins lourds. <br/>";
                         $_SESSION['noteModif'] .= "Fichier moins lourds svp ! <br/>";
                    }
               }
     
               //Controller le type des fichiers
               for($j=0;$j<$countfiles;$j++)
               {
                    $extensions = array('image/png', 'image/gif', 'image/jpg', 'image/jpeg', 'video/mp4', 'video/webm', 'video/ogg', 'audio/ogg', 'audio/mp3', 'audio/mpeg');
                    $extension = $_FILES['import']['type'][$j]; 
                    if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
                    {
                         $erreur = 'Vous devez uploader un fichier de type png, gif, jpg, video, audio ! <br/>';
                         $_SESSION['noteModif'] .= "Seulement fichier type png, gif, jpg, video, audio ! <br/>";
                    }
               }
     
               //Parcourir les fichiers à uploader
               for($i=0;$i<$countfiles;$i++)
               {          
                    $dossier = 'upload/';
                    $fichier = basename($_FILES['import']['name'][$i]);
                    $extension = $_FILES['import']['type'][$i];          
     
                    if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
                    {
                         //On formate le nom du fichier ici...
                         $fichier = strtr($fichier, 
                              'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
                              'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                         $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
                         
                         //Rendre unique le nom du fichier
                         $chemin =  $dossier . uniqid() . '_' . $fichier;
     
                         //Uploader les fichiers dans un dossier
                         if(move_uploaded_file($_FILES['import']['tmp_name'][$i], $chemin)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
                         {
                              echo "Succes de l'upload ! <br/>";
                              $_SESSION['noteModif'] .= "Succès upload fichier " . $fichier . " ! </br>";
                              //Faire un tableau des donnees d'un media pour insérer plus tard
                              //Seule les medias insérer avec succès pourront etre insérer dans la base de donnée
                              $tableValeursMediaInsert[] = array($extension, $chemin);
                              
                         }
                         else //Sinon (la fonction renvoie FALSE).
                         {
                              echo 'Echec de l\'upload ! <br/>';
                              $_SESSION['noteModif'] .= "Echec upload fichier " . $fichier . " ! </br>";
                         }
                    }
                    else
                    {
                         //Afficher l'erreur
                         echo $erreur;
                    }
               }    
               
               if(isset($tableValeursMediaInsert[0]))
               {
                    echo "Demarrage de l'insertion ! <br/>";
     
                    //Démarrer la transaction pour insérer dans la base de donnée
                    if(transactionInsertMedias($tableValeursMediaInsert,$idModif))
                    {
                         $_SESSION['noteModif'] .= "Succès insertion média(s) dans base de donnée ! </br>";
                    }  
                    else {
                         $_SESSION['noteModif'] .= "Echec insertion média(s) dans base de donnée ! </br>";
                    }
               }
          }
          else 
          {
               echo 'Rien à uploader! <br/>';
               $_SESSION['noteModif'] .= "Vous n'avez rien sélectionné à uploader ! </br>";
          }
     }
}

//Transaction pour modifier les timestamp du post et ses medias
transactionUpdateTimePostMedias($idModif);

//Redirection
if(!empty($pageRedirection))
{
     header('Location: ' . $pageRedirection);
}
else {
     header('Location: index.php');    
}

?>