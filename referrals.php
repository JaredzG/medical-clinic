<?php
  include_once 'header.php';
  require_once 'includes/dbh.inc.php';
  require_once 'includes/functions.inc.php';
  if ($_SESSION["userRole"] !== 'doctor' && $_SESSION["userRole"] !== 'receptionist' && $_SESSION["userRole"] !== 'admin') {
    header("location: index.php?error=notauthorized");
    exit();
  }
  if ($_SESSION["userRole"] === 'doctor') {
  $sql = "SELECT doc_ID FROM Doctor WHERE doc_user = ? AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: referrals.php?error=getdocidfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "i", $_SESSION["userID"]);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  $sessionDoctorID = $row["doc_ID"];
  $pats = getDocPatients($conn, $sessionDoctorID);
  $specs = getAllSpecialists($conn);
?>
<h2>Make a Referral</h2>
<div class='referral-form'>
  <form action='/medical-clinic/includes/referrals.inc.php' method='post'>
    <div class='form-element'>
      <input style='display: none;' name='doctor' value='<?php echo $row["doc_ID"]?>'/>
    </div>
    <div class='form-element'>
      <label for='patient'>Choose a Patient</label>
      <select name='patient' id='patient'>
        <option>Select</option>
      <?php
        while ($row = mysqli_fetch_assoc($pats)) {
          $patID = $row["patient_ID"];
          $patFName = $row["f_name"];
          $patLName = $row["l_name"];
      ?>
      <option value='<?php echo $patID?>'><?php echo $patFName.' '.$patLName?></option>
      <?php
        }
      ?>
      </select>
    </div>
    <div class='form-element'>
      <label for='specialist'>Choose a Specialist</label>
      <select name='specialist' id='specialist'>
        <option>Select</option>
      <?php
        while ($row = mysqli_fetch_assoc($specs)) {
          $docID = $row["doc_ID"];
          $docFName = $row["f_name"];
          $docLName = $row["l_name"];
      ?>
      <option value='<?php echo $docID?>'><?php echo $docFName.' '.$docLName?></option>
      <?php
        }
      ?>
      </select>
    </div>
    <div class='submit-btn'>
      <button type='submit' name='submit'>Make Referral</button>
    </div>
  </form>
</div>
<?php
  }  
  if ($_SESSION["userRole"] === 'doctor') {
    $result2 = getActiveDocReferrals($conn, $sessionDoctorID);
    $result3 = getInactiveDocReferrals($conn, $sessionDoctorID);
  }
  else if ($_SESSION["userRole"] === 'receptionist' || $_SESSION["userRole"] === 'admin') {
    $result2 = getActiveReferrals($conn);
    $result3 = getInactiveReferrals($conn);
  }
?>
<div class='referrals'>
  <h2>Active Referrals</h2>
  <table class="table-template">
    <thead>
      <tr>
        <?php
          if ($_SESSION["userRole"] === 'receptionist' || $_SESSION["userRole"] === 'admin') {
        ?>
        <th>Primary Doctor Name</th>
        <?php
          }
        ?>
        <th>Patient</th>
        <th>Specialist</th>
      </tr>
    </thead>
    <tbody>
      <?php
        while ($row2 = mysqli_fetch_assoc($result2)) {
          $refPrimaryID = intval($row2["primary_ID"]);
          $refPatID = intval($row2["pat_ID"]);
          $refSpecID = intval($row2["specialist_ID"]);
          if ($_SESSION["userRole"] === 'receptionist' || $_SESSION["userRole"] === 'admin') {
            $primDocName = getDocNameFromDocID($conn, $refPrimaryID);
            $primDocNameRow = mysqli_fetch_assoc($primDocName);
          }
          $patientName = getPatientNameFromPatientID($conn, $refPatID);
          $patientNameRow = mysqli_fetch_assoc($patientName);
          $specDocName = getDocNameFromDocID($conn, $refSpecID);
          $specDocNameRow = mysqli_fetch_assoc($specDocName);
          $depName = getDepartmentName($conn, intval($specDocNameRow["dep_num"]));
          $depNameRow = mysqli_fetch_assoc($depName);
          ?>
      <tr>
        <?php
        if ($_SESSION["userRole"] === 'receptionist' || $_SESSION["userRole"] === 'admin') {
        ?>
        <td><?php echo $primDocNameRow["f_name"].' '.$primDocNameRow["l_name"]?></td>
        <?php
          }
        ?>
        <td><?php echo $patientNameRow["f_name"].' '.$patientNameRow["l_name"]?></td>
        <td><?php echo $specDocNameRow["f_name"].' '.$specDocNameRow["l_name"].' ('.$depNameRow["dep_name"].')'?></td>
      </tr>
      <?php
        }
      ?>
    </tbody>
  </table>
</div>
<div class='referrals'>
  <h2>Inactive Referrals</h2>
  <table class="table-template">
    <thead>
      <tr>
        <?php
          if ($_SESSION["userRole"] === 'receptionist' || $_SESSION["userRole"] === 'admin') {
        ?>
        <th>Primary Doctor Name</th>
        <?php
          }
        ?>
        <th>Patient</th>
        <th>Specialist</th>
      </tr>
    </thead>
    <tbody>
      <?php
        while ($row3 = mysqli_fetch_assoc($result3)) {
          $refPrimaryID = intval($row3["primary_ID"]);
          $refPatID = intval($row3["pat_ID"]);
          $refSpecID = intval($row3["specialist_ID"]);
          if ($_SESSION["userRole"] === 'receptionist' || $_SESSION["userRole"] === 'admin') {
            $primDocName = getDocNameFromDocID($conn, $refPrimaryID);
            $primDocNameRow = mysqli_fetch_assoc($primDocName);
          }
          $patientName = getPatientNameFromPatientID($conn, $refPatID);
          $patientNameRow = mysqli_fetch_assoc($patientName);
          $specDocName = getDocNameFromDocID($conn, $refSpecID);
          $specDocNameRow = mysqli_fetch_assoc($specDocName);
          $depName = getDepartmentName($conn, intval($specDocNameRow["dep_num"]));
          $depNameRow = mysqli_fetch_assoc($depName);
          ?>
      <tr>
        <?php
        if ($_SESSION["userRole"] === 'receptionist' || $_SESSION["userRole"] === 'admin') {
        ?>
        <td><?php echo $primDocNameRow["f_name"].' '.$primDocNameRow["l_name"]?></td>
        <?php
          }
        ?>
        <td><?php echo $patientNameRow["f_name"].' '.$patientNameRow["l_name"]?></td>
        <td><?php echo $specDocNameRow["f_name"].' '.$specDocNameRow["l_name"].' ('.$depNameRow["dep_name"].')'?></td>
      </tr>
      <?php
        }
      ?>
    </tbody>
  </table>
</div>
<?php
  include_once 'footer.php';
?>