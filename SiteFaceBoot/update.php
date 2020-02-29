<?php
require "./dbutils.inc.php";

$pageRedirection = filter_input(INPUT_POST, 'pageRedirect', FILTER_SANITIZE_STRING);
$idModif = filter_input(INPUT_POST, 'idModif', FILTER_VALIDATE_INT);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);


if(!empty($message) && !empty($idModif))
{
    updPostWithIdPost($message, $idModif);
}


if(!empty($pageRedirection))
{
    header('Location: ' . $pageRedirection);
}

?>