<?php
  include_once 'header.php';
  require_once 'includes/dbh.inc.php';
  require_once 'includes/functions.inc.php';
    if (isset($_SESSION["userRole"])) {
        if (isset($_POST["userID"])) {
          $userID = $_POST["userID"];
          $userRole = $_POST["userRole"];
        }
        $result = getMedicalRecordFromUserID($conn, $userID);
        $row = mysqli_fetch_assoc($result);
        $result2 = getUserInfo($conn, $userID, $userRole);
        $row2 = mysqli_fetch_assoc($result2);
        $fname = $row2["f_name"];
        $mname = $row2["m_name"];
        $lname = $row2["l_name"];
        if ($_SESSION["userRole"] === 'admin') {
          $ssn = $row2["ssn"];
        }
        $sex = $row2["sex"];
        $addressID = $row2["address_ID"];
        $result3 = getAddress($conn, $addressID);
        $row3 = mysqli_fetch_assoc($result3);
        if (strval($row3["office_add"]) === '0') {
          $streetAdd = $row3["street_address"];
          $aptNum = $row3["apt_num"];
          $city = $row3["city"];
          $state = $row3["state"];
          $zip = $row3["zip_code"];
        }
        if ($userRole === 'patient') {
          // Get demographic info from Medical Record
          $bdate = $row["b_date"];
          switch ($row["ethnicity"]) {
            case 'hl':
              $eth = 'Hispanic or Latino';
              break;
            case 'nhl':
              $eth = 'Not Hispanic or Latino';
              break;
          }
          switch ($row["race"]) {
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
        else if ($userRole === 'doctor') {
          $depnum = $row2["dep_num"];
          $result4 = getDepartmentName($conn, $depnum);
          $row4 = mysqli_fetch_assoc($result4);
          $depname = $row4["dep_name"];
          $cred = $row2["credentials"];
        }
        else if ($userRole === 'nurse') {
          $depnum = $row2["dep_num"];
          $result4 = getDepartmentName($conn, $depnum);
          $row4 = mysqli_fetch_assoc($result4);
          $depname = $row4["dep_name"];
          $reg = $row2["registered"];
        }
  ?>
  <div>
    <h2>User Info</h2>
    <h3>First Name</h3>
    <p><?php echo $fname?></p>
    <h3>Middle Name</h3>
    <p><?php echo $mname?></p>
    <h3>Last Name</h3>
    <p><?php echo $lname?></p>
    <?php
      if ($_SESSION["userRole"] === 'admin') {
    ?>
        <h3>SSN</h3>
        <p><?php echo $ssn?></p>
    <?php
      }
    ?>
    <h3>Gender</h3>
    <p><?php echo $sex?></p>
    <?php
      if ($userRole === 'patient') {
    ?>
        <h3>Birth Date</h3>
        <p><?php echo $bdate?></p>
        <h3>Ethnicity</h3>
        <p><?php echo $eth?></p>
        <h3>Race</h3>
        <p><?php echo $race?></p>
    <?php
      }
      else if ($userRole === 'doctor') {
    ?>
        <h3>Department Name</h3>
        <p><?php echo $depname?></p>
        <h3>Credentials</h3>
        <p><?php echo $cred?></p>
    <?php
      }
      else if ($userRole === 'nurse') {
    ?>
        <h3>Department Name</h3>
        <p><?php echo $depname?></p>
        <h3>Registered?</h3>
        <p><?php
            if (strval($reg) === '0')
              echo 'No';
            else
              echo 'Yes';
            ?>
        </p>
    <?php
      }
    ?>
    <h3>Street Address</h3>
    <p><?php echo $streetAdd?></p>
    <h3>Apartment Number</h3>
    <p><?php echo $aptNum?></p>
    <h3>City</h3>
    <p><?php echo $city?></p>
    <h3>State</h3>
    <p><?php echo $state?></p>
    <h3>Zip Code</h3>
    <p><?php echo $zip?></p>
    <form action='/medical-clinic/updateinfo.php' method='post'>
          <div class='form-element'>
            <input style='display: none;' name='userID' value='<?php echo $userID?>'/>
          </div>
          <div class='form-element'>
            <input style='display: none;' name='userRole' value='<?php echo $userRole?>'/>
          </div>
          <div class='form-element'>
            <input style='display: none;' name='action' value='update'/>
          </div>
          <div class='submit-btn'>
            <button type='submit' name='submit'>Update Info</button>
          </div>
        </form>
  <?php
    }
    else {
      header("location: index.php");
      exit();
    }
  include_once 'footer.php';
?>