<?php
require "./dbutils.inc.php";
//Récupérer l'id du post sélectionné
$idGet = filter_input(INPUT_GET, 'identifiant', FILTER_SANITIZE_STRING);

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
            //Si problème, ne pas autoriser supprimer dans base de donnée            
            $AutorisationSupPost = false;
        }   
        else {
            //Ajouter dans une table des idMedia à supprimer
            $tableIdASup[] = $unMediaASup['idMedia'];
        }
    }
    
    //Si la table des idMedia à supprimer n'est pas vide, commencer suppression dans base de donnée
    if(!empty($tableIdASup))
    {
        transactionDeletePostMedias($idGet, $tableIdASup, $AutorisationSupPost);
    }   
}

header('Location: index.php');

?>