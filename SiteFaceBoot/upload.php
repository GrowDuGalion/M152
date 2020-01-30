<?php
require "./dbutils.inc.php";

if(isset($_FILES['img']))
{ 
    $dossier = 'upload/';
    $fichier = basename($_FILES['img']['name']);
    $taille_maxi = 100000;
    $taille = filesize($_FILES['img']['tmp_name']);
    $extensions = array('.png', '.gif', '.jpg', '.jpeg');
    $extension = strrchr($_FILES['img']['name'], '.'); 
    //Début des vérifications de sécurité...
    if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
    {
         $erreur = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg, txt ou doc...';
    }
    if($taille>$taille_maxi)
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
        
         $chemin =  $dossier . time() .$fichier;

         if(move_uploaded_file($_FILES['img']['tmp_name'], $chemin)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
         {
            insertionMedia($_FILES['img']['type'], $chemin);

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
         echo $erreur;
    }
}
?>