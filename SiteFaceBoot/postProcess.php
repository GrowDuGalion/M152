<?php
require "./dbutils.inc.php";

$MessagePost = filter_input(INPUT_POST, 'Message', FILTER_SANITIZE_STRING);
$tableValeursMediaInsert = array();

var_dump($_FILES['import']['type'][0]);

if(isset($_FILES['import']))
{
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
                    $erreur = " Vous devez choisir des fichiers moins lourds. <br/>";
               }
          }

          //Controller le type des fichiers
          for($j=0;$j<$countfiles;$j++)
          {
               $extensions = array('image/png', 'image/gif', 'image/jpg', 'image/jpeg', 'video/mp4', 'video/webm', 'video/ogg', 'audio/ogg', 'audio/mp3', 'audio/mpeg');
               $extension = $_FILES['import']['type'][$j]; 
               if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
               {
                    $erreur = ' Vous devez uploader un fichier de type png, gif, jpg, jpeg, video, audio. <br/>';
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
                         //Faire un tableau des donnees d'un media pour insérer plus tard
                         //Seule les medias insérer avec succès pourront etre insérer dans la base de donnée
                         $tableValeursMediaInsert[] = array($extension, $chemin);
                         
                    }
                    else //Sinon (la fonction renvoie FALSE).
                    {
                         echo 'Echec de l\'upload ! <br/>';
                    }
               }
               else
               {
                    //Afficher l'erreur
                    echo $erreur;
               }
          }      
     }
     else 
     {
          echo 'Rien à uploader! <br/>';
     }
}


if(isset($MessagePost) || isset($tableValeursMediaInsert[0]))
{
     echo "Demarrage de l'insertion ! <br/>";

     //Démarrer la transaction pour insérer dans la base de donnée
     transactionInsertPostMedias($tableValeursMediaInsert, $MessagePost);

     header("Location: index.php");
  
}
?>