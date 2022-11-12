<?php
  include_once 'header.php';
?>
  <div class='signup-form'>
    <h2>Sign Up</h2>
    <form action='/medical-clinic/includes/signup.inc.php' method='post'>
      <div class='form-element'>
        <label for='email'>Enter Email</label>
        <input type='email' name='email' id='email' placeholder='Email' required/>
      </div>
      <div class='form-element'>
        <label for='phonenum'>Enter Phone Number</label>
        <input type='text' name='phonenum' id='phonenum' placeholder='Phone Number' required/>
      </div>
      <div class='form-element'>
        <label for='username'>Enter Username</label>
        <input type='text' name='username' id='username' placeholder='Username' required/>
      </div>
      <div class='form-element'>
        <label for='password'>Enter Password</label>
        <input type='password' name='password' id='password' placeholder='Password' required/>
      </div>
      <div class='form-element'>
        <label for='passwordrepeat'>Repeat Password</label>
        <input type='password' name='passwordrepeat' id='passwordrepeat' placeholder='Password' required/>
      </div>
      <div class='submit-btn'>
        <button type='submit' name='submit'>Sign Up</button>
      </div>
    </form>
    <?php
      if (isset($_GET["error"])) {
        if ($_GET["error"] === "emptyinput") {
          echo "<p>Fill in all fields.</p>";
        }
        if ($_GET["error"] === "invalidemail") {
          echo "<p>Choose a proper email.</p>";
        }
        if ($_GET["error"] === "invalidphone") {
          echo "<p>Choose a proper phone number.</p>";
        }
        if ($_GET["error"] === "invaliduser") {
          echo "<p>Choose a proper username.</p>";
        }
        if ($_GET["error"] === "pwdnomatch") {
          echo "<p>Passwords do not match. Try again.</p>";
        }
        if ($_GET["error"] === "userexists") {
          echo "<p>Username or Email already taken.</p>";
        }
        if ($_GET["error"] === "stmtfailed") {
          echo "<p>Something went wrong. Try again.</p>";
        }
        if ($_GET["error"] === "none") {
          echo "<p>Success.</p>";
        }
      }
    ?>
  </div>

  
<?php
  include_once 'footer.php';
?>