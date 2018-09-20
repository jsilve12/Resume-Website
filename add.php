<?php
//Use to avoid duplicating the add page
session_start();
require_once "pdo.php";
require_once "Functions.php";

basic_stuff();
//If there are errors in Position submission
$msg = validatePos();
if(is_string($msg)){
    $_SESSION['error'] = $msg;
    header("Location:".basename($_SERVER['PHP_SELF']."?profile_id=".$_GET['profile_id']));
    return;
}

//Creates the table variables
$Position = new DB;
$Position->table = "Position";

$Profile = new DB;
$Profile->table = "Profile";

$users = new DB;
$users->table = "users";

die_now();
errors();

//Pulls the values of the field variables
$fn = "";
$ln = "";
$em = "";
$he = "";
$su = "";
$tag = "Add";
$countPos = 0;
$countInst = 0;

//Gets the information for an edit form
if(basename($_SERVER['PHP_SELF']) == "edit.php")
{
    $stmt1 = $pdo->prepare('SELECT * FROM Profile WHERE profile_id = :uid');
    $stmt1->execute(array(':uid' => $_GET['profile_id']));
    $row = $stmt1->fetch(PDO::FETCH_ASSOC);
            
    //Makes sure the id exists
    if($row === false)
    {
        $_SESSION['error'] = "user_id doesn't exist";
    }
            
    //Prepares the values to be entered into the text field
    $fn = $row['first_name'];
    $ln = $row['last_name'];
    $em = $row['email'];
    $he = $row['headline'];
    $su = $row['summary'];
    $tag = "Save";
}

//Deals with the SQL insertion
if(isset($_POST['first_name']) || isset($_POST['last_name']) || isset($_POST['email']) || isset($_POST['headline']) || isset($_POST['summary']))
{
    if(strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1)
    {
        $_SESSION["error"] = ('All fields are required');
        header("Location: ".basename($_SERVER['PHP_SELF'])."?profile_id=".$_GET['profile_id']);
        return;
    }
    else if(! strpos($_POST['email'], ('@')))
    {
        $_SESSION['error'] = "The email address must contain an @";
        header("Location: ".basename($_SERVER['PHP_SELF'])."?profile_id=".$_GET['profile_id']);
        return;
    }
    else
    {
       $profile_id = 0;
        if(basename($_SERVER['PHP_SELF']) == "add.php")
        {
            $stmt = $pdo->prepare('INSERT INTO Profile(user_id, first_name, last_name, email, headline, summary) VALUES ( :uid, :fn, :ln, :em, :he, :su)');
            $stmt->execute(array(
                ':uid' => $_SESSION['user_id'],
                ':fn' => $_POST['first_name'],
                ':ln' => $_POST['last_name'],
                ':em' => $_POST['email'],
                ':he' => $_POST['headline'],
                ':su' => $_POST['summary']
            ));
            $_SESSION['success'] = ('added');
            
            //Gets the primary key id
            $profile_id = $pdo->lastInsertId();
        }
        else
        {
            $profile_id = $_GET['profile_id'];
            $stmt = $pdo->prepare('UPDATE Profile SET first_name= :fn, last_name = :ln, email = :em, headline = :he, summary = :su WHERE profile_id=:uid ');
            $stmt->execute(array(
                ':uid' => $_GET['profile_id'],
                ':fn' => $_POST['first_name'],
                ':ln' => $_POST['last_name'],
                ':em' => $_POST['email'],
                ':he' => $_POST['headline'],
                ':su' => $_POST['summary']
            ));
            $_SESSION['success'] = ('updated');
            
            //Deletes the old positions and institutions while editing
            $stmt = $pdo->prepare('DELETE FROM `Position` WHERE profile_id = :uid');
            $stmt->execute(array(':uid' => $_GET['profile_id']));
            
            $stmtq = $pdo->prepare('DELETE FROM `Education` WHERE profile_id = :uid');
            $stmtq->execute(array(':uid' => $_GET['profile_id']));
        }
        //Adds the Positions
        for($i=1;$i<=9; $i++)
        {
            if(! isset($_POST['year'.$i])) continue;
            if(! isset($_POST['desc'.$i])) continue;
            
            $year = $_POST['year'.$i];
            $desc = $_POST['desc'.$i];
                $stmt = $pdo->prepare('INSERT INTO `Position`(profile_id, rank, `year`, description) VALUES ( :uid, :fn, :ln, :em)');
                $stmt->execute(array(
                ':uid' => $profile_id,
                ':fn' => $i,
                ':ln' => $year,
                ':em' => $desc
            ));
        }
        
        //Adds the Institutions
        for($i=0;$i<=9; $i++)
        {
            if(! isset($_POST['yeartime'.$i])) continue;
            if(! isset($_POST['edu_school'.$i])) continue;
            
            //This logic determines whether the institution exists or it must be added
            $inst = $_POST['edu_school'.$i];
            $stmt = $pdo->prepare('SELECT * FROM Institution WHERE name=:uid');
            $stmt->execute(array(':uid' => $inst));
            $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            //Inserts the id if it doesn't exist, and gets the value
            //for the institution either way
            if(empty($arr))
            {
                $stmt1 = $pdo->prepare('INSERT INTO Institution(name) VALUES(:uid)');
                $stmt1->execute(array(':uid' => $inst));
                $inst = $pdo->lastInsertId();
            }
            else
            {
                $inst = $arr[0]['institution_id'];
            }
            
            // Adds the new institution to profile keys
            $year = $_POST['yeartime'.$i];
                $stmt = $pdo->prepare('INSERT INTO `Education`(profile_id, institution_id, `rank`, year) VALUES ( :uid, :fn, :ln, :em)');
                $stmt->execute(array(
                ':uid' => $profile_id,
                ':fn' => $inst,
                ':ln' => $i,
                ':em' => $year
            ));
        }
        header("Location: index.php");
        return;
    }
}
errors();
?>        
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
    crossorigin="anonymous">

