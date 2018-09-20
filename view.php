<?php
    session_start();
    require_once("pdo.php");
    require_once("Functions.php");
    if(!isset($_GET['profile_id']))
    {
        header("Location:index.php");
        $_SESSION['error'] = "Profile Doesn\'t Exist";
        return;
    }
    basic_stuff();
    $result = $pdo->prepare('SELECT * FROM Profile WHERE profile_id = :uid');
    $result->execute(array(':uid' => $_GET['profile_id']));
    $row = $result->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Jonathan Silverstein's Resume View</title>
</head>
<body>
<h1>Profile information</h1>
<form method="post">
    <p>First Name: <?php echo($row['first_name']); ?> </p>
    <p>Last Name: <?php echo($row['last_name']); ?></p>
    <p>Email: <?php echo($row['email']); ?></p>
    <p>Headline: <?php echo($row['headline']); ?></p>
    <p>Summary: <?php echo($row['summary']); ?></p>
    <?php
        //Creates the table variables and gets the profile data
        $stmt1 = $pdo->prepare('SELECT * FROM `Position` WHERE profile_id = :uid');
        $stmt1->execute(array(':uid' => $_GET['profile_id']));
        
        //Enters the profile data into the form
        while($row = $stmt1->fetch(PDO::FETCH_ASSOC))
        {
            echo('<p> Year:'.$row['year'].'</p>');
            echo('<p> Description: '.$row['description'].'</p>');
        }
        
        //Gets the Education Data
        $stmt2 = $pdo->prepare('SELECT Education.profile_id, Education.rank, Education.year, Institution.institution_id, Education.institution_id, Institution.name FROM (Education INNER JOIN Institution ON Education.institution_id = Institution.institution_id) WHERE profile_id = :uid');
        $stmt2->execute(array(':uid' => $_GET['profile_id']));
        
        //Enters the profile data into the form
        while($row = $stmt2->fetch(PDO::FETCH_ASSOC))
        {
            echo('<p> Year:'.$row['year'].'</p>');
            echo('<p> School: '.$row['name'].'</p>');
        }
    ?>
    <input type="submit" value="cancel" name="Cancel">
    <input type="submit" value="Logout" name="Logout" >
</form>

</body>
</html>