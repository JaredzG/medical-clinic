<?php
  include_once 'header.php';
?>
<form action='/medical-clinic/viewinfo.php' method='post'>
  <div class='form-element'>
    <input style='display: none;' name='userID' value='<?php echo $_SESSION["userID"]?>'/>
  </div>
  <div class='form-element'>
    <input style='display: none;' name='userRole' value='<?php echo $_SESSION["userRole"]?>'/>
  </div>
  <div class='submit-btn'>
    <button type='submit' name='submit'>View Info</button>
  </div>
</form>
<?php
  if ($_SESSION["userRole"] === 'patient') {
?>
<form action='/medical-clinic/delete.php' method='post'>
  <div class='form-element'>
    <input style='display: none;' name='userID' value='<?php echo 
    $_SESSION["userID"]?>'/>
  </div>
  <div class='form-element'>
    <input style='display: none;' name='userRole' value='<?php echo $_SESSION["userRole"]?>'/>
  </div>
  <div class='form-element'>
    <input style='display: none;' name='otherID' value='none'/>
  </div>
  <div class='form-element'>
    <input style='display: none;' name='otherRole' value='none'/>
  </div>
  <div class='form-element'>
    <input style='display: none;' name='senderRole' value='patient'/>
  </div>
  <div class='submit-btn'>
    <button type='submit' name='submit'>Delete Account</button>
  </div>
</form>
<?php
  }
  include_once 'footer.php';
?>