<?php
  include_once 'header.php';
  require_once 'includes/dbh.inc.php';
  require_once 'includes/functions.inc.php';
  $patID = $_REQUEST["patID"];
?>
<h2>Emergency Contact</h2>
    <form action='/medical-clinic/includes/emergencycontact.inc.php' method='post'>
    <div class='form-element'>
        <input style='display: none;' type='text' name='patID' value='<?php echo $patID?>'/>
      </div>
    <div class='form-element'>
        <label for='fname'>Enter First Name</label>
        <input type='text' name='fname' id='fname' placeholder='First Name' required/>
      </div>
      <div class='form-element'>
        <label for='mname'>Enter Middle Name (Not Required)</label>
        <input type='text' name='mname' id='mname' placeholder='Middle Name'/>
      </div>
      <div class='form-element'>
        <label for='lname'>Enter Last Name</label>
        <input type='text' name='lname' id='lname' placeholder='Last Name' required/>
      </div>
      <div class='form-element'>
        <label for='relationship'>Select Relationship</label>
        <select name='relationship' id='relationship' required>
          <option>Select</option>
          <option value='parent'>Parent</option>
          <option value='spouse'>Spouse</option>
          <option value='other'>Other</option>
        </select>
      </div>
      <div class='form-element'>
        <label for='phonenum'>Enter Phone Number</label>
        <input type='text' name='phonenum' id='phonenum' placeholder='Phone Number' required/>
      </div>
      <div class='form-element'>
        <label for='sex'>Select Gender</label>
        <select name='sex' id='sex' required>
          <option value='male'>Male</option>
          <option value='female'>Female</option>
        </select>
      </div>
      <div class='submit-btn'>
        <button type='submit' name='submit'>Sign Up</button>
      </div>
    </form>
<?php
        if ($_REQUEST["status"] === 'success') {
          echo '<script>alert("Patient Info Submitted")</script>';
        }
  include_once 'footer.php';
?>