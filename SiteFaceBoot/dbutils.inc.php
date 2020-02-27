<?php

require "./dbconnector.inc.php";
$rollBackAutorise;

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
            $rollBackAutorise = true;
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
            $rollBackAutorise = true;
          }
          // return (isset($answer["iduser"]) ? $answer["iduser"] : False);
  
          return $answer;
  }

 //Faire une transaction
 function transactionInsert($tableMediaParam, $textParam)
 {
   //booleen pour autoriser un rollback si detection d'une erreur PDO dans les fonctions d'insertion
   $rollBackAutorise = false;

   //Singleton PDO
   static $ps = null;
   if ($ps == null) 
   {
     $ps = m152();
   }

   //commencer la transaction
   $ps->beginTransaction();

   //Insertion dans post
   insertionPost($textParam);

   //Recuperer l'id du dernier post inséré
   $idPost = getPostId();

   //Parcourir les différentes données des medias recues pour les insérer
   if(isset($tableMediaParam[0]))
   {
      foreach ($tableMediaParam as $media) 
      {
        insertionMedia($media[0],$media[1],  $idPost['Last_id']);
      }
   }

   //Faire un rollback s'il y a erreur, sinon commit les insertions
   if($rollBackAutorise)
   {
     $ps->rollBack();
   }
   else 
   {
     $ps->commit();
   }
 }

 //Faire une transaction
 function transactionDelete($idParam)
 {
   //booleen pour autoriser un rollback si detection d'une erreur PDO dans les fonctions d'insertion
   $rollBackAutorise = false;

   //Singleton PDO
   static $ps = null;
   if ($ps == null) 
   {
     $ps = m152();
   }

   //commencer la transaction
   $ps->beginTransaction();

   //Suppression du post et ses médias
   delMediaWithId($idParam);
   delPostWithId($idParam);

   //Faire un rollback s'il y a erreur, sinon commit les insertions
   if($rollBackAutorise)
   {
     $ps->rollBack();
   }
   else 
   {
     $ps->commit();
   }
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

  function getAllPost()
  {
    static $ps = null;
    $sql = "SELECT * FROM post ORDER BY creationDate DESC LIMIT 30;";
  
    if ($ps == null) {
      $ps = m152()->prepare($sql);
    }
    $answer = false;
    try {
  
      if ($ps->execute())
        $answer = $ps->fetchall(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
    // return (isset($answer["iduser"]) ? $answer["iduser"] : False);
  
    return $answer;
  }

  function getMediaWithId($idParam)
  {
    static $ps = null;
    $sql = "SELECT * FROM media WHERE idPost=:idSelect LIMIT 5;";
  
    if ($ps == null) {
      $ps = m152()->prepare($sql);
    }
    $answer = false;
    try {
      $ps->bindParam(':idSelect', $idParam, PDO::PARAM_INT);
  
      if ($ps->execute())
        $answer = $ps->fetchall(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
    // return (isset($answer["iduser"]) ? $answer["iduser"] : False);
  
    return $answer;
  }

  function delMediaWithId($idParam)
  {
    static $ps = null;
    $sql = "DELETE FROM media WHERE idPost=:idSelect;";
  
    if ($ps == null) {
      $ps = m152()->prepare($sql);
    }
    $answer = false;
    try {
      $ps->bindParam(':idSelect', $idParam, PDO::PARAM_INT);
  
      if ($ps->execute())
      $answer = true;
    } catch (PDOException $e) {
      echo $e->getMessage();
      $rollBackAutorise = true;
    }
    // return (isset($answer["iduser"]) ? $answer["iduser"] : False);
  
    return $answer;
  }

  function delPostWithId($idParam)
  {
    static $ps = null;
    $sql = "DELETE FROM post WHERE idPost=:idSelect;";
  
    if ($ps == null) {
      $ps = m152()->prepare($sql);
    }
    $answer = false;
    try {
      $ps->bindParam(':idSelect', $idParam, PDO::PARAM_INT);
  
      if ($ps->execute())
      $answer = true;
    } catch (PDOException $e) {
      echo $e->getMessage();
      $rollBackAutorise = true;
    }
    // return (isset($answer["iduser"]) ? $answer["iduser"] : False);
  
    return $answer;
  }

  function affichagePost($cote)
  {  
    $formatMessage = "<div class=\"panel panel-default\">
    <div class=\"panel-heading\"><h4>Message</h4></div>
    %s
      <div class=\"panel-body\">
        <p>
          %s  
        </p>    
        <p>
          <a href=\"modification.php?identifiant=%d\"><img src=\"ressource/iconModif.png\" width=\"32px\" height=\"38px\"></a>
          <a href=\"suppression.php?identifiant=%d\"><img src=\"ressource/iconSupp.png\" width=\"28px\" height=\"28px\"></a>
        </p>                   
      </div>
    </div>";

    $message = "";
    
    foreach(getAllPost() as $post)
    {
      $mediaMessage = "";
      
      $permisAffiche = false; 

      if($cote == "droite")
      {
        if($post['idPost']%2 != 0)
        {
          $permisAffiche = true;
        }
      }
      if($cote == "gauche")
      {
        if($post['idPost']%2 == 0)
        {
          $permisAffiche = true;
        }
      }

      if($permisAffiche)
      {     
        foreach(getMediaWithId($post['idPost']) as $media)
        {                   
          if(stristr($media['typeMedia'], 'image'))
          {
            $cheminImage = $media['nomMedia'];
            $formatImage = "<img src=\"%s\" alt=\"Photo de grow\" width=\"100%%\"/>";
            $mediaMessage .= sprintf($formatImage, $cheminImage);
          }
          if(stristr($media['typeMedia'], 'video'))
          {
            $cheminVideo = $media['nomMedia'];
            $typeVideo = $media['typeMedia'];
            $formatVideo = "  <video width=\"100%%\" height=\"300\" autoplay muted loop>
                                <source src=\"%s\" type=\"%s\">
                              </video>";
            $mediaMessage .= sprintf($formatVideo, $cheminVideo, $typeVideo);
          }
          if(stristr($media['typeMedia'], 'audio'))
          {
            $cheminAudio = $media['nomMedia'];
            $typeAudio = $media['typeMedia'];
            $formatAudio = "  <audio controls>
                                <source src=\"%s\" type=\"%s\">
                              </audio>";
            $mediaMessage .= sprintf($formatAudio, $cheminAudio, $typeAudio);
          }                      
        }  
        
        $textMessage = $post['commentaire'];
        $idMessage = $post['idPost'];

        $message .= sprintf($formatMessage,$mediaMessage,$textMessage, $idMessage, $idMessage); 
      }                           
    }

    echo $message;    
  }


  