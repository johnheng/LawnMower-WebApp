<?php
// show potential errors / feedback (from login object)
if (isset($login)) {
    if ($login->errors) {
        foreach ($login->errors as $error) {
            echo '<div class="alert alert-warning fade in" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <strong>' . $error . "</div><br>";
        }
    }
    if ($login->messages) {
        foreach ($login->messages as $message) {
            echo '<div class="alert alert-warning fade in" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <strong>' . $message . "</div><br>";
        }
    }
}
if(isset($_SESSION["message"]))
{
  echo '<div class="alert alert-warning fade in" role="alert">
              <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <strong>' . $_SESSION["message"] . "</div><br>";
}
?>

<!-- Button trigger modal -->
      <button class="btn btn-primary btn-lg col-md-4 col-md-offset-4" data-toggle="modal" data-target="#myModal">
        Log in to LawnMower
      </button>

      <!-- Modal -->
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
              <h4 class="modal-title" id="myModalLabel">Login</h4>
            </div>
            <div class="modal-body">

              <form  method="post" action="index.php" name="loginform" class="form-horizontal" role="form">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Username</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputEmail3" name="user_name" placeholder="Username">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" id="inputPassword3" name="user_password" placeholder="Password">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <div class="radio">
                      <label class="radio-inline">
                        <input type="radio" name="user_type_1" id="inlineRadio1" value="customer"> Customer
                      </label>
                      <label class="radio-inline">
                        <input type="radio" name="user_type_2" id="inlineRadio2" value="employee"> Employee
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <div class="btn-group">
                  <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                      Register <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="register.php?customer">Customer</a></li>
                    <li><a href="register.php?employee">Employee</a></li>
                  </ul>
                </div>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" name="login" value="Log in">Login</button>
              </div>
          </div>
        </div>
      </div>