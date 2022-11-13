<?php
  include_once 'header.php';
  require_once 'includes/dbh.inc.php';
  require_once 'includes/functions.inc.php';
  if ($_SESSION["userRole"] === 'patient') {
    $userID = $_SESSION["userID"];
    $userRole = $_SESSION["userRole"];
  }
  else {
    $userID = $_POST["userID"];
    $userRole = $_POST["userRole"];
  }
  if ($_POST["action"] === 'update') {
    switch ($userRole) {
      case 'patient':
        $sql2 = "SELECT * FROM Patient WHERE pat_user = ? AND deleted_flag = false;";
        $stmt2 = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt2, $sql2)) {
          header("location: viewmedrecord.php?error=getmedrecfailed");
          exit();
        }
        mysqli_stmt_bind_param($stmt2, 'i', $userID);
        mysqli_stmt_execute($stmt2);
        $result2 = mysqli_stmt_get_result($stmt2);
        $row2 = mysqli_fetch_assoc($result2);
        $sql3 = "SELECT * FROM Medical_Record WHERE pat_ID = ? AND deleted_flag = false;";
        $stmt3 = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt3, $sql3)) {
          header("location: viewmedrecord.php?error=getmedrecfailed");
          exit();
        }
        mysqli_stmt_bind_param($stmt3, 'i', $row2["patient_ID"]);
        mysqli_stmt_execute($stmt3);
        $result3 = mysqli_stmt_get_result($stmt3);
        $row3 = mysqli_fetch_assoc($result3);
        break;
      case 'doctor':
        $sql2 = "SELECT * FROM Doctor WHERE doc_user = ? AND deleted_flag = false;";
        $stmt2 = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt2, $sql2)) {
          header("location: viewmedrecord.php?error=getmedrecfailed");
          exit();
        }
        mysqli_stmt_bind_param($stmt2, 'i', $userID);
        mysqli_stmt_execute($stmt2);
        $result2 = mysqli_stmt_get_result($stmt2);
        $row2 = mysqli_fetch_assoc($result2);
        $roleID = $row2["doc_ID"];
        break;
      case 'nurse':
        $sql2 = "SELECT * FROM Nurse WHERE nurse_user = ? AND deleted_flag = false;";
        $stmt2 = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt2, $sql2)) {
          header("location: viewmedrecord.php?error=getmedrecfailed");
          exit();
        }
        mysqli_stmt_bind_param($stmt2, 'i', $userID);
        mysqli_stmt_execute($stmt2);
        $result2 = mysqli_stmt_get_result($stmt2);
        $row2 = mysqli_fetch_assoc($result2);
        $roleID = $row2["nurse_ID"];
        break;
      case 'receptionist':
        $sql2 = "SELECT * FROM Receptionist WHERE pat_user = ? AND deleted_flag = false;";
        $stmt2 = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt2, $sql2)) {
          header("location: viewmedrecord.php?error=getmedrecfailed");
          exit();
        }
        mysqli_stmt_bind_param($stmt2, 'i', $userID);
        mysqli_stmt_execute($stmt2);
        $result2 = mysqli_stmt_get_result($stmt2);
        $row2 = mysqli_fetch_assoc($result2);
        $roleID = $row2["rec_ID"];
        break;
    }
    $fname = $row2["f_name"];
    $mname = $row2["m_name"];
    $lname = $row2["l_name"];
    if ($_SESSION["userRole"] === 'admin') {
      $ssn = $row2["ssn"];
    }
    $sex = $row2["sex"];
    $addressID = $row2["address_ID"];
    $result4 = getAddress($conn, $addressID);
    $row4 = mysqli_fetch_assoc($result4);
    if (strval($row4["office_add"]) === '0') {
      $streetAdd = $row4["street_address"];
      $aptNum = $row4["apt_num"];
      $city = $row4["city"];
      $state = $row4["state"];
      $zip = $row4["zip_code"];
    }
    if ($userRole === 'patient') {
      $primDoc = $row2["prim_doc_ID"];
      $bdate = $row3["b_date"];
      switch ($row3["ethnicity"]) {
        case 'hl':
          $eth = 'Hispanic or Latino';
          break;
        case 'nhl':
          $eth = 'Not Hispanic or Latino';
          break;
      }
      switch ($row3["race"]) {
        case 'aian':
          $race = 'American Indian or Alaska Native';
          break;
        case 'a':
          $race = 'Asian';
          break;
        case 'baf':
          $race = 'Black or African American';
          break;
        case 'nhopi':
          $race = 'Native Hawaiian or Other Pacific Islander';
          break;
        case 'w':
          $race = 'White';
          break;
      }
    }
  }
