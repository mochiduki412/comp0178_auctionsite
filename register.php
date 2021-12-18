<?php include_once("header.php") ?>
<?php include_once("includes/page_utils.php") ?>

<div class="container">
  <h2 class="my-3">Register new account</h2>

  <!-- Create auction form -->
  <form method="POST" action="process_registration.php">
    <!-- accountType -->
    <div class="form-group row">
      <label for="accountType" class="col-sm-2 col-form-label text-right">Registering as a:</label>
      <div class="col-sm-10">
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="accountType" id="accountBuyer" value="buyer" checked>
          <label class="form-check-label" for="accountBuyer">Buyer</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="accountType" id="accountSeller" value="seller">
          <label class="form-check-label" for="accountSeller">Seller</label>
        </div>
        <small id="accountTypeHelp" class="form-text-inline text-muted"><span class="text-danger">* Required.</span></small>
      </div>
    </div>

    <!-- firstName -->
    <div class="form-group row">
      <label for="firstName" class="col-sm-2 col-form-label text-right">First Name</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name">
      </div>
    </div>

    <!-- lastName -->
    <div class="form-group row">
      <label for="lastName" class="col-sm-2 col-form-label text-right">Last Name</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name">
      </div>
    </div>

    <!-- email -->
    <div class="form-group row">
      <label for="email" class="col-sm-2 col-form-label text-right">Email</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="email" name="email" placeholder="Email">
        <small id="emailHelp" class="form-text text-muted">
          <span class="text-danger">* Required.</span>
        </small>
      </div>
    </div>

    <!-- password -->
    <div class="form-group row">
      <label for="password" class="col-sm-2 col-form-label text-right">Password</label>
      <div class="col-sm-10">
        <input type="password" class="form-control" id="password" name='password' placeholder="Password">
        <button type="button" class="m-1" onclick="if (password.type == 'text') password.type = 'password'; else password.type = 'text';">Toggle</button>
        <small id="passwordHelp" class="form-text text-muted">
          <span class="text-danger">* Required.</span>
          <span class="text-danger">
            Password must have at least 1 uppercase character,1 lowercase character, 1 number and 1 special character (such as @!#).
          </span>
        </small>
      </div>
    </div>

    <!-- confirm password -->
    <div class="form-group row">
      <label for="passwordConfirm" class="col-sm-2 col-form-label text-right">Repeat password</label>
      <div class="col-sm-10">
        <input type="password" class="form-control" id="passwordConfirm" name='passwordConfirm' placeholder="Enter password again">
        <button type="button" class="m-1" onclick="if (passwordConfirm.type == 'text') passwordConfirm.type = 'password'; else passwordConfirm.type = 'text';">Toggle</button>
        <small id="passwordConfirmationHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
      </div>
    </div>

    <!-- submit button -->
    <div class="form-group row">
      <button type="submit" class="btn btn-primary form-control">Register</button>
    </div>
  </form>

  <div class="text-center">Already have an account? <a href="" data-toggle="modal" data-target="#loginModal">Login</a>

  </div>

  <?php include_once("footer.php") ?>