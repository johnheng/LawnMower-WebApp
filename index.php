<?php
ob_start();
?>

<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>LawnMower App</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/jumbotron-narrow.css" rel="stylesheet">
    <link href="css/carousel.css" rel="stylesheet">
    <link href="css/sticky-footer.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="/js/ie10-viewport-bug-workaround.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    

  </head>

  <body>
    <div class="container">  
      <?php
          require_once("config/db.php");
         require_once("classes/Login.php");
         $login = new Login();
          #var_dump($_SESSION);
         // ... ask if we are logged in here:
         
         if ($login->isUserLoggedIn() == true) 
         {
            if($_SESSION["user_type"] == "employee")
            {
              header("location: employeemain.php");
            }
            else
            {
              header("location: customermain.php");
            }
         }
      ?>
      <?php
        include("views/not_logged_in.php");
      ?>
    </div> <!-- /container -->



    <div class="footer">
        <div class="container">
          <p class="text-muted">&copy LawnMower App</p>
        </div>
      </div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
  <script src="http://code.jquery.com/jquery-latest.js"></script>
  <script src="js/bootstrap.min.js"></script>
  </body>
</html>