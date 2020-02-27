<?php
require "./dbutils.inc.php";

$idGet = filter_input(INPUT_GET, 'identifiant', FILTER_SANITIZE_STRING);

if($idGet!=null && $idGet!=false)
{
    $mediasASup = getMediaWithId($idGet);
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
        transactionDelete($idGet);
    }   
}

header('Location: index.php');
?>