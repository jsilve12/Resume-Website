<?php
session_start();
require_once("pdo.php");
require_once "Functions.php";
if ( isset($_POST['cancel'] ))
{
    // Redirect the browser to autos.php
        header("location: index.php");
    return;
}
$salt = 'XyZzy12*_';
if(isset($_POST['Log']))
{
    $check = hash('md5', $salt.$_POST['pass']);
    $stmt10 = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');
    $stmt10->execute(array(':em' => $_POST['email'], ':pw'=> $check));
    $row = $stmt10->fetch(PDO::FETCH_ASSOC);
    
    if($row !== false)
    {
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_id'] = $row['user_id'];
        header("Location: index.php");
        return;
    }
    else
    {
        $_SESSION['error'] = "Invalid Username or Password";
        header("Location: login.php");
        return;
    }
}


// Check to see if we have some POST data, if we do process it
    
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Jonathan Silverstein's Login Page</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
    errors();
?>
<form method="POST">
<label for="nam">User Name</label>
<input type="text" name="email" id="email"><br/>
<label for="id_1723">Password</label>
<input type="password" name="pass" id="id_1723"><br/>
<input type="submit" onclick="return doValidate();" name= "Log" value=" Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint: The password is the four character sound a cat
makes (all lower case) followed by 123. -->
</p>
<script type=text/javascript>
    function doValidate()
    {
        console.log('Validating...');
        try
        {
            addr = document.getElementById('email').value;
            pass = document.getElementById('id_1723').value;
            
            console.log("Validating username="+addr+" password="+pass);
            
            if((pass == null || pass == "" || addr == null || addr == ""))
            {
                alert("Both fields must be filled out");
                console.log('Returned False');
                return false;
            }
            if(addr.indexOf('@') == -1)
            {
                alert("Email must contain @");
                console.log('Returned False');
                return false;
            }
            return true;
        } catch(e)
        {
            return false;
        }
        return false;
    }
</script>
</div>
</body>
