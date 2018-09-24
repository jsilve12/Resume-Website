<?php

  class send{
    $buttonName;
    $sqlName;
    $tableName;

    public function set($butt, $sql, $table)
    {
      $this->buttonName = $butt;
      $this->sqlName = $sql;
      $this->table = $table;
    }
    public function getButton()
    {
      return $this->buttonName;
    }
    public function send($value, $profile)
    {
      $result1 = $pdo->prepare('UPDATE '.$tableName.'  SET '.$sqlName.' = :em WHERE profile_id = :pid');
      //For some reason the explode function counts 12 spaces
      $result1->execute(array(
        ':pid' => $profile,
        ':em' => $value
      ));
    }
    public function sendInt($value, $integer, $profile)
    {
      $result1 = $pdo->prepare('UPDATE '.$tableName.'  SET '.$sqlName.' = :em WHERE profile_id = :pid prim = :prim');
      //For some reason the explode function counts 12 spaces
      $result1->execute(array(
        ':pid' => $profile,
        ':prim' => $integer,
        ':em' => $value
      ));
    }
  }
    session_start();
    require_once("pdo.php");
    require_once("Functions.php");
    // if(!isset($_GET['profile_id']))
    // {
    //     header("Location:index.php");
    //     $_SESSION['error'] = "Profile Doesn't Exist";
    //     return;
    // }
    $result = $pdo->prepare('SELECT * FROM Profile WHERE profile_id = :uid');
    $result->execute(array(':uid' => $_POST['profile_id']));
    $row = $result->fetch(PDO::FETCH_ASSOC);

    //Go through all the if statements and have them modify the appropriate thing
    if($_POST['name'] == "Fname")
    {
      $result1 = $pdo->prepare('UPDATE Profile SET first_name = :fn, last_name = :lan WHERE profile_id = :pid');
      $names = explode(' ',$_POST['value']);
      //For some reason the explode function counts 12 spaces
      $result1->execute(array(
        ':pid' => $_POST['profile_id'],
        ':fn' => $names[0],
        ':lan' => $names[12]
      ));
      echo('Success');
    }
    $buttonNames = ["email" => , "summary", "description", "experience", "ExpYear", "School", "Degree", "GPA", "Year", "Skill", "SkillDescription"];
    foreach($buttonNmes)
    else if($_POST['name'] == "email")
    {
      $result1 = $pdo->prepare('UPDATE Profile SET email = :em WHERE profile_id = :pid');
      //For some reason the explode function counts 12 spaces
      $result1->execute(array(
        ':pid' => $_POST['profile_id'],
        ':em' => $_POST['value']
      ));
      echo('Success');
    }
?>
