<?php
session_start();
require_once("pdo.php");
require_once("Functions.php");

  class send{
    public $buttonName;
    public $sqlName;
    public $tableName;

    public function set($butt, $sql, $table)
    {
      $this->buttonName = $butt;
      $this->sqlName = $sql;
      $this->tableName = $table;
    }
    public function getButton()
    {
      return $this->buttonName;
    }
    public function getSQL()
    {
      return $this->sqlName;
    }
    public function getTable()
    {
      return $this->tableName;
    }
  }
    $result = $pdo->prepare('SELECT * FROM Profile WHERE profile_id = :uid');
    $result->execute(array(':uid' => $_POST['profile_id']));
    $row = $result->fetch(PDO::FETCH_ASSOC);

    //Creates an array of objects that can be saved on the server
    $email = new send;
    $email->set('email','email','Profile');

    $summary = new send;
    $summary->set('summary','summary','Profile');

    $header = new send;
    $header->set('experience','header','Posit');

    $descript = new send;
    $descript->set('ExpDescription', 'description', 'Posit');

    $years = new send;
    $years->set('ExpYear', 'years', 'Posit');

    $school = new send;
    $school->set('School', 'name', 'Education');

    $degree = new send;
    $degree->set('Degree', 'Degree', 'Education');

    $GPA = new send;
    $GPA->set('GPA', 'GPA', 'Education');

    $year = new send;
    $year->set('SchoolYear', 'Years', 'Education');

    $skill = new send;
    $skill->set('Skillers', 'Skill', 'Skills');

    $sDescript = new send;
    $sDescript->set('SkillDescription', 'Description', 'Skills');

    $Interest = new send;
    $Interest->set('Interest', 'description', 'Interest');

    $options = array($email, $summary, $header, $descript, $years, $school, $degree, $GPA, $year, $skill, $sDescript, $Interest);

    // public function send($value, $profile)
    // {
    //   $result1 = $pdo->prepare('UPDATE '.$tableName.'  SET '.$sqlName.' = :em WHERE profile_id = :pid');
    //   //For some reason the explode function counts 12 spaces
    //   $result1->execute(array(
    //     ':pid' => $profile,
    //     ':em' => $value
    //   ));
    // }


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
    else
    {
      foreach($options as $key)
      {
        if(strpos($_POST['name'], $key->getButton()) !== false)
        {
          //Gets any index
          $index = substr($_POST['name'], strlen($key->getButton()));
          if(strlen($index) == 0)
          {
            $result1 = $pdo->prepare('UPDATE '.$key->getTable().' SET '.$key->getSQL().' = :em WHERE profile_id = '.$_POST['profile_id']);
              $result1->execute(array(
                ':em' => $_POST['value']
              ));
          }
          else {
            echo($key->getSQL());
            $result1 = $pdo->prepare('UPDATE '.$key->getTable().'  SET '.$key->getSQL().' = :em WHERE profile_id = '.$_POST['profile_id'].' AND prim = '.$index);
              $result1->execute(array(
                ':em' => $_POST['value']
              ));
              echo('success');
          }
        }
      }
    }
?>
