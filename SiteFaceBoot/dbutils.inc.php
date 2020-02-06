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
  //Insertion dans la table media
  function insertionMedia($typeParam, $nomParam, $idParam)
  {
          static $ps = null;
          $sql = "INSERT INTO media (typeMedia, nomMedia, idPost) VALUES (:typeInsert, :nomInsert, :idInsert);";
  
          if ($ps == null) {
            $ps = m152()->prepare($sql);
          }
          $answer = false;
          try {
            $ps->bindParam(':typeInsert', $typeParam, PDO::PARAM_STR);
            $ps->bindParam(':nomInsert', $nomParam, PDO::PARAM_STR); 
            $ps->bindParam(':idInsert', $idParam, PDO::PARAM_INT);             
  
            if ($ps->execute())
              $answer = true;
          } catch (PDOException $e) {
            echo $e->getMessage();
          }
          // return (isset($answer["iduser"]) ? $answer["iduser"] : False);
  
          return $answer;
  }


  //Insertion dans la table commentaire
  function insertionPost($commentParam)
  {
          static $ps = null;
          $sql = "INSERT INTO post (commentaire) VALUES (:commentInsert);";
  
          if ($ps == null) {
            $ps = m152()->prepare($sql);
          }
          $answer = false;
          try {
            $ps->bindParam(':commentInsert', $commentParam, PDO::PARAM_STR);     
  
            if ($ps->execute())
              $answer = true;
          } catch (PDOException $e) {
            echo $e->getMessage();
          }
          // return (isset($answer["iduser"]) ? $answer["iduser"] : False);
  
          return $answer;
  }

  function getPostId() 
  {
    static $ps = null;
    $sql = "SELECT LAST_INSERT_ID() AS Last_id FROM post  LIMIT 1;";
  
    if ($ps == null) {
      $ps = m152()->prepare($sql);
    }
    $answer = false;
    try {
  
      if ($ps->execute())
        $answer = $ps->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
    // return (isset($answer["iduser"]) ? $answer["iduser"] : False);
  
    return $answer;
  }

  