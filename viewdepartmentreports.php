<?php
  include_once 'header.php';
?>
<div class='view-app-form'>
  <h2>DEPARTMENT REPORT</h2>
  <form action='/medical-clinic/viewdepartmentreports.php' method='post'>
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
    if ($_SESSION["userRole"] === 'admin'){
      $scheduled = viewDepartmentReports($conn, $mindate, $maxdate);
?>
<div>
  <h2>Doctor Report</h2>
  <table>
    <tr>
      <th>Dep Name</th>
      <th>Doctor Count</th>
      <th>TOTAL APPOINTMENT COUNT</th>
      <th>UNIQUE PATIENT COUNT</th>
      <th>Revenue</th>
    </tr>
    <?php
      while ($row = mysqli_fetch_assoc($scheduled)) {
        $dname = $row["dep_name"];
        $dcount = $row["Doctor_Count"];
        $tappointment = $row["Total_Appointments"];
        $upatient = $row["Unique_Patients"];
        $revenue = $row["REVENUE"];
    ?>
        <tr>
          <td><?php echo $dname?></td> 
          <td><?php echo $dcount?></td>
          <td><?php echo $tappointment?></td>
          <td><?php echo $upatient?></td>
          <td><?php echo $revenue?></td>
        </tr>
    <?php
      }
    ?>
    </table>
</div>
<?php
    }
  }
?>
<?php
  include_once 'footer.php';
?>