<?php
session_start();
require_once("pdo.php");
require_once "Functions.php";
if(!isset($_GET['profile_id']))
{
  //The server - for whatever reason - dislikes header and makes you redirect inside an HTML page
    echo("<html>
				<head>
				  <meta http-equiv='Refresh' content='0;url=index.php' />
				  <!-- US -->
				</head>
			</html>");
        return;
}

basic_stuff();
$result = $pdo->prepare('SELECT * FROM Profile WHERE profile_id = :uid');
$result->execute(array(':uid' => $_GET['profile_id']));
$row = $result->fetch(PDO::FETCH_ASSOC);
if ( isset($_POST['cancel'] ))
{
  //The server - for whatever reason - dislikes header and makes you redirect inside an HTML page
    // Redirect the browser to resume.php
        echo("<html>
				<head>
				  <meta http-equiv='Refresh' content='0;url=resume.php?profile_id=".(string)$_GET['profile_id']."' />
				  <!-- US -->
				</head>
			</html>");
        return;
}
if(isset($_POST['Log']))
{
    $check = $_POST['pass'];
    $stmt10 = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');
    $stmt10->execute(array(':em' => $_POST['email'], ':pw'=> $check));
    $row = $stmt10->fetch(PDO::FETCH_ASSOC);

    if($row !== false)
    {
      //The server - for whatever reason - dislikes header and makes you redirect inside an HTML page
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_id'] = $row['user_id'];
     	echo("<html>
				<head>
				  <meta http-equiv='Refresh' content='0;url=modify.php?profile_id=".(string)$_GET['profile_id']."' />
				  <!-- US -->
				</head>
			</html>");
        return;
    }
    else
    {
        //The server - for whatever reason - dislikes header and makes you redirect inside an HTML page
        $_SESSION['error'] = "Invalid Username or Password";
        echo("<html>
				<head>
				  <meta http-equiv='Refresh' content='0;url=login.php?profile_id=".(string)$_GET['profile_id']."' />
				  <!-- US -->
				</head>
			</html>");
        return;
    }
}


// Check to see if we have some POST data, if we do process it

?>
<!DOCTYPE html>
<html>
<head>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Jonathan Silverstein's Login Page</title>

  <!-- Bootstrap core CSS -->
  <link href="Bootstrap/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template -->
  <link href="https://fonts.googleapis.com/css?family=Saira+Extra+Condensed:500,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,800,800i" rel="stylesheet">
  <link href="Bootstrap/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="Bootstrap/css/resume.min.css" rel="stylesheet">

</head>
<body id="page-top">

  <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top" id="sideNav">
    <a class="navbar-brand js-scroll-trigger" href="#page-top">
      <span class="d-block d-lg-none"><?php echo($row['first_name']); ?>  <?php echo($row['last_name']); ?></span>
      <span class="d-none d-lg-block">
        <img class="img-fluid img-profile rounded-circle mx-auto mb-2" src="Bootstrap/img/profile.jpg" alt="">
      </span>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link js-scroll-trigger" href="resume.php?profile_id=<?php echo($_GET['profile_id']); ?>">
            Resume</a>
        </li>
      </ul>
    </div>
  </nav>
    <div class="container">
<h2>Please Log In</h2>
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
<!-- Bootstrap core JavaScript -->
<script src="Bootstrap/vendor/jquery/jquery.min.js"></script>
<script src="Bootstrap/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Plugin JavaScript -->
<script src="Bootstrap/vendor/jquery-easing/jquery.easing.min.js"></script>
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
    }
</script>
</div>
</body>
