<?php
  include_once 'header.php';
?>
<div class='view-app-form'>
  <h2>MEDICATION REPORT</h2>
  <form action='/medical-clinic/viewmedicationreports.php' method='post'>
    <div class='submit-btn'>
      <button type='submit' name='submit'>Submit</button>
    </div>
  </form>
</div>
<?php
  require_once "includes/dbh.inc.php";
  require_once "includes/functions.inc.php";
  if (isset($_POST["submit"])) {
    if ($_SESSION["userRole"] === 'admin'){
      $scheduled = viewMedicationReport($conn, $mindate, $maxdate);
?>
<div>
  <table class="table-template">
    <thead>
      <tr>
        <th>Medicine Name</th>
        <th>Patient Count</th>
        <th>Average Height</th>
        <th>Average Weight</th>
        <th>Average Age</th>
        <th>American Indian or Alaska Native</th>
        <th>Asian</th>
        <th>Black or African American</th>
        <th>Native Hawaiian or Other Pacific Islander</th>
        <th>White</th>
      </tr>
    </thead>
    <tbody>
    <?php
      while ($row = mysqli_fetch_assoc($scheduled)) {
        $mname = $row["name"];
        $pcount = $row["Patient_Count"];
        $a_height = $row["Average_Height"];
        $a_weight = $row["Average_Weight"];
        $a_age = $row["Average_Age"];
        $race_i = $row["American_Indian_or_Alaska_Native"];
        $race_a = $row["Asian"];
        $race_b = $row["Black_or_African_American"];
        $race_n = $row["Native_Hawaiian_or_Other_Pacific_Islander"];
        $race_w = $row["White"];
    ?>
        <tr>
          <td><?php echo $mname?></td>
          <td><?php echo $pcount?></td>
          <td><?php echo $a_height?></td>
          <td><?php echo $a_weight?></td>
          <td><?php echo $a_age?></td>
          <td><?php echo $race_i?></td>
          <td><?php echo $race_a?></td>
          <td><?php echo $race_b?></td>
          <td><?php echo $race_n?></td>
          <td><?php echo $race_w?></td>
        </tr>
    <?php
      }
    ?>
    </tbody>
    </table>
    <p>*last columns indicate population distribution of medicine users by race/ethnicity</p>
</div>
<?php
    }
  }
?>
<?php
  include_once 'footer.php';
?>