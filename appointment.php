<?php
  include_once 'header.php';
  require_once 'includes/dbh.inc.php';
  require_once 'includes/functions.inc.php';
?>
<div class='appointment-form'>
  <h2>Create Appointment</h2>
  <form action='/medical-clinic/includes/appointment.inc.php' method='post'>
      <div class='form-element'>
        <label for='username'>Enter Username</label>
        <input type='text' name='username' id='username' placeholder='Username' required/>
      </div>
      <div class='form-element'>
        <label for='doctor'>Select Doctor</label>
        <select name='doctor' id='doctor' required>
          <option>Select</option>
          <?php
            $result = getDoctors($conn);
            while ($row = mysqli_fetch_assoc($result)) {
              $deptName = mysqli_fetch_assoc(getDepartmentName($conn, $row["dep_num"]));
          ?>
          <option value='<?php echo $row["doc_ID"]?>'><?php echo $deptName["dep_name"].": ".$row["f_name"]." ".$row["l_name"]?></option>
          <?php
            }
          ?>
        </select>
      </div>
      <div class='form-element'>
        <label for='reason'>Enter appointment reason</label>
        <textarea style='display: block;' name='reason' id='reason' rows='4' cols='50' required>Reason</textarea>
      </div>
      <div class='form-element'>
        <label for='adate-time'>Enter appointment date</label>
        <input type='datetime-local' id='adate-time' name='adate-time' value='2022-12-01T00:00' min='2022-11-01T00:00' max='2023-01-01T00:00' required/>
      </div>
      <div class='submit-btn'>
        <button type='submit' name='submit'>Submit</button>
      </div>
    </form>
    <?php
      if (isset($_GET["error"])) {
        if ($_GET["error"] === "emptyinput") {
          echo "<p>Fill in all fields.</p>";
        }
      }
    ?>
</div> 
<?php
  if ($_REQUEST["error"] === 'createappfailed') {
    echo '<p>Could not create appointment. No referral with specialist was found.</p>';
  }
  include_once 'footer.php';
?>