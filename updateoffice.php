<?php
  include_once 'header.php';
  if (isset($_POST["submit"])) {
?>
<div class='office-form'>
  <h2>Update Office</h2>
  <form action='/medical-clinic/includes/updateoffice.inc.php' method='post'>
    <input style='display: none;' name='officeID' value='<?php echo $_POST["officeID"]?>'/>
    <div class='form-element'>
    <label for='officeDepNum'>Select Department</label>
      <select name='officeDepNum' id='officeDepNum'>
        <option>Select</option>
      <?php
        require_once 'includes/dbh.inc.php';
        require_once 'includes/functions.inc.php';
        $result = viewDepartments($conn);
        while ($row = mysqli_fetch_assoc($result)) {
          $depnum = $row["department_number"];
          $depname = $row["dep_name"];
      ?>
        <option value='<?php echo $depnum; ?>'<?php if ($_POST["officeDep"] === $depname) echo ' selected';?>><?php echo $depnum.':   '.$depname ?></option>
      <?php
        }
      ?>
      </select>
    </div>
    <div class='form-element'>
      <label for='officePhoneNum'>Enter Phone Number</label>
      <input type='text' name='officePhoneNum' id='officePhoneNum' value='<?php echo $_POST["officePhoneNum"];?>'/>
    </div>
    <div class='submit-btn'>
      <button type='submit' name='submit'>Submit</button>
    </div>
  </form>
</div>
<?php
  }
  else {
    header("location: index.php");
    exit();
  }
?>
<?php
  include_once 'footer.php';
?>