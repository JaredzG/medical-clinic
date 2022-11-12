<?php
  include_once 'header.php';
  session_start();
  require_once 'includes/dbh.inc.php';
  require_once 'includes/functions.inc.php';
  if ($_SESSION["newuserRole"] === 'admin') {
    header("location: index.php");
    exit();
  }
?>
<div class='emp-info-form'>
  <h2>Enter Employee Information</h2>
  <form action='/medical-clinic/includes/empinfo.inc.php' method='post'>
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
        <label for='ssn'>Enter SSN</label>
        <input type='text' name='ssn' id='ssn' placeholder='SSN' required/>
      </div>
      <div class='form-element'>
        <label for='sex'>Select Gender</label>
        <select name='sex' id='sex' required>
          <option value='male'>Male</option>
          <option value='female'>Female</option>
        </select>
      </div>
      <div class='form-element'>
        <label for='street-add'>Enter Street Address</label>
        <input type='text' name='street-add' id='street-add' placeholder='100 Main St' required/>
      </div>
      <div class='form-element'>
        <label for='apt-num'>Enter Apt Num</label>
        <input type='text' name='apt-num' id='apt-num' placeholder='123'/>
      </div>
      <div class='form-element'>
        <label for='city'>Enter City</label>
        <input type='text' name='city' id='city' placeholder='Houston'/>
      </div>
      <div class='form-element'>
        <label for='state'>Select State</label>
        <select name='state' id='state' required>
          <option value="AL">Alabama</option>
          <option value="AK">Alaska</option>
          <option value="AZ">Arizona</option>
          <option value="AR">Arkansas</option>
          <option value="CA">California</option>
          <option value="CO">Colorado</option>
          <option value="CT">Connecticut</option>
          <option value="DE">Delaware</option>
          <option value="DC">District Of Columbia</option>
          <option value="FL">Florida</option>
          <option value="GA">Georgia</option>
          <option value="HI">Hawaii</option>
          <option value="ID">Idaho</option>
          <option value="IL">Illinois</option>
          <option value="IN">Indiana</option>
          <option value="IA">Iowa</option>
          <option value="KS">Kansas</option>
          <option value="KY">Kentucky</option>
          <option value="LA">Louisiana</option>
          <option value="ME">Maine</option>
          <option value="MD">Maryland</option>
          <option value="MA">Massachusetts</option>
          <option value="MI">Michigan</option>
          <option value="MN">Minnesota</option>
          <option value="MS">Mississippi</option>
          <option value="MO">Missouri</option>
          <option value="MT">Montana</option>
          <option value="NE">Nebraska</option>
          <option value="NV">Nevada</option>
          <option value="NH">New Hampshire</option>
          <option value="NJ">New Jersey</option>
          <option value="NM">New Mexico</option>
          <option value="NY">New York</option>
          <option value="NC">North Carolina</option>
          <option value="ND">North Dakota</option>
          <option value="OH">Ohio</option>
          <option value="OK">Oklahoma</option>
          <option value="OR">Oregon</option>
          <option value="PA">Pennsylvania</option>
          <option value="RI">Rhode Island</option>
          <option value="SC">South Carolina</option>
          <option value="SD">South Dakota</option>
          <option value="TN">Tennessee</option>
          <option value="TX">Texas</option>
          <option value="UT">Utah</option>
          <option value="VT">Vermont</option>
          <option value="VA">Virginia</option>
          <option value="WA">Washington</option>
          <option value="WV">West Virginia</option>
          <option value="WI">Wisconsin</option>
          <option value="WY">Wyoming</option>
        </select>
      </div>
      <div class='form-element'>
        <label for='zip'>Enter Zip Code</label>
        <input type='text' name='zip' id='zip' placeholder='12345' required/>
      </div>
      <?php
        if ($_SESSION["newuserRole"] === "doctor") {
      ?>
        <div class='form-element'>
        <label for='clinic'>Preferred Clinic Location</label>
        <select name='clinic' id='clinic' required>
          <option>Select</option>
      <?php
        $result = viewClinicLocations($conn);
        while ($row = mysqli_fetch_assoc($result)) {
          $addID = $row["address_ID"];
          $streetAdd = $row["street_address"];
          $city = $row["city"];
          $state = $row["state"];
          $zip = $row["zip_code"];
      ?>
        <option value='<?php echo $addID; ?>'><?php echo $streetAdd.' '.$city.', '.$state.' '.$zip ?></option>
      <?php
        }
      ?>
        </select>
      </div>
    <div class='form-element'>
    <label for='num'>Select a Department</label>
      <select name='num' id='num' required>
        <option>Select</option>
      <?php
        require_once 'includes/dbh.inc.php';
        require_once 'includes/functions.inc.php';
        $result = viewDepartments($conn);
        while ($row = mysqli_fetch_assoc($result)) {
          $depnum = $row["department_number"];
          $depname = $row["dep_name"];
      ?>
        <option value='<?php echo $depnum; ?>'><?php echo $depnum.':   '.$depname ?></option>
      <?php
        }
      ?>
      </select>
    </div>
    <div class='form-element'>
      <label for='credentials'>Enter Credentials</label>
      <input type='text' name='credentials' id='credentials'/>
    </div>
      <?php
        }
        else if ($_SESSION["newuserRole"] === "nurse") {
      ?>
   <div class='form-element'>
    <label for='num'>Select a Department</label>
      <select name='num' id='num'>
        <option>Select</option>
      <?php
        $result = viewDepartments($conn);
        while ($row = mysqli_fetch_assoc($result)) {
          $depnum = $row["department_number"];
          $depname = $row["dep_name"];
      ?>
        <option value='<?php echo $depnum; ?>'><?php echo $depnum.':   '.$depname ?></option>
      <?php
        }
      ?>
      </select>
    </div>
        <div class='form-element'>
          <label for='registered'>Registered</label>
          <select id='registered' name='registered'>
            <option>Select</option>
            <option value='0'>No</option>
            <option value='1'>Yes</option>
          </select>
        </div>
      <?php
        }
      ?>
      <div class='submit-btn'>
        <button type='submit' name='submit'>Sign Up</button>
      </div>
    </form>
    <?php
      if (isset($_GET["error"])) {
        if ($_GET["error"] === "emptyinput") {
          echo "<p>Fill in all fields.</p>";
        }
        if ($_GET["error"] === "invalidssn") {
          echo "<p>Enter a proper SSN.</p>";
        }
        if ($_GET["error"] === "invalidzip") {
          echo "<p>Enter a proper zip code.</p>";
        }
      }
    ?>
</div> 
<?php
  include_once 'footer.php';
?>