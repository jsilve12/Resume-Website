<?php
session_start();
require_once "Functions.php";
require_once "pdo.php";
basic_stuff();
die_now();
errors();
$stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :profile_id");
$stmt->execute(array(":profile_id" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row === false)
{
    $_SESSION['error'] = 'Bad value for profile_id';
    header("Location:index.php");
    return;
}
if(isset($_POST['Delete']))
{
    $stmt = $pdo->prepare("DELETE FROM Profile WHERE profile_id = :profile_id");
    $stmt->execute(array(":profile_id" =>$_GET["profile_id"]));
    $_SESSION['success'] = "Record deleted ".$_GET['profile_id'];
    header("Location:index.php");
    return;
}
elseif(isset($_POST['Cancel']))
{
    header("Location:index.php");
    return;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Jonathan Silverstein's Autos</title>
    <?php require_once "bootstrap.php";?>
</head>
<body>
    <div class="container">
        <?php
        if ( isset($_SESSION['user_id']) ) {
            echo "<h1>Tracking Profile's for ";
            echo htmlentities($_SESSION['name']);
            echo "</h1>\n";
        }
        ?>
        <p> Confirm: Deleting <?= htmlentities($row['first_name']) ?></p>
    </div>
    <form method="post">
        <input type="submit" value="Delete" name="Delete" />
        <input type="submit" value="Cancel" name="Cancel" />
        <input type="submit" name="logout" value="Logout" />
    </form>
</body>
</html>