<?php
  include_once 'header.php';
  require_once 'includes/dbh.inc.php';
  require_once 'includes/functions.inc.php';
  if (isset($_SESSION["userRole"])) {
      if ($_SESSION["userRole"] === 'patient') {
        $result = getMedicalRecordFromUserID($conn, $_SESSION["userID"]);
      }
      else if ($_SESSION["userRole"] === 'doctor' ||$_SESSION["userRole"] === 'admin') {
        $result = getMedicalRecordFromPatientID($conn, $_REQUEST["patID"]);
      }
      else {
        header("location: index.php");
        exit();
      }
      $medrec = mysqli_fetch_assoc($result);
      if (!is_null($medrec)) {
        $result2 = getMedicineID($conn, $medrec["pat_ID"]);
        $medication = [];
        $i = 0;
        while ($row2 = mysqli_fetch_assoc($result2)) {
          $result3 = getMedicineName($conn, $row2["med_ID"]);
          $row3 = mysqli_fetch_assoc($result3);
          $medication[$i] = $row3["name"];
          $i = $i + 1;
        }
        $height = $medrec["inch_height"];
        $weight = $medrec["pound_weight"];
        $allergies = $medrec["allergies"];
        $diagnoses = $medrec["diagnoses"];
        $immus = $medrec["immunizations"];
        $prog = $medrec["progress"];
        $treatPlan = $medrec["treatment_plan"];
        $bdate = $medrec["b_date"];
        switch ($medrec["ethnicity"]) {
          case 'hl':
            $eth = 'Hispanic or Latino';
            break;
          case 'nhl':
            $eth = 'Not Hispanic or Latino';
            break;
        }
        switch ($medrec["race"]) {
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
?>
<div>
  <h2>Medical Record</h2>
  <h3>Height</h3>
  <p><?php echo $height?></p>
  <h3>Weight</h3>
  <p><?php echo $weight?></p>
  <h3>Birth Date</h3>
  <p><?php echo $bdate?></p>
  <h3>Ethnicity</h3>
  <p><?php echo $eth?></p>
  <h3>Race</h3>
  <p><?php echo $race?></p>
  <h3>Allergies</h3>
  <p><?php echo $allergies?></p>
  <h3>Diagnoses</h3>
  <p><?php echo $diagnoses?></p>
  <h3>Immunizations</h3>
  <p><?php echo $immus?></p>
  <h3>Progress</h3>
  <p><?php echo $prog?></p>
  <h3>Treatment Plan</h3>
  <p><?php echo $treatPlan?></p>
  <h3>Medication</h3>
  <?php
    $i = 0;
    while ($i < count($medication)) {
      echo '<p>'.$medication[$i].'</p>';
      $i = $i + 1;
    }
  ?>
</div>
<?php
    if ($_SESSION["userRole"] === 'doctor' ||$_SESSION["userRole"] === 'admin') {
      echo '<a href="updatemedrecord.php?patid='.$medrec["pat_ID"].'&action=update">Update Medical Record</a>';
    }
  }
}
else {
  header("location: index.php");
  exit();
}
  include_once 'footer.php';
?>