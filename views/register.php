<?php
// show potential errors / feedback (from registration object)
if (isset($registration)) {
    if ($registration->errors) {
        foreach ($registration->errors as $error) {
            echo $error;
        }
    }
    if ($registration->messages) {
        foreach ($registration->messages as $message) {
            echo $message;
        }
    }
}
?>

<form class="form-horizontal" action="register.php" method="POST" name="registerform">
  <fieldset>
    <div id="legend">
      <legend class="">New Customer Registration</legend>
    </div>
    <div class="control-group">
      <!-- Username -->
      <label class="control-label"  for="user_name">Username*</label>
      <div class="controls">
        <!-- <input type="text" id="username" name="username" placeholder="" class="input-xlarge"> -->
        <input id="login_input_username" class="login_input" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" required />
        <p class="help-block text-muted">Username can contain any letters or numbers, without spaces</p>
      </div>
    </div>

    <div class="control-group">
      <!-- E-mail -->
      <label class="control-label" for="user_email">Name*</label>
      <div class="controls">
        <!-- <input type="text" id="email" name="email" placeholder="" class="input-xlarge"> -->
        <input id="name" class="login_input" type="text" name="name" required />
      </div>
    </div>
 
    <div class="control-group">
      <!-- E-mail -->
      <label class="control-label" for="user_email">E-mail*</label>
      <div class="controls">
        <!-- <input type="text" id="email" name="email" placeholder="" class="input-xlarge"> -->
        <input id="login_input_email" class="login_input" type="email" name="user_email" required />
        <p class="help-block text-muted">Please provide your E-mail</p>
      </div>
    </div>
 
    <div class="control-group">
      <!-- Password-->
      <label class="control-label" for="user_password_new">Password*</label>
      <div class="controls">
        <!-- <input type="password" id="password" name="password" placeholder="" class="input-xlarge"> -->
        <input id="login_input_password_new" class="login_input" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" />
        <p class="help-block text-muted">Password should be at least 6 characters</p>
      </div>
    </div>
 
    <div class="control-group">
      <!-- Password -->
      <label class="control-label"  for="user_password_repeat">Password* (Confirm)</label>
      <div class="controls">
        <!-- <input type="password" id="password_confirm" name="password_confirm" placeholder="" class="input-xlarge"> -->
        <input id="login_input_password_repeat" class="login_input" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />
        <p class="help-block text-muted">Please confirm password</p>
      </div>
    </div>


    <div class="control-group">
      <!-- Password -->
      <label class="control-label"  for="address1">Address line 1*</label>
      <div class="controls">
        <!-- <input type="password" id="password_confirm" name="password_confirm" placeholder="" class="input-xlarge"> -->
        <input id="address1" class="login_input" type="text" name="address1" required />
        <p class="help-block text-muted">Street address, P.O. box, company name, c/o</p>
      </div>
    </div>

    <div class="control-group">
      <!-- Password -->
      <label class="control-label"  for="address2">Address line 2</label>
      <div class="controls">
        <!-- <input type="password" id="password_confirm" name="password_confirm" placeholder="" class="input-xlarge"> -->
        <input id="address2" class="login_input" type="text" name="address2" />
        <p class="help-block text-muted">Apartment, suite, unit, building, floor, etc.</p>
      </div>
    </div>

    <div class="control-group">
      <!-- Password -->
      <label class="control-label"  for="city">City*</label>
      <div class="controls">
        <!-- <input type="password" id="password_confirm" name="password_confirm" placeholder="" class="input-xlarge"> -->
        <input id="city" class="login_input" type="text" name="city" required />
      </div>
    </div>

    <div class="control-group">
      <!-- Password -->
      <label class="control-label"  for="state">State*</label>
      <div class="controls">
        <!-- <input type="password" id="password_confirm" name="password_confirm" placeholder="" class="input-xlarge"> -->
        <input id="state" class="login_input" type="text" pattern="[a-zA-Z]{2,64}" name="state" required />
      </div>
    </div>

    <div class="control-group">
      <!-- Password -->
      <label class="control-label"  for="zip">ZIP Code*</label>
      <div class="controls">
        <!-- <input type="password" id="password_confirm" name="password_confirm" placeholder="" class="input-xlarge"> -->
        <input id="zip" class="login_input" type="number" pattern="[0-9]{1,20}" name="zip" required />
      </div>
    </div>

    <div class="control-group">
      <!-- Password -->
      <label class="control-label"  for="phone">Phone number*</label>
      <div class="controls">
        <!-- <input type="password" id="password_confirm" name="password_confirm" placeholder="" class="input-xlarge"> -->
        <input id="phone" class="login_input" type="text" name="phone" required />
        <p class="help-block text-muted">(xxx)xxx-xxxx</p>
      </div>
    </div>
 
    <div class="control-group">
      <!-- Button -->
      <p class="text-muted">*Required</p>
    </div>

    <div class="control-group">
      <!-- Button -->
      <div class="controls">
        <input class="btn btn-success" type="submit"  name="register_customer" value="Register" />
        <a class="btn btn-default" href="index.php">Go back</a>
      </div>
    </div>
  </fieldset>
</form>