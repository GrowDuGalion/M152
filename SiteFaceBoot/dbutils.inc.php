<?php

require "./dbconnector.inc.php";
//Variable pour auoriser ou non le commit des transactions
$commitAutorise;

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

  //Faire une transaction pour un nouveau post avec ses medias
  function transactionInsertPostMedias($tableMediaParam, $textParam)
  {
    //booleen pour autoriser un rollback si detection d'une erreur PDO dans les fonctions d'insertion
    $commitAutorise = true;

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
    $idPost = getLastPostId();

    //Parcourir les différentes données des medias recues pour les insérer
    if(isset($tableMediaParam[0]))
    {
      foreach ($tableMediaParam as $media) 
      {
        insertionMedia($media[0],$media[1],  $idPost['Last_id']);
      }
    }

    //Faire un rollback s'il y a erreur, sinon commit les insertions
    if($commitAutorise)
    {
      $ps->commit();     
    }
    else 
    {
      $ps->rollBack();
    }

    return $commitAutorise;
  }

  //Faire une transaction pour inserer des medias sur un post existant
  function transactionInsertMedias($tableMediaParam, $idParam)
  {
    //booleen pour autoriser un rollback si detection d'une erreur PDO dans les fonctions d'insertion
    $commitAutorise = true;

    //Singleton PDO
    static $ps = null;
    if ($ps == null) 
    {
      $ps = m152();
    }

    //commencer la transaction
    $ps->beginTransaction();

    //Parcourir les différentes données des medias recues pour les insérer
    if(isset($tableMediaParam[0]))
    {
        foreach ($tableMediaParam as $media) 
        {
          insertionMedia($media[0],$media[1], $idParam);
        }
    }

    //Faire un rollback s'il y a erreur, sinon commit les insertions
    if($commitAutorise)
    {
      $ps->commit();
      
    }
    else 
    {
      $ps->rollBack();
    }

    return $commitAutorise;
  }

  //Faire une transaction pour supprimer un post existant avec ses medias
  function transactionDeletePostMedias($idParam, $tableIdParam)
  {
    //booleen pour autoriser un rollback si detection d'une erreur PDO dans les fonctions d'insertion
    $commitAutorise = true;

    //Singleton PDO
    static $ps = null;
    if ($ps == null) 
    {
      $ps = m152();
    }

    //commencer la transaction
    $ps->beginTransaction();

    //Suppression du post et ses médias
    foreach ($tableIdParam as $idASup) 
    {
      delMediaWithIdMedia($idASup);
    }     

    //Faire un rollback s'il y a erreur, sinon commit les insertions
    if($commitAutorise)
    {
      $ps->commit();
      
    }
    else 
    {
      $ps->rollBack();
    }

    return $commitAutorise;
  }

  //Faire une transaction pour supprimer des medias d'un post
  function transactionDeleteMedias($tableIdParam)
  {
    //booleen pour autoriser un rollback si detection d'une erreur PDO dans les fonctions d'insertion
    $commitAutorise = true;

    //Singleton PDO
    static $ps = null;
    if ($ps == null) 
    {
      $ps = m152();
    }

    //commencer la transaction
    $ps->beginTransaction();

    //Suppression du post et ses médias
    foreach ($tableIdParam as $idASup) 
    {
      delMediaWithIdMedia($idASup);
    }

    //Faire un rollback s'il y a erreur, sinon commit les insertions
    if($commitAutorise)
    {
      $ps->commit();
      
    }
    else 
    {
      $ps->rollBack();
    }

    return $commitAutorise;
  }

  //Faire une transaction pour supprimer des medias d'un post
  function transactionUpdateTimePostMedias($idParam)
  {
    //booleen pour autoriser un rollback si detection d'une erreur PDO dans les fonctions d'insertion
    $commitAutorise = true;

    //Singleton PDO
    static $ps = null;
    if ($ps == null) 
    {
      $ps = m152();
    }

    //commencer la transaction
    $ps->beginTransaction();

    //Mettre à jour les timestamp de modification
    updPostTimeWithIdPost($idParam);
    updMediaTimeWithIdPost($idParam);

    //Faire un rollback s'il y a erreur, sinon commit les insertions
    if($commitAutorise)
    {
      $ps->commit();
    }
    else 
    {    
      $ps->rollBack();
    }

    return $commitAutorise;
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
            $commitAutorise = false;
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
            $commitAutorise = false;
          }
          // return (isset($answer["iduser"]) ? $answer["iduser"] : False);
  
          return $answer;
  }

  //Fonction obtenir l'id du dernier post inséré
  function getLastPostId() 
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
      $commitAutorise = false;
    }
    // return (isset($answer["iduser"]) ? $answer["iduser"] : False);
  
    return $answer;
  }

  //Fonction obtenir tous les posts
  function getAllPost()
  {
    static $ps = null;
    $sql = "SELECT * FROM post ORDER BY creationDate DESC LIMIT 50;";
  
    if ($ps == null) {
      $ps = m152()->prepare($sql);
    }
    $answer = false;
    try {
  
      if ($ps->execute())
        $answer = $ps->fetchall(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo $e->getMessage();
      $commitAutorise = false;
    }
    // return (isset($answer["iduser"]) ? $answer["iduser"] : False);
  
    return $answer;
  }

  //Fonction obtenir le post à partir de son idPost
  function getPostWithId($idParam)
  {
    static $ps = null;
    $sql = "SELECT * FROM post WHERE idPost=:idSelect LIMIT 1;";
  
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
      $commitAutorise = false;
    }
    // return (isset($answer["iduser"]) ? $answer["iduser"] : False);
  
    return $answer;
  }

  //Fonction obtenir les médias à partir d'un isPost
  function getMediaWithIdPost($idParam)
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
      $commitAutorise = false;
    }
    // return (isset($answer["iduser"]) ? $answer["iduser"] : False);
  
    return $answer;
  }

  //Fonction obtenir un média à partir de son idMedia
  function getMediaWithIdMedia($idParam)
  {
    static $ps = null;
    $sql = "SELECT * FROM media WHERE idMedia=:idSelect LIMIT 1;";
  
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
      $commitAutorise = false;
    }
    // return (isset($answer["iduser"]) ? $answer["iduser"] : False);
  
    return $answer;
  }

  //Fonction supprimer les médias à partir d'un idPost
  function delMediaWithIdPost($idParam)
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
      $commitAutorise = false;
    }
    // return (isset($answer["iduser"]) ? $answer["iduser"] : False);
  
    return $answer;
  }

  //Fonction Supprimer un média à partir de son idMedia
  function delMediaWithIdMedia($idParam)
  {
    static $ps = null;
    $sql = "DELETE FROM media WHERE idMedia=:idSelect;";
  
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
      $commitAutorise = false;
    }
    // return (isset($answer["iduser"]) ? $answer["iduser"] : False);
  
    return $answer;
  }

   //Fonction Supprimer un post à partir de son idPost
  function delPostWithIdPost($idParam)
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
      $commitAutorise = false;
    }
    // return (isset($answer["iduser"]) ? $answer["iduser"] : False);
  
    return $answer;
  }

  //Fonction changer le texte d'un post à partir de son idPost
  function updPostWithIdPost($textParam,$idParam)
  {
    static $ps = null;
    $sql = "UPDATE post SET commentaire=:textParam WHERE idPost=:idParam;";
  
    if ($ps == null) {
      $ps = m152()->prepare($sql);
    }
    $answer = false;
    try {
      $ps->bindParam(':textParam', $textParam, PDO::PARAM_STR);
      $ps->bindParam(':idParam', $idParam, PDO::PARAM_INT);
  
      if ($ps->execute())
      $answer = true;
    } catch (PDOException $e) {
      echo $e->getMessage();
      $commitAutorise = false;
    }
    // return (isset($answer["iduser"]) ? $answer["iduser"] : False);
  
    return $answer;
  }

  //Fonction changer le temps de modification d'un post à partir de son idPost
  function updPostTimeWithIdPost($idParam)
  {
    static $ps = null;
    $sql = "UPDATE post SET modificationDate=CURRENT_TIME() WHERE idPost=:idParam;";
  
    if ($ps == null) {
      $ps = m152()->prepare($sql);
    }
    $answer = false;
    try {
      $ps->bindParam(':idParam', $idParam, PDO::PARAM_INT);
  
      if ($ps->execute())
      $answer = true;
    } catch (PDOException $e) {
      echo $e->getMessage();
      $commitAutorise = false;
    }
    // return (isset($answer["iduser"]) ? $answer["iduser"] : False);
  
    return $answer;
  }

  //Fonction changer le temps de modification des médias à partir d'un idPost
  function updMediaTimeWithIdPost($idParam)
  {
    static $ps = null;
    $sql = "UPDATE media SET modificationDate=CURRENT_TIME() WHERE idPost=:idParam;";
  
    if ($ps == null) {
      $ps = m152()->prepare($sql);
    }
    $answer = false;
    try {
      $ps->bindParam(':idParam', $idParam, PDO::PARAM_INT);
  
      if ($ps->execute())
      $answer = true;
    } catch (PDOException $e) {
      echo $e->getMessage();
      $commitAutorise = false;
    }
    // return (isset($answer["iduser"]) ? $answer["iduser"] : False);
  
    return $answer;
  }

  //Fonction afficher des posts et leurs médias dans index.php
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
          <a href=\"deleteProcess.php?identifiant=%d\"><img src=\"ressource/iconSupp.png\" width=\"28px\" height=\"28px\"></a>
        </p>                   
      </div>
    </div>";
    //Variable qui contiendra les blocks post et leurs médias
    $message = "";
    
    foreach(getAllPost() as $post)
    {
      //Variable qui contiendra les balises html d'affichage des médias, La variable sera inséré dans le block message du post
      $mediaMessage = "";
      
      $permisAffiche = false; 

      //Gérer quel côté afficher en fonction du pair/impair de l'idPost
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
        //Générer les balises pour afficher les médias
        foreach(getMediaWithIdPost($post['idPost']) as $media)
        {          
          //Pour afficher une image         
          if(stristr($media['typeMedia'], 'image'))
          {
            $cheminImage = $media['nomMedia'];
            $formatImage = "<img src=\"%s\" alt=\"Photo de grow\" width=\"100%%\"/>";
            $mediaMessage .= sprintf($formatImage, $cheminImage);
          }
          //Pour afficher une vidéo
          if(stristr($media['typeMedia'], 'video'))
          {
            $cheminVideo = $media['nomMedia'];
            $typeVideo = $media['typeMedia'];
            $formatVideo = "  <video width=\"100%%\" height=\"300\" autoplay muted loop>
                                <source src=\"%s\" type=\"%s\">
                              </video>";
            $mediaMessage .= sprintf($formatVideo, $cheminVideo, $typeVideo);
          }
          //Pour afficher une audio  
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
        
        //Récupérer le texte du post
        $textMessage = $post['commentaire'];
        //Id du post utilisé pour les suppression et modification
        $idMessage = $post['idPost'];

        //Ajouter un block post
        $message .= sprintf($formatMessage,$mediaMessage,$textMessage, $idMessage, $idMessage); 
      }                           
    }

    echo $message;    
  }

  //Fonction afficher des médias supprimables dans modification.php
  function affichageMediasModif($idParam)
  {
    $formatForm = "<ul class=\"list-group\">%s</ul><input class=\"btn btn-primary pull-right\" type=\"submit\" name=\"btnSupMedia\" value=\"Supprimer\" />";

    //Récupérer les médias supprimables à afficher dans modification.php
    $tableMediasModif = getMediaWithIdPost($idParam);
    $formAReturn = "";
    $compteur = 0;

    //Vérifier si le post a au moins un média
    if(count($tableMediasModif)>0)
    {
      $mediaLi = "";      
      foreach ($tableMediasModif as $media) 
      {
        //Créer un checkbox avec le texte correspondant nom du média et la value correspondant à l'id du média
        $formatMediaLi = "<li class=\"list-group-item\"><input type=\"checkbox\" id=\"%s\" name=\"media[]\" value=\"%d\"><label for=\"%s\">%s</label></li>";
        $idCheckbox = "m" . $compteur;
        $valueCheckbox = $media['idMedia'];
        $textCheckbox = substr($media['nomMedia'],21);

        $mediaLi .= sprintf($formatMediaLi, $idCheckbox, $valueCheckbox, $idCheckbox, $textCheckbox);
        $compteur = $compteur+1;
      }

      $formAReturn = sprintf($formatForm, $mediaLi);
    }
    else 
    {
      //Mettre aucun checkbox si pas de média
      $formAReturn = "Aucun média";
    }

    echo $formAReturn;
  }


  