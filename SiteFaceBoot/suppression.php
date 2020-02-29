<?php
require "./dbutils.inc.php";

$idGet = filter_input(INPUT_GET, 'identifiant', FILTER_SANITIZE_STRING);
$pageRedirection = filter_input(INPUT_POST, 'pageRedirect', FILTER_SANITIZE_STRING);
$tableMediaChoisiASup = $_POST['media'];

//Depuis la page modification.php
//Supprimer les medias selectionnés d'un post choisi
if(!empty($tableMediaChoisiASup))
{
    $autorisationSupDansBase = true;
    $tableIdASup = array();

    foreach($tableMediaChoisiASup as $idASup)
    {
        $unMediaASup = getMediaWithIdMedia($idASup);
        if(unlink($unMediaASup[0]['nomMedia']) == false)
        {
            $autorisationSupDansBase = false;
        }
        else {
            $tableIdASup[] = $idASup;
        }
    }

    if($autorisationSupDansBase)
    {
        transactionDeleteMedias($tableIdASup);
    }
}

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

//Redirection
if(!empty($pageRedirection))
{
    header('Location: ' . $pageRedirection);
}
else 
{
    header('Location: index.php');
}
?>