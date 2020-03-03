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
    $autorisationSupDansBase = true;

    //Supprimer les fichers médias du post choisi
    foreach ($mediasASup as $unMediaASup) 
    {
        if(unlink($unMediaASup['nomMedia']) == false)
        {
            //Si problème, ne pas autoriser supprimer dans base de donnée
            $autorisationSupDansBase = false;
        }   
    }
    
    //Si autorisé, supprimer les médias du post choisi dans base de donnée
    if($autorisationSupDansBase)
    {
        transactionDeletePostMedias($idGet);
    }   
}

header('Location: index.php');

?>