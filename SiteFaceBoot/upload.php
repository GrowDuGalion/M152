<?php
require "./dbutils.inc.php";

$MessagePost = filter_input(INPUT_POST, 'Message', FILTER_SANITIZE_STRING);

if(isset($_FILES['img']))
{ 
     $countfiles = count($_FILES['img']['name']);
     $limitationMessagePost = true;
     $idPost = 0;
     
     var_dump($_FILES['img']['size']);
     var_dump($_FILES['img']['type']);

     //Controler la taille des fichiers
     for($l=0;$l<$countfiles;$l++)
     {
          $taille_maxi = 3000000;
          $taille = $_FILES['img']['size'][$l];

          if($taille> $taille_maxi) //Si taille dépasse la taille maximum autorisé
          {
               $erreur = "Vous devez choisir des fichiers moins lourds";
          }
     }

     //Controller le type des fichiers
     for($j=0;$j<$countfiles;$j++)
     {
          $extensions = array('image/png', 'image/gif', 'image/jpg', 'image/jpeg');
          $extension = $_FILES['img']['type'][$j]; 

          if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
          {
               $erreur = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg';
          }
     }

     //Parcourir les fichiers à uploader
     for($i=0;$i<$countfiles;$i++)
     {          
          $dossier = 'upload/';
          $fichier = basename($_FILES['img']['name'][$i]);
          $extension = $_FILES['img']['type'][$i]; 

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
               if(move_uploaded_file($_FILES['img']['tmp_name'][$i], $chemin)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
               {
                    //Insérer une seule fois le message dans table post
                    if($limitationMessagePost)
                    {                                                     
                         insertionPost($MessagePost);
                         
                         //Récupérer l'id du dernier post
                         $idPost = getPostId();

                         $limitationMessagePost = false;
                    }

                    //Insérer les médias avec leur nom et l'id du post correspondant
                    insertionMedia($extension, $chemin, $idPost['Last_id']);
                                       
                    echo 'Upload effectue avec succes !';
               }
               else //Sinon (la fonction renvoie FALSE).
               {
                    echo 'Echec de l\'upload !';
               }
          }
          else
          {
               //Afficher l'erreur
               echo $erreur;
          }
     }


   
}
?>