<link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r"
    crossorigin="anonymous">

<link rel="stylesheet" 
    href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" 
    integrity="sha384-xewr6kSkq3dBbEtB6Z/3oFZmknWn7nHqhLVLrYgzEFRbU/DHSxW7K3B44yWUN60D" 
    crossorigin="anonymous">

<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>

<script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
  integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
  crossorigin="anonymous"></script>
<title>Jonathan Silverstein's Resumes Add</title>
<?php require_once "bootstrap.php";?>
</head>
<body>
<div class="container">
<?php
if ( isset($_SESSION['email']) ) {
    echo "<h1>Adding Resumes's for ";
    echo htmlentities($_SESSION['email']);
    echo "</h1>\n";
}
?>
<form method="post">
    <p>First Name:
    <input type="text" name="first_name" value="<?= $fn ?>" />
    </p>
    <p>
    Last Name:
    <input type="text" name="last_name" value= "<?= $fn ?>" />
    </p>
    <p>Email:
    <input type="text" name="email" id="Email" value= "<?= $em ?>" /> </p>
    <p>Headline
    <input type="text" name="headline" id="Headline" value="<?= $he?>"/></p>
    <p>Summary
    <input type="text" name="summary" id="Summary" value="<?= $su ?>" />
    </p>
    <p>
        Institution: <input type = "submit" id="addInst" value= "+">
    <?php
    //Handles the addition forms added by the edit for institution
        if(basename($_SERVER['PHP_SELF']) == "edit.php")
        {
            //Retrieves the education
        $stmt2 = $pdo->prepare('SELECT Education.profile_id, Education.rank, Education.year, Institution.institution_id, Education.institution_id, Institution.name FROM (Education INNER JOIN Institution ON Education.institution_id = Institution.institution_id) WHERE profile_id = :uid');
            
        $stmt2->execute(array(':uid' => $_GET['profile_id']));
        while($row1 = $stmt2->fetch(PDO::FETCH_ASSOC))
        {
            $countInst++;
            echo('<div id ="institution'.$countInst.'">'.
            '<p> Year: <input type="text" name="yeartime'.$countInst.'"'.
            'value = "'.$row1['year'].'" />'.
            '<input type="button" value="-"'.
            'onclick=$(\'#institution'.$countInst.'\').remove();return false;"></p>'.
            'School: <input type="text" size="80" name="edu_school'.$countInst.'" class="school" value="'.$row1['name'].'"/> </div>');
        }
    }
    ?>
        <div id = "institution"></div>
        </p>
    <p>
        Position: <input type = "submit" id="addPos" value= "+">
    <?php
    //Handles the additional forms added from edit
    $countPos = 0;
    $countInst++;
    if(basename($_SERVER['PHP_SELF']) == "edit.php")
    {
        //Retrieves the Positions
        $stmt1 = $pdo->prepare('SELECT * FROM `Position` WHERE profile_id = :uid');
        $stmt1->execute(array(':uid' => $_GET['profile_id']));
        
        while($row = $stmt1->fetch(PDO::FETCH_ASSOC))
        {
            $countPos++;
            echo('<div id ="position'.$countPos. '">'.
                '<p> Year:<input type="text" name="year'.$countPos.'" value = "'.$row['year'].'" />'.
                '<input type="button" value="-"'.
                'onclick=$(\'#position'.$countPos.'\').remove();return false;"></p>'.
                '<p><textarea name="desc'.$countPos.'" rows ="8" cols = "80"></p>'.
                $row['description'].'</textarea>'.
                '</div>');
        }
    }
    ?>
        <div id = "position_fields"></div>
    </p>
    <p>
    <input type="submit" value= "<?= $tag ?>"/>
    </form>
    <form method="post">
    <input type="submit" value="cancel" name="Cancel">
    </p>
</form>
<script>
    countPos = <?php echo($countPos) ?>;
    countInst = <?php echo($countInst) ?>;
    $(document).ready(function(){
        window.console && console.log('Document ready called');
        //The javascript for the Position button
        $('#addPos').click(function(event){
            event.preventDefault();
            if(countPos >= 9)
            {
                alert("Maximum of nine position entries exceeded");
                return;
            }
            countPos++;
            window.console && console.log("Adding Position "+countPos);
            $('#position_fields').append(
                '<div id ="position'+countPos+ '"> \
                <p> Year:<input type="text" name="year'+countPos+'" value = "" /> \
                <input type="button" value="-" \
                onclick=$(\'#position'+countPos+'\').remove();return false;"></p>\
                <textarea name="desc'+countPos+'" rows ="8" cols = "80"></textarea>\
                </div>'); 
        });
        //The javascript for the Institution button
        $('#addInst').click(function(even){
            event.preventDefault();
            if(countPos >= 9)
            {
                alert("Maximum of nine institution entries exceeded");
                return;
            }
            countInst++;
            window.console && console.log("Adding Institution "+countInst);
            $('#institution').append(
                '<div id ="institution'+countInst+'">\
                <p> Year: <input type="text" name="yeartime'+countInst+ '"\
                />\
                <input type="button" value="-"\
                onclick=$(\'#institution'+countInst+'\').remove();return false;"></p>\
                <p>\
                School: <input type="text" size="80" name="edu_school'+countInst+'" class="school" value=""/> </p>');
            //Autofill javascript
            $('.school').autocomplete({source:"school.php"});
        });
        
        //Autofill javascript
        $('.school').autocomplete({source:"school.php"});
    })
</script>
</div>
</body>
</html>
