<?php
require "./dbutils.inc.php";
//Récupérer l'id du post sélectionné
$idGet = filter_input(INPUT_GET, 'identifiant', FILTER_SANITIZE_STRING);

//Ouvrir une session
if (session_status() == PHP_SESSION_NONE) 
{
     session_start();
}

//Créer une variable session pour récupérer des messages d'erreur
$_SESSION['msgSup'] = "";


//Interdiction de suppression sans $idModif existant dans base
if(empty(getPostWithId($idGet)))
{
  header("Location: index.php");
}

//Supprimer le post et tous ses médias
if(!empty($idGet))
{
    $mediasASup = getMediaWithIdPost($idGet);
    $AutorisationSupPost = true;
    $tableIdASup = array();

    //Supprimer les fichers médias du post choisi
    foreach ($mediasASup as $unMediaASup) 
    {
        if(unlink($unMediaASup['nomMedia']) == false)
        {
            //Si problème, ne pas autoriser supprimer le post dans base de donnée            
            $AutorisationSupPost = false;
            $_SESSION['msgSup'] .= "Echec de la suppression du fichier " . substr($unMediaASup['nomMedia'],21). " ! </br>";
        }   
        else {
            //Ajouter dans une table des idMedia à supprimer
            $tableIdASup[] = $unMediaASup['idMedia'];
            $_SESSION['msgSup'] .= "Succes de la suppression du fichier " . substr($unMediaASup['nomMedia'],21). " ! </br>";
        }
    }    

    if(!$AutorisationSupPost)
    {
        $_SESSION['msgSup'] .= "Annulation de la suppression du post ! </br>";
    }

    //Si la table des idMedia à supprimer n'est pas vide, commencer suppression dans base de donnée
    if(!empty($tableIdASup))
    {
        if(transactionDeletePostMedias($idGet, $tableIdASup, $AutorisationSupPost))
        {
            $_SESSION['msgSup'] .= "Succes de la suppression des médias de la base de donnée cités dessus ! </br>";

            if($AutorisationSupPost)
            {
                $_SESSION['msgSup'] .= "Succès de la suppression du post de la base de donnée ! </br>";
            }           
        }
        else {
            $_SESSION['msgSup'] .= "Echec de la suppression des médias et/ou du post de la base de donnée ! </br>";
        }
    }   
}

header('Location: index.php');

?>