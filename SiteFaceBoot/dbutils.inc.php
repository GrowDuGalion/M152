<?php

require "./dbconnector.inc.php";

/**
 * Connecteur de la base de données du M151.
 * Déclenche un die si la connexion n'est pas possible.
 * @staticvar PDO $dbc
 * @return \PDO
 */
function m152() {
  static $dbc = null;

  // Première visite de la fonction
  if ($dbc == null) {
    // Essaie le code ci-dessous
    try {
      $dbc = new PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, DBPWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_PERSISTENT => true));
    }
    // Si une exception est arrivée
    catch (Exception $e) {
      echo 'Erreur : ' . $e->getMessage() . '<br />';
      echo 'N° : ' . $e->getCode();
      // Quitte le script et meurt
      die('Could not connect to MySQL');
    }
  }
  // Pas d'erreur, retourne un connecteur
  return $dbc;
}

//Récuprérer tous les elements de la table tequipe
function getAll($limit) {
  static $ps = null;
  $sql = "SELECT * FROM foot.Tequipe LIMIT :limit;";

  if ($ps == null) {
    $ps = m152()->prepare($sql);
  }
  $answer = false;
  try {
    $ps->bindParam(':limit', $limit, PDO::PARAM_INT);

    if ($ps->execute())
      $answer = $ps->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
  // return (isset($answer["iduser"]) ? $answer["iduser"] : False);

  return $answer;
}

  //Insertion dans la table media
  function insertionMedia($typeParam, $nomParam)
  {
          static $ps = null;
          $sql = "INSERT INTO media (typeMedia, nomMedia) VALUES (:typeInsert, :nomInsert);";
  
          if ($ps == null) {
            $ps = m152()->prepare($sql);
          }
          $answer = false;
          try {
            $ps->bindParam(':typeInsert', $typeParam, PDO::PARAM_STR);
            $ps->bindParam(':nomInsert', $nomParam, PDO::PARAM_STR);           
  
            if ($ps->execute())
              $answer = true;
          } catch (PDOException $e) {
            echo $e->getMessage();
          }
          // return (isset($answer["iduser"]) ? $answer["iduser"] : False);
  
          return $answer;
  }

  //Supprimer les fichiers au type non autorise
  function SupTypeNonAutorise()
  {
          static $ps = null;
          $sql = "DELETE FROM media WHERE typeMedia != 'jpeg' AND typeMedia != 'jpg' AND typeMedia != 'png' AND typeMedia != 'gif';";
  
          if ($ps == null) {
            $ps = m152()->prepare($sql);
          }
          $answer = false;
          try {         
  
            if ($ps->execute())
              $answer = true;
          } catch (PDOException $e) {
            echo $e->getMessage();
          }
          // return (isset($answer["iduser"]) ? $answer["iduser"] : False);
  
          return $answer;
  }


  //Obtenir le nom d'un pays a partir de son code
  function getPays($codePays) {
    static $ps = null;
    $sql = "SELECT NomPays FROM tpays WHERE (CodePays = :codePaysRecu);";
  
  
    if ($ps == null) {
      $ps = m152()->prepare($sql);
    }
    $answer = false;
    try {
      $ps->bindParam(':codePaysRecu', $codePays, PDO::PARAM_STR);
  
      if ($ps->execute())
      $answer = $ps->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
    return $answer;
  }

  