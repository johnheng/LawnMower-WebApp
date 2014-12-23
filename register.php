<!DOCTYPE HTML>
<!--
    Helios 1.5 by HTML5 UP
    html5up.net | @n33co
    Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<?php
session_start();
/*echo "GET<br>";
var_dump($_GET);

echo "<br>POST<br>";
var_dump($_POST);

echo "SESSION<br>";
var_dump($_SESSION);*/

/**
 * A simple, clean and secure PHP Login Script / MINIMAL VERSION
 * For more versions (one-file, advanced, framework-like) visit http://www.php-login.net
 *
 * Uses PHP SESSIONS, modern password-hashing and salting and gives the basic functions a proper login system needs.
 *
 * @author Panique
 * @link https://github.com/panique/php-login-minimal/
 * @license http://opensource.org/licenses/MIT MIT License
 */

// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once("libraries/password_compatibility_library.php");
}?>

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
            <section id="content">
                <?php
                if (version_compare(PHP_VERSION, '5.3.7', '<')) {
                    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
                } else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
                    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
                    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
                    require_once("libraries/password_compatibility_library.php");
                }

                require_once("config/db.php");

                // load the login class
                require_once("classes/Registration.php");

                $registration = new Registration();

                if(isset($_GET["customer"]) or ($_SESSION['registration_type'] == "customer"))
                {
                    include("views/register.php");
                }
                #if(isset($_GET["employee"]) or ($_SESSION['registration_type'] == "employee"))
                else
                {
                    include("views/employeeregister.php");
                }

                
                ?>
            </section><!-- content -->
        </div><!-- container -->
    </body>
</html>