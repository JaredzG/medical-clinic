<?php
  include_once 'header.php';
?>
<div class='view-app-form'>
  <h2>View Appointments</h2>
  <form action='/medical-clinic/viewappointments.php' method='post'>
    <div class='form-element'>
      <label for='mindate'>Select a Minimum Date</label>
      <input type='datetime-local' name='mindate' id='mindate' value='2022-12-01T00:00'  min='2022-1-01T00:00' max='2023-01-01T00:00' required/>
    </div>
    <div class='form-element'>
      <label for='maxdate'>Select a Maximum Date</label>
      <input type='datetime-local' name='maxdate' id='maxdate' value='2022-12-01T00:00' min='2022-1-01T00:00' max='2023-01-01T00:00' required/>
    </div>
    <div class='submit-btn'>
      <button type='submit' name='submit'>Submit</button>
    </div>
  </form>
</div>
<?php
  require_once "includes/dbh.inc.php";
  require_once "includes/functions.inc.php";
  if (isset($_POST["submit"]) || ($_REQUEST["mindate"] && $_REQUEST["maxdate"])) {
    if (isset($_POST["submit"])) {
      $mindate = $_POST["mindate"];
      $maxdate = $_POST["maxdate"];
    }
    else {
      $mindate = $_REQUEST["mindate"];
      $maxdate = $_REQUEST["maxdate"];
    }
    if ($_SESSION["userRole"] === 'patient') {
      $patID = getPatientID($conn, $_SESSION["username"]);
      $result1 = viewPatientScheduledApps($conn, $patID, $mindate, $maxdate);
      $result2 = viewPatientApprovedApps($conn, $patID, $mindate, $maxdate);
      $result3 = viewPatientUnpaidAppointments($conn, $patID, $mindate, $maxdate);
      $result4 = viewPatientPaidAppointments($conn, $patID, $mindate, $maxdate);
      $result5 = viewPatientCancelledApps($conn, $patID, $mindate, $maxdate);
?>
<div>
  <h2>Scheduled Appointments</h2>
  <table class="table-template">
    <thead>
      <tr>
        <th>Date and Time</th>
        <th>Reason</th>
      </tr>
    </thead>
    <tbody>
    <?php
      while ($row = mysqli_fetch_assoc($result1)) {
        $dateTime = $row["date_time"];
        $reason = $row["reason"];
    ?>
        <tr>
          <td><?php echo $dateTime?></td>
          <td><?php echo $reason?></td>
        </tr>
    <?php
      }
    ?>
    </tbody>
    </table>
</div>
<div>
<div>
  <h2>Approved Appointments</h2>
  <table class="table-template">
    <thead>
      <tr>
        <th>Date and Time</th>
        <th>Reason</th>
      </tr>
    </thead>
    <tbody>
    <?php
      while ($row = mysqli_fetch_assoc($result2)) {
        $dateTime = $row["date_time"];
        $reason = $row["reason"];
    ?>
        <tr>
          <td><?php echo $dateTime?></td>
          <td><?php echo $reason?></td>
        </tr>
    <?php
      }
    ?>
    </tbody>
    </table>
</div>
<div>
  <h2>Unpaid Appointments</h2>
  <table class="table-template">
    <thead>
    <tr>
      <th>Date and Time</th>
      <th>Reason</th>
    </tr>
    </thead>
    <tbody>
    <?php
      while ($row = mysqli_fetch_assoc($result3)) {
        $dateTime = $row["date_time"];
        $reason = $row["reason"];
        ?>
        <tr>
          <td><?php echo $dateTime?></td>
          <td><?php echo $reason?></td>
        </tr>
        <?php
      }
      ?>
      </tbody>
    </table>
  </div>
  <div>
    <h2>Paid Appointments</h2>
    <table class="table-template">
      <thead>
      <tr>
        <th>Date and Time</th>
        <th>Reason</th>
      </tr>
      </thead>
      <tbody>
      <?php
      while ($row = mysqli_fetch_assoc($result4)) {
        $dateTime = $row["date_time"];
        $reason = $row["reason"];
        ?>
        <tr>
          <td><?php echo $dateTime?></td>
          <td><?php echo $reason?></td>
        </tr>
        <?php
      }
      ?>
      </tbody>
    </table>
  </div>
  <div>
    <h2>Cancelled Appointments</h2>
    <table class="table-template">
      <thead>
        <tr>
          <th>Date and Time</th>
          <th>Reason</th>
        </tr>
      </thead>
      <tbody>
      <?php
        while ($row = mysqli_fetch_assoc($result5)) {
          $dateTime = $row["date_time"];
          $reason = $row["reason"];
      ?>
          <tr>
            <td><?php echo $dateTime?></td>
            <td><?php echo $reason?></td>
          </tr>
      <?php
        }
      ?>
      </tbody>
      </table>
  </div>
  <div>
  <?php
    }
    else if ($_SESSION["userRole"] === 'doctor') {
      $doccomapps = getDoctorCompletedApps($conn, $mindate, $maxdate);
      $docincapps = getDoctorIncompleteApps($conn, $mindate, $maxdate);
?>
<div>
  <h2>Completed Appointments</h2>
  <table class="table-template">
    <thead>
      <tr>
        <th>Patient ID</th>
        <th>Date and Time</th>
        <th>Reason</th>
      </tr>
    </thead>
    <tbody>
      <?php
        while ($row = mysqli_fetch_assoc($doccomapps)) {
          $patID = $row["patient_ID"];
          $dateTime = $row["date_time"];
          $reason = $row["reason"];
          ?>
          <tr>
            <td><?php echo $patID?></td>
            <td><?php echo $dateTime?></td>
            <td><?php echo $reason?></td>
          </tr>
          <?php
        }
        ?>
    </tbody>
    </table>
  </div>
  <div>
    <h2>Incomplete Appointments</h2>
    <table class="table-template">
      <thead>
        <tr>
          <th>Patient ID</th>
          <th>Date and Time</th>
          <th>Reason</th>
        </tr>
      </thead>
      <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($docincapps)) {
          $patID = $row["patient_ID"];
          $dateTime = $row["date_time"];
          $reason = $row["reason"];
          ?>
          <tr>
            <td><?php echo $patID?></td>
            <td><?php echo $dateTime?></td>
            <td><?php echo $reason?></td>
          </tr>
      <?php
        }
      ?>
      </tbody>
    </table>
</div>
<?php
    }
    else if ($_SESSION["userRole"] === 'nurse') {
      $nursecomapps = getNurseCompletedApps($conn, $mindate, $maxdate);
      $nurseincapps = getNurseIncompleteApps($conn, $mindate, $maxdate);
?>
<div>
  <h2>Completed Appointments</h2>
  <table class="table-template">
    <thead>
      <tr>
        <th>Patient ID</th>
        <th>Date and Time</th>
        <th>Reason</th>
      </tr>
    </thead>
    <tbody>
    <?php
      while ($row = mysqli_fetch_assoc($nursecomapps)) {
        $patID = $row["patient_ID"];
        $dateTime = $row["date_time"];
        $reason = $row["reason"];
        ?>
        <tr>
          <td><?php echo $patID?></td>
          <td><?php echo $dateTime?></td>
          <td><?php echo $reason?></td>
        </tr>
        <?php
      }
      ?>
    </tbody>
    </table>
  </div>
  <div>
    <h2>Incomplete Appointments</h2>
    <table class="table-template">
      <thead>
        <tr>
          <th>Patient ID</th>
          <th>Date and Time</th>
          <th>Reason</th>
        </tr>
      </thead>
      <tbody>
      <?php
      while ($row = mysqli_fetch_assoc($nurseincapps)) {
        $patID = $row["patient_ID"];
        $dateTime = $row["date_time"];
        $reason = $row["reason"];
        ?>
        <tr>
          <td><?php echo $patID?></td>
          <td><?php echo $dateTime?></td>
          <td><?php echo $reason?></td>
        </tr>
    <?php
      }
    ?>
      </tbody>
    </table>
</div>
<?php
    }
    else if ($_SESSION["userRole"] === 'receptionist' || $_SESSION["userRole"] === 'admin'){
      $scheduled = getScheduledApps($conn, $mindate, $maxdate);
      $approved = getApprovedApps($conn, $mindate, $maxdate);
      $completed = getCompletedApps($conn, $mindate, $maxdate);
      $cancelled = getCancelledApps($conn, $mindate, $maxdate);
?>
<div>
  <h2>Scheduled Appointments</h2>
  <table class="table-template">
    <thead>
      <tr>
        <th>Appointment ID</th>
        <th>Patient ID</th>
        <th>Date and Time</th>
        <th>Reason</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php
      while ($row = mysqli_fetch_assoc($scheduled)) {
        debug_to_console($row);
        $appID = $row["app_ID"];
        $patID = $row["patient_ID"];
        $dateTime = $row["date_time"];
        $reason = $row["reason"];
    ?>
        <tr>
          <td><?php echo $appID?></td>
          <td><?php echo $patID?></td>
          <td><?php echo $dateTime?></td>
          <td><?php echo $reason?></td>
          <td>
            <a href='includes/updateapp.inc.php?id=<?php echo $row["app_ID"]?>&mindate=<?php echo $mindate?>&maxdate=<?php echo $maxdate?>&status=approve'>Approve</a>
            <a href='includes/updateapp.inc.php?id=<?php echo $row["app_ID"]?>&mindate=<?php echo $mindate?>&maxdate=<?php echo $maxdate?>&status=cancel'>Cancel</a>
          </td>
        </tr>
    <?php
      }
    ?>
    </tbody>
    </table>
</div>
<div>
  <h2>Approved Appointments</h2>
  <table class="table-template">
    <thead>
    <tr>
      <th>Appointment ID</th>
      <th>Patient ID</th>
      <th>Date and Time</th>
      <th>Reason</th>
      <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php
      while ($row = mysqli_fetch_assoc($approved)) {
        $appID = $row["app_ID"];
        $patID = $row["patient_ID"];
        $dateTime = $row["date_time"];
        $reason = $row["reason"];
    ?>
        <tr>
          <td><?php echo $appID?></td>
          <td><?php echo $patID?></td>
          <td><?php echo $dateTime?></td>
          <td><?php echo $reason?></td>
          <td>
          <a href='includes/updateapp.inc.php?id=<?php echo $row["app_ID"]?>&mindate=<?php echo $mindate?>&maxdate=<?php echo $maxdate?>&status=complete'>Complete</a>
          <a href='includes/updateapp.inc.php?id=<?php echo $row["app_ID"]?>&mindate=<?php echo $mindate?>&maxdate=<?php echo $maxdate?>&status=cancel'>Cancel</a>
          </td>
        </tr>
    <?php
      }
    ?>
    <tbody>
    </table>
</div>
<div>
  <h2>Completed Appointments</h2>
  <table class="table-template">
    <thead>
      <tr>
        <th>Appointment ID</th>
        <th>Patient ID</th>
        <th>Date and Time</th>
        <th>Reason</th>
      </tr>
    </thead>
    <tbody>
    <?php
      while ($row = mysqli_fetch_assoc($completed)) {
        $appID = $row["app_ID"];
        $patID = $row["patient_ID"];
        $dateTime = $row["date_time"];
        $reason = $row["reason"];
    ?>
        <tr>
          <td><?php echo $appID?></td>
          <td><?php echo $patID?></td>
          <td><?php echo $dateTime?></td>
          <td><?php echo $reason?></td>
        </tr>
    <?php
      }
    ?>
    </tbody>
    </table>
</div>
<div>
  <h2>Cancelled Appointments</h2>
  <table class="table-template">
    <thead>
    <tr>
      <th>Appointment ID</th>
      <th>Patient ID</th>
      <th>Date and Time</th>
      <th>Reason</th>
    </tr>
    </thead>
    <tbody>
    <?php
      while ($row = mysqli_fetch_assoc($cancelled)) {
        $appID = $row["app_ID"];
        $patID = $row["patient_ID"];
        $dateTime = $row["date_time"];
        $reason = $row["reason"];
    ?>
        <tr>
          <td><?php echo $appID?></td>
          <td><?php echo $patID?></td>
          <td><?php echo $dateTime?></td>
          <td><?php echo $reason?></td>
        </tr>
    <?php
      }
    ?>
    </tbody>
    </table>
</div>
<?php
    }
  }
?>
<?php
  include_once 'footer.php';
?>