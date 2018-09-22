<?php
    session_start();
    require_once("pdo.php");
    require_once("Functions.php");
    if(!isset($_GET['profile_id']))
    {
        header("Location:index.php");
        $_SESSION['error'] = "Profile Doesn't Exist";
        return;
    }
    basic_stuff();
    $result = $pdo->prepare('SELECT * FROM Profile WHERE profile_id = :uid');
    $result->execute(array(':uid' => $_GET['profile_id']));
    $row = $result->fetch(PDO::FETCH_ASSOC);

    //Go through all the if statements and have them modify the appropriate thing
    if($_POST['name'] == "name")
    {
      $result1 = $pdo->prepare('UPDATE Profile SET first_name = :fn, last_name = :lan WHERE profile_id = :pid');
      $names = explode(" ", $_GET['value']);
      $result1->execute(array(
        ':pid' => $_GET['profile_id'],
        ':fn' => $names[0],
        ':ln' => $names[1]
      ));
    }
?>
