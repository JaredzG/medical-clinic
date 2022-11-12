<?php
  include_once 'header.php';
?>
  <div class='login-form'>
    <h2>Log In</h2>
    <form action='/medical-clinic/includes/login.inc.php' method='post'>
      <div class='form-element'>
        <label for='username'>Enter Username or Email</label>
        <input type='text' name='username' id='username' placeholder='Username or Email' required/>
      </div>
      <div class='form-element'>
        <label for='password'>Enter Password</label>
        <input type='password' name='password' id='password' placeholder='Password' required/>
      </div>
      <div class='submit-btn'>
        <button type='submit' name='submit'>Log In</button>
      </div>
    </form>
    <?php
      if (isset($_GET["error"])) {
        if ($_GET["error"] === "emptyinput") {
          echo "<p>Fill in all fields.</p>";
        }
        else if ($_GET["error"] === "wronglogin") {
          echo "<p>Wrong login information. Try again.</p>";
        }
        else if ($_GET["error"] === "stmtfailed") {
          echo "<p>Something went wrong. Try again.</p>";
        }
      }
    ?>
  </div>
<?php
  include_once 'footer.php';
?>