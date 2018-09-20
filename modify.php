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
    die_now();
    errors();
    basic_stuff();
    $result = $pdo->prepare('SELECT * FROM Profile WHERE profile_id = :uid');
    $result->execute(array(':uid' => $_GET['profile_id']));
    $row = $result->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Resume - Start Bootstrap Theme</title>

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
            <a class="nav-link js-scroll-trigger" href="#about">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="#experience">Experience</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="#education">Education</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="#skills">Skills</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="#interests">Interests</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll-trigger" href="resume.php?profile_id=<?php echo($_GET['profile_id']); ?>">
              Finish</a>
          </li>
        </ul>
      </div>
    </nav>


    <div class="container-fluid p-0">

      <section class="resume-section p-3 p-lg-5 d-flex d-column" id="about">
        <div class="my-auto" id="name">
          <h1 class="mb-0" id="Fname"><?php echo($row['first_name']); ?>
            <span class="text-primary" id="Lname"><?php echo($row['last_name']); ?></span></h1><button type = 'button' onclick = changeName()> Modify</button>
          <div class="subheading mb-5">
            <a href="mailto:name@email.com"><?php echo($row['email']); ?></a>
          </div>
          <p class="lead mb-5"><?php echo($row['summary']); ?></p>
          <div class="social-icons">
            <a href="#">
              <i class="fab fa-linkedin-in"></i>
            </a>
            <a href="#">
              <i class="fab fa-github"></i>
            </a>
            <a href="#">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="#">
              <i class="fab fa-facebook-f"></i>
            </a>
          </div>
        </div>
      </section>

      <hr class="m-0">

      <section class="resume-section p-3 p-lg-5 d-flex flex-column" id="experience">
        <div class="my-auto">
          <h2 class="mb-5">Experience</h2>

          <div class="resume-item d-flex flex-column flex-md-row mb-5">
            <?php
              //Creates the table variables and gets the profile data
              $stmt1 = $pdo->prepare('SELECT * FROM `Position` WHERE profile_id = :uid');
              $stmt1->execute(array(':uid' => $_GET['profile_id']));

              //Enters the profile data into the form
              while($row = $stmt1->fetch(PDO::FETCH_ASSOC))
              {
                echo('<div class="resume-content mr-auto">
              <h3 class="mb-0">'.$row['header'].'</h3>');
                echo(' <p>'.$row['description'].'</p></div>');
                echo('<div class="resume-date text-md-right">
              <span class="text-primary">'.$row['year'].'</span>
            </div>
            </div>');
              };
              ?>

      </section>

      <hr class="m-0">

      <section class="resume-section p-3 p-lg-5 d-flex flex-column" id="education">
        <div class="my-auto">
          <h2 class="mb-5">Education</h2>
        </div>
          <?php
           $stmt2 = $pdo->prepare('SELECT Education.profile_id, Education.rank, Education.year, Institution.Years, Institution.Degree, Institution.GPA, Institution.institution_id, Education.institution_id, Institution.name FROM (Education INNER JOIN Institution ON Education.institution_id = Institution.institution_id) WHERE profile_id = :uid');
        $stmt2->execute(array(':uid' => $_GET['profile_id']));

        //Enters the profile data into the form
        while($row = $stmt2->fetch(PDO::FETCH_ASSOC))
        {
            echo('
              <h3 class="mb-0">'.$row['name'].'</h3>
              <div class="subheading mb-3">'.$row['Degree'].'
              <p>GPA: '.$row['GPA'].'</p>
            </div>
            <div class="resume-date text-md-right">
              <span class="text-primary">'.$row['Years'].'</span>
            </div>
          </div>');
        }
        ?>

      </section>

      <hr class="m-0">

      <section class="resume-section p-3 p-lg-5 d-flex flex-column" id="skills">
        <div class="my-auto">
          <h2 class="mb-5">Skills</h2>
          <?php
          $stmt2 = $pdo->prepare('SELECT * FROM Skills');
        $stmt2->execute();

        //Enters the profile data into the form
        while($row = $stmt2->fetch(PDO::FETCH_ASSOC))
        {
            echo('
              <h3 class="mb-0">'.$row['Skill'].'</h3>
              <p>'.$row['Description'].'</p>');
        }
          ?>
        </div>
      </section>

      <hr class="m-0">

      <section class="resume-section p-3 p-lg-5 d-flex flex-column" id="interests">
        <div class="my-auto">
          <h2 class="mb-5">Interests</h2>
          <?php
            $stmt2 = $pdo->prepare('SELECT * FROM Interest');
            $stmt2->execute();

            //Enters the profile data into the form
            while($row = $stmt2->fetch(PDO::FETCH_ASSOC))
            {
              echo('
              <p class="mb-0">'.$row['description'].'</p>');
            }
          ?>
        </div>
      </section>

      <hr class="m-0">

    </div>
    <script type=text/javascript>
        function changeName()
        {
            console.log('Pulling up name Change');
            try
            {
                Fname = document.getElementById('Fname').value;
                Lname = document.getElementById('Lname').value;

                document.getElementById('Fname').contentEditable = "true";
                return false;
            } catch(e)
            {
            return false;
          }
        }
    </script>
    <!-- Bootstrap core JavaScript -->
    <script src="Bootstrap/vendor/jquery/jquery.min.js"></script>
    <script src="Bootstrap/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="Bootstrap/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for this template -->
    <script src="Bootstrap/js/resume.min.js"></script>

  </body>

</html>