?>
<h2>User Info</h2>
<div class='info-form'>
<form action='/medical-clinic/includes/updateinfo.inc.php' method='post'>
<input style='display: none;' type='text' name='userrole' value='<?php echo $userRole?>'/>
<input style='display: none;' type='text' name='userid' value='<?php echo $userID?>'/>
  <div class='form-element'>
    <label for='fname'>Enter First Name</label>
    <input type='text' name='fname' id='fname' value='<?php if (!is_null($row2)) echo $fname;?>'/>
  </div>
  <div class='form-element'>
    <label for='mname'>Enter Middle Name (Not Required)</label>
    <input type='text' name='mname' id='mname' value='<?php if (!is_null($row2)) echo $mname;?>'/>
  </div>
  <div class='form-element'>
    <label for='lname'>Enter Last Name</label>
    <input type='text' name='lname' id='lname' value='<?php if (!is_null($row2)) echo $lname;?>'/>
  </div>
  <div class='form-element'>
    <label for='street-add'>Enter Street Address</label>
    <input type='text' name='street-add' id='street-add' value='<?php if (!is_null($row4)) echo $streetAdd;?>'/>
  </div>
  <div class='form-element'>
    <label for='apt-num'>Enter Apt Num</label>
    <input type='text' name='apt-num' id='apt-num' value='<?php if (!is_null($row4)) echo $aptNum;?>'/>
  </div>
  <div class='form-element'>
    <label for='city'>Enter City</label>
    <input type='text' name='city' id='city' value='<?php if (!is_null($row4)) echo $city;?>'/>
  </div>
  <div class='form-element'>
    <label for='state'>Select a State</label>
    <select name='state' id='state'>
      <option value="AL" <?php if ('AL' === $state) echo 'selected'?>>Alabama</option>
      <option value="AK" <?php if ('AK' === $state) echo 'selected'?>>Alaska</option>
      <option value="AZ" <?php if ('AZ' === $state) echo 'selected'?>>Arizona</option>
      <option value="AR" <?php if ('AR' === $state) echo 'selected'?>>Arkansas</option>
      <option value="CA" <?php if ('CA' === $state) echo 'selected'?>>California</option>
      <option value="CO" <?php if ('CO' === $state) echo 'selected'?>>Colorado</option>
      <option value="CT" <?php if ('CT' === $state) echo 'selected'?>>Connecticut</option>
      <option value="DE" <?php if ('DE' === $state) echo 'selected'?>>Delaware</option>
      <option value="DC" <?php if ('DC' === $state) echo 'selected'?>>District Of Columbia</option>
      <option value="FL" <?php if ('FL' === $state) echo 'selected'?>>Florida</option>
      <option value="GA" <?php if ('GA' === $state) echo 'selected'?>>Georgia</option>
      <option value="HI" <?php if ('HI' === $state) echo 'selected'?>>Hawaii</option>
      <option value="ID" <?php if ('ID' === $state) echo 'selected'?>>Idaho</option>
      <option value="IL" <?php if ('IL' === $state) echo 'selected'?>>Illinois</option>
      <option value="IN" <?php if ('IN' === $state) echo 'selected'?>>Indiana</option>
      <option value="IA" <?php if ('IA' === $state) echo 'selected'?>>Iowa</option>
      <option value="KS" <?php if ('KS' === $state) echo 'selected'?>>Kansas</option>
      <option value="KY" <?php if ('KY' === $state) echo 'selected'?>>Kentucky</option>
      <option value="LA" <?php if ('LA' === $state) echo 'selected'?>>Louisiana</option>
      <option value="ME" <?php if ('ME' === $state) echo 'selected'?>>Maine</option>
      <option value="MD" <?php if ('MD' === $state) echo 'selected'?>>Maryland</option>
      <option value="MA" <?php if ('MA' === $state) echo 'selected'?>>Massachusetts</option>
      <option value="MI" <?php if ('MI' === $state) echo 'selected'?>>Michigan</option>
      <option value="MN" <?php if ('MN' === $state) echo 'selected'?>>Minnesota</option>
      <option value="MS" <?php if ('MS' === $state) echo 'selected'?>>Mississippi</option>
      <option value="MO" <?php if ('MO' === $state) echo 'selected'?>>Missouri</option>
      <option value="MT" <?php if ('MT' === $state) echo 'selected'?>>Montana</option>
      <option value="NE" <?php if ('NE' === $state) echo 'selected'?>>Nebraska</option>
      <option value="NV" <?php if ('NV' === $state) echo 'selected'?>>Nevada</option>
      <option value="NH" <?php if ('NH' === $state) echo 'selected'?>>New Hampshire</option>
      <option value="NJ" <?php if ('NJ' === $state) echo 'selected'?>>New Jersey</option>
      <option value="NM" <?php if ('NM' === $state) echo 'selected'?>>New Mexico</option>
      <option value="NY" <?php if ('NY' === $state) echo 'selected'?>>New York</option>
      <option value="NC" <?php if ('NC' === $state) echo 'selected'?>>North Carolina</option>
      <option value="ND" <?php if ('ND' === $state) echo 'selected'?>>North Dakota</option>
      <option value="OH" <?php if ('OH' === $state) echo 'selected'?>>Ohio</option>
      <option value="OK" <?php if ('OK' === $state) echo 'selected'?>>Oklahoma</option>
      <option value="OR" <?php if ('OR' === $state) echo 'selected'?>>Oregon</option>
      <option value="PA" <?php if ('PA' === $state) echo 'selected'?>>Pennsylvania</option>
      <option value="RI" <?php if ('RI' === $state) echo 'selected'?>>Rhode Island</option>
      <option value="SC" <?php if ('SC' === $state) echo 'selected'?>>South Carolina</option>
      <option value="SD" <?php if ('SD' === $state) echo 'selected'?>>South Dakota</option>
      <option value="TN" <?php if ('TN' === $state) echo 'selected'?>>Tennessee</option>
      <option value="TX" <?php if ('TX' === $state) echo 'selected'?>>Texas</option>
      <option value="UT" <?php if ('UT' === $state) echo 'selected'?>>Utah</option>
      <option value="VT" <?php if ('VT' === $state) echo 'selected'?>>Vermont</option>
      <option value="VA" <?php if ('VA' === $state) echo 'selected'?>>Virginia</option>
      <option value="WA" <?php if ('WA' === $state) echo 'selected'?>>Washington</option>
      <option value="WV" <?php if ('WV' === $state) echo 'selected'?>>West Virginia</option>
      <option value="WI" <?php if ('WI' === $state) echo 'selected'?>>Wisconsin</option>
      <option value="WY" <?php if ('WY' === $state) echo 'selected'?>>Wyoming</option>
    </select>
  </div>
  <div class='form-element'>
    <label for='zip'>Enter Zip Code</label>
    <input type='text' name='zip' id='zip' value='<?php if (!is_null($row4)) echo $zip;?>'/>
  </div>
  <?php
      if ($userRole === 'patient') {
        $result5 = getClinicAddressOfPrimaryDoctor($conn, $primDoc);
        $row5 = mysqli_fetch_assoc($result5);
        $clinic = $row5["address_ID"];
  ?>
  <div class='form-element'>
    <label for='clinic'>Preferred Clinic Location</label>
    <select name='clinic' id='clinic'>
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
    <option value='<?php echo $addID; ?>' <?php if ($addID === $clinic) echo 'selected'?>><?php echo $streetAdd.' '.$city.', '.$state.' '.$zip ?></option>
  <?php
    }
  ?>
    </select>
  </div>
  <?php
    }
    else if ($userRole === 'doctor' || $userRole === 'nurse') {
  ?>
   <div class='form-element'>
        <label for='clinic'>Preferred Clinic Location</label>
        <select name='clinic' id='clinic' required>
          <option>Select</option>
      <?php
        $result = viewClinicLocations($conn);
        $result2 = getPreferredClinic($conn, $userRole, $roleID);
        $clinic = mysqli_fetch_assoc($result2);
        while ($row = mysqli_fetch_assoc($result)) {
          $addID = $row["address_ID"];
          $streetAdd = $row["street_address"];
          $city = $row["city"];
          $state = $row["state"];
          $zip = $row["zip_code"];
      ?>
        <option value='<?php echo $addID; ?>'<?php if ( !is_null($clinic) && $clinic["office_ID"] === $addID) echo ' selected';?>><?php echo $streetAdd.' '.$city.', '.$state.' '.$zip ?></option>
      <?php
        }
      ?>
        </select>
      </div>
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
        <option value='<?php echo $depnum; ?>'<?php if ( !is_null($row2) && $row2["dep_num"] === $depnum) echo ' selected';?>><?php echo $depnum.': '.$depname ?></option>
      <?php
        }
      ?>
      </select>
    </div>
    <?php
      if ($userRole === 'doctor') {
    ?>
    <label for='credentials'>Credentials</label>
    <textarea style='display: block;' name='credentials' id='credentials' rows='4' cols='50'><?php if (!is_null($row2)) echo $row2["credentials"];?></textarea>
  </div>
  <?php
      }
    }
  ?>
  <div class='submit-btn'>
    <button type='submit' name='submit'>Submit</button>
  </div>
</form>
</div>
<?php
  include_once 'footer.php';
?>