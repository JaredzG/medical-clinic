<?php
  include_once 'header.php';
  require_once 'includes/dbh.inc.php';
  require_once 'includes/functions.inc.php';
  $patID = intval($_REQUEST["patid"]);
  if ($_REQUEST["action"] === 'update') {
    $sql = "SELECT * FROM Medical_Record WHERE pat_ID = ? AND deleted_flag = false;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("location: viewmedrecord.php?error=getmedrecfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt, 'i', $patID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $sql2 = "SELECT med_ID FROM Medical_Record_Contains_Medicine WHERE pat_ID = ? AND deleted_flag = false;";
    $stmt2 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt2, $sql2)) {
      header("location: viewmedrecord.php?error=getmedidsfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt2, 'i', $patID);
    mysqli_stmt_execute($stmt2);
    $result2 = mysqli_stmt_get_result($stmt2);
    $rows = mysqli_fetch_all($result2);
    if (count($rows) != intval('0')) {
      $medIDs = [];
      $i = 0;
      while ($i < count($rows)) {
        $medIDs[$i] = $rows[$i][0];
        $i = $i + 1;
      }
      $in = str_repeat('?, ', count($rows) - 1).'?';
      $types = str_repeat('i', count($rows));
      $sql3 = "SELECT name, description FROM Medicine WHERE med_ID IN ($in) AND deleted_flag = false;";
      $stmt3 = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt3, $sql3)) {
        header("location: viewmedrecord.php?error=getmednamesfailed");
        exit();
      }
      mysqli_stmt_bind_param($stmt3, $types, ...$medIDs);
      mysqli_stmt_execute($stmt3);
      $result3 = mysqli_stmt_get_result($stmt3);
      $rows2 = mysqli_fetch_all($result3);
      $medNames = [];
      $medDescs = [];
      $i = 0;
      while ($i < count($rows2)) {
        $medNames[$i] = $rows2[$i][0];
        $medDescs[$i] = $rows2[$i][1];
        $i = $i + 1;
      }
    }
  }
?>
<h2>Medical Record</h2>
<div class='med-rec-form'>
<form action='/medical-clinic/includes/updatemedrecord.inc.php' method='post'>
  <input style='display: none;' type='text' name='patid' value='<?php echo $patID?>'/>
  <div class='form-element'>
    <label for='height'>Height (Inches)</label>
    <input style='display: block;' type='text' name='height' id='height' value='<?php if (!is_null($row)) echo $row["inch_height"];?>'/>
  </div>
  <div class='form-element'>
    <label for='weight'>Weight (Pounds)</label>
    <input style='display: block;' type='text' name='weight' id='weight' value='<?php if (!is_null($row)) echo $row["pound_weight"];?>'/>
  </div>
  <div class='form-element'>
        <label for='bdate'>Birth Date</label>
        <input type='date' id='bdate' name='bdate' value='<?php if(!is_null($row)) echo $row["b_date"];?>' min='1920-01-01' required/>
      </div>
  <div class='form-element'>
        <label for='ethnicity'>Ethnicity</label>
        <select name='ethnicity' id='ethnicity' value='<?php if(!is_null($row)) echo $row["ethnicity"];?>'>
          <option value='hl'>Hispanic or Latino</option>
          <option value='nhl'>Not Hispanic or Latino</option>
        </select>
  </div>
  <div class='form-element'>
        <label for='race'>Race</label>
        <select name='race' id='race' value='<?php if (!is_null($row)) echo $row["race"];?>'>
          <option value='aian'>American Indian or Alaska Native</option>
          <option value='a'>Asian</option>
          <option value='baf'>Black or African American</option>
          <option value='nhopi'>Native Hawaiian or Other Pacific Islander</option>
          <option value='w'>White</option>
        </select>
      </div>
  <div class='form-element'>
    <label for='allergies'>Allergies</label>
    <textarea style='display: block;' name='allergies' id='allergies' rows='4' cols='50'><?php if (!is_null($row)) echo $row["allergies"];?></textarea>
  </div>
  <div class='form-element'>
    <label for='diagnoses'>Diagnoses</label>
    <textarea style='display: block;' name='diagnoses' id='diagnoses' rows='4' cols='50'><?php if (!is_null($row)) echo $row["diagnoses"];?></textarea>
  </div>
  <div class='form-element'>
    <label for='immunizations'>Immunizations</label>
    <textarea style='display: block;' name='immunizations' id='immunizations' rows='4' cols='50'><?php if (!is_null($row)) echo $row["immunizations"];?></textarea>
  </div>
  <div class='form-element'>
    <label for='progress'>Progress</label>
    <textarea style='display: block;' name='progress' id='progress' rows='4' cols='50'><?php if (!is_null($row)) echo $row["progress"];?></textarea>
  </div>
  <div class='form-element'>
    <label for='treatment-plan'>Treatment Plan</label>
    <textarea style='display: block;' name='treatment-plan' id='treatment-plan' rows='4' cols='50'><?php if (!is_null($row)) echo $row["treatment_plan"];?></textarea>
  </div>
  <div class='form-element'>
    <label for='medication'>Medication</label>
    <textarea style='display: block;' name='medication' id='medication' rows='4' cols='50'><?php
        if (!is_null($medNames) && !is_null($medDescs)) {
          $i = 0;
          while ($i < count($medNames)) {
            echo $medNames[$i].' - '.$medDescs[$i]."\n";
            $i = $i + 1;
          }
        }
      ?></textarea>
  </div>
  <div class='form-element'>
    <label for='prescribe'>Prescribe Additional Medication?</label>
    <select name='prescribe' id='prescribe'>
      <option>Select</option>
      <option value='0'>No</option>
      <option value='1'>Yes</option>
    </select>
  </div>
  <div class='form-element'>
    <label for='brand'>Brand</label>
    <input style='display: block;' type='text' id='brand' name='brand'/>
  </div>
  <div class='form-element'>
    <label for='name'>Name</label>
    <input style='display: block;' type='text' id='name' name='name'/>
  </div>
  <div class='form-element'>
    <label for='desc'>Description</label>
    <textarea style='display: block;' name='desc' id='desc' rows='4' cols='50'></textarea>
  </div>
  <button class='submit-btn' type='submit' name='submit'>Submit</button>
</form>
</div>
<?php
  include_once 'footer.php';
?>