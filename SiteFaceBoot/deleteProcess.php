<?php
require "./dbutils.inc.php";

$idGet = filter_input(INPUT_GET, 'identifiant', FILTER_SANITIZE_STRING);

//Depuis la page index.php
//Supprimer le post et tous ses médias
if(!empty($idGet))
{
    $mediasASup = getMediaWithIdPost($idGet);
    $autorisationSupDansBase = true;

    foreach ($mediasASup as $unMediaASup) 
    {
        if(unlink($unMediaASup['nomMedia']) == false)
        {
            $autorisationSupDansBase = false;
        }   
    }
   
    if($autorisationSupDansBase)
    {
        transactionDeletePostMedias($idGet);
    }   
}

header('Location: index.php');

?>