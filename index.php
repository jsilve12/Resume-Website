<?php header('Location: resume.php?profile_id=1'); ?>
<!-- <?php
session_start();
require_once("Functions.php");
require_once("pdo.php");
basic_stuff();
// If the user requested logout go back to index.php
if ( isset($_GET['Log']) ) {
    header('Location: logout.php');
    return;
}
//Used after the successful entry of a record
if( isset($_SESSION['success']))
{
    echo('<p style="color:green">'.$_SESSION['success']."</p>\n");
    unset($_SESSION['success']);
}
?>
<!DOCTYPE html>
<html>
<head>
<title> Jonathan Silverstein - Resume's </title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Welcome to Resume Storage</h1>
<?php
//Options if you are logged in
if ( ! isset($_SESSION['user_id']) || strlen($_SESSION['user_id']) < 1  ) {
    echo("<p>");
    echo("<a href='login.php'>Please log in</a>");
    echo("</p>");
    echo("</br>");
}
//If you aren't logged in
else
{
    if ( isset($_SESSION['user_id']) ) {
        echo "<h1>Tracking Resume's for ";
        echo htmlentities($_SESSION['name']);
        echo ("</h1>\n");
        echo ("<a href ='add.php'>Add New Entry</a>");
    echo("</br>");
    }
}
// Fall through into the View
?>

    <?php
//Prints the basic information
    $result = $pdo->query('SELECT * FROM Profile, users WHERE Profile.user_id=users.user_id');
    $count = 0;
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        if($count == 0)
        {
            ++$count;
            echo("<table style='width:50%'; ");
            echo("border='1';");
            echo("border:20 px solid black;>");
            echo("<tr> <th> Name </th> <th> Headline </th> <th> Edit/Delete </th> <tr/>");
        }
            echo(" <tr> ");
            echo(" <td> <a href='resume.php?profile_id=".htmlentities($row['profile_id'])."'>".$row['first_name']."</a>"."
            </td>");
            echo("<td>".htmlentities($row['headline'].' ')."</td>");
            if(isset($_SESSION['user_id'])&& $_SESSION['user_id'] = htmlentities($row['user_id']))
            {
                echo("<td><a href='edit.php?profile_id=".$row['profile_id']."'>Edit</a>"." / "."<a href='delete.php?profile_id=".$row['profile_id']."'>Delete</a> </td>");
            }
            echo("<tr/>");
    }
    echo("</table>");
    if($count == 0)
    {
        echo("No rows found");
        echo("<br/>");
    }
    if(isset($_SESSION['user_id']))
    {
        echo("<a href='index.php?Log=out'>Logout</a>");
    }
    ?>
</div>
</body>
</html> -->
