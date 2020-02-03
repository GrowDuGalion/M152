<?php
require "./dbutils.inc.php";

if(isset($_FILES['img']))
{ 
     $countfiles = count($_FILES['img']['name']);
     $taile_maxi_totale = 70000000;
     $tailleTotale = 0;

     //Controler la taille totale d'un post
     for($k=0;$k<$countfiles;$k++)
     {
          $tailleTotale = $tailleTotale + filesize($_FILES['img']['tmp_name'][$i]);
          if($taille>$taile_maxi_totale)
          {
               $erreur = 'Vous devez faire une sélection de fichiers mois lourd que 70 Mo';
          }
     }

     //Parcourir les fichiers à uploader
     for($i=0;$i<$countfiles;$i++)
     {
          $dossier = 'upload/';
          $fichier = basename($_FILES['img']['name'][$i]);
          $taille_maxi = 3000000;
          $taille = filesize($_FILES['img']['tmp_name'][$i]);
          $extensions = array('.png', '.gif', '.jpg', '.jpeg');
          $extension = strrchr($_FILES['img']['name'][$i], '.'); 
          //Début des vérifications de sécurité...
          if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
          {
               $erreur = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg';
          }
          if($taille>$taille_maxi) //Si la taille du fichier est trop grande
          {
               $erreur = 'Le fichier est trop gros...';
          }
          if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
          {
               //On formate le nom du fichier ici...
               $fichier = strtr($fichier, 
                    'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
                    'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
               $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
               
               //Rendre unique le nom du fichier
               $chemin =  $dossier . time() . '_' . $fichier;

               if(move_uploaded_file($_FILES['img']['tmp_name'][$i], $chemin)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
               {
                    //Insérer dans la base de donnée
                    insertionMedia($extension, $chemin);

                    //Depuis le serveur, supprimer les fichiers aux extensions non désirables
                    SupTypeNonAutorise();

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