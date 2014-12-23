
<!DOCTYPE html>
<?php
session_start();
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="">

    <title>LawnMower App - Employee Page</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/dashboard.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="/js/ie-emulation-modes-warning.js"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="employeemain.php">Employee Dashboard</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href id="date"></a></li>
            <li><a href="index.php?logout">Logout</a></li>
            <li><a href="#">Settings</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li class="active"><a href="#">Overview</a></li>
            <!--<li><a href="#">Reports</a></li>-->
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <!-- <h3>Your next scheduled service is <mark>October 2nd, 2014</mark></h3> -->
          <?php
            require_once("classes/sql.php");
            $info = new SQL; $info -> findNearestService($_SESSION["user_id"], "employee");
          ?>
          
            
            <?php
              require_once("classes/sql.php");
              if (isset($_GET["more"])) {
                $info = new SQL; $info -> findAllEventsAll($_SESSION["user_id"]);
              }
              else {
                echo '<h2 class="sub-header"><a href="employeemain.php?more">Upcoming Services</a> <small>(for the next seven days)</small></h2>
                <div class="table-responsive">';
                $info = new SQL; $info -> findAllEventsFuture($_SESSION["user_id"], "employee");
                echo '</div>';
                echo '<h2 class="sub-header">Past Services <small>(for the last five appointments)</small></h2>
                <div class="table-responsive">';
                $info = new SQL; $info -> findAllEventsPast($_SESSION["user_id"], "employee");
              }
            ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/docs.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="/js/ie10-viewport-bug-workaround.js"></script>
    <script src="/js/date.js"></script>
    <script>
        window.onload = function(){
          defaultDate()
        };
    </script>
  </body>
</html>
