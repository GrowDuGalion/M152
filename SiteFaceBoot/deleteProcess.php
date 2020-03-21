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

//Interdiction de modification sans $idModif
if(empty($idGet))
{
  header("Location: index.php");
}
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
    $tableNomASup = array();

    //Récupérer les noms et id des médias à supprimer
    foreach ($mediasASup as $unMediaASup) 
    {
        $tableIdASup[] = $unMediaASup['idMedia'];
        $tableNomASup[] = $unMediaASup['nomMedia'];
    }

    //Déclencher la transaction que s'il y a des médias à supprimmer
    if(!empty($tableIdASup) && !empty($tableNomASup))
    {
        if(transactionDeletePostMedias($idGet, $tableIdASup))
        {
            $_SESSION['msgSup'] .= "Succes de la suppression des médias de la base de donnée! </br>";    
            
            //Supprimer les fichers médias du post choisi après transaction de suppression réussie
            foreach ($tableNomASup as $unMediaASup) 
            {
                if(unlink($unMediaASup) == false)
                {         
                    $_SESSION['msgSup'] .= "Echec de la suppression du fichier " . substr($unMediaASup,21). " ! </br>";
                }   
                else {                
                    $_SESSION['msgSup'] .= "Succès de la suppression du fichier " . substr($unMediaASup,21). " ! </br>";
                }
            }                  
        }
        else 
        {
            $_SESSION['msgSup'] .= "Echec de la suppression des médias et/ou du post de la base de donnée ! </br>";
            $AutorisationSupPost = false;
        }
    }
    
    //Supprimer le post qui n'a pas de média ou transaction de suppression des médias a fonctionnée
    if($AutorisationSupPost)
    {        
        $_SESSION['msgSup'] .= "Succès de la suppression du post de la base de donnée ! </br>";
        delPostWithIdPost($idGet);
    }
    else {
        $_SESSION['msgSup'] .= "Annulation de la suppression du post ! </br>";
    }
 
}

header('Location: index.php');

?>