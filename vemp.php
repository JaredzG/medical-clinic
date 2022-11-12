<?php
  include_once 'header.php';
  require_once 'includes/dbh.inc.php';
  require_once 'includes/functions.inc.php';
?>
<div class='emp-form'>
  <h2>View Employees</h2>
  <form action='/medical-clinic/vemp.php' method='post'>
    <div class='form-element'>
      <label for='num'>Select a Department</label>
        <select name='num' id='num'>
          <option>Select</option>
          <option value='0'>None</option>
        <?php
          require_once 'includes/dbh.inc.php';
          require_once 'includes/functions.inc.php';
          $result = viewDepartments($conn);
          while ($row = mysqli_fetch_assoc($result)) {
            $depnum = $row["department_number"];
            $depname = $row["dep_name"];
        ?>
          <option value='<?php echo $depnum; ?>'><?php echo $depnum.': '.$depname ?></option>
        <?php
          }
        ?>
        </select>
    </div>
    <div class='submit-btn'>
      <button type='submit' name='submit'>Submit</button>
    </div>
  </form>
</div>
<?php
  if (isset($_POST["submit"])) {
    if ($_POST["num"] !== 0) {
      $result = getDepartment($conn, $_POST["num"]);
      $row = mysqli_fetch_assoc($result);
      $depname = $row["dep_name"];
    }
?>
    <div>
      <h2><?php echo $depname.' '?>Employees</h2>
      <table>
        <tr>
          <th>User ID</th>
          <th>Name</th>
          <th>Role</th>
        </tr>
        <?php
          if ($_POST["num"] !== '0') {
            $result2 = getEmployees($conn, $_POST["num"], 1);
            $result3 = getEmployees($conn, $_POST["num"], 2);
            while ($row = mysqli_fetch_assoc($result2)) {
                $empID = $row["user_ID"];
                $empName = $row["f_name"]." ".$row["l_name"];
                $role = $row["user_role"];
          ?>
            <tr>
              <td><?php echo $empID?></td>
              <td><?php echo $empName?></td>
              <td><?php echo $role?></td>
            </tr>
          <?php
            }
            while ($row = mysqli_fetch_assoc($result3)) {
                $empID = $row["user_ID"];
                $empName = $row["f_name"]." ".$row["l_name"];
                $role = $row["user_role"];
          ?>
            <tr>
              <td><?php echo $empID?></td>
              <td><?php echo $empName?></td>
              <td><?php echo $role?></td>
            </tr>
          <?php
            }
          }
          $result4 = getEmployees($conn, $_POST["num"], 0);
          while ($row = mysqli_fetch_assoc($result4)) {
              $empID = $row["user_ID"];
              $empName = $row["f_name"]." ".$row["l_name"];
              $role = $row["user_role"];
        ?>
          <tr>
            <td><?php echo $empID?></td>
            <td><?php echo $empName?></td>
            <td><?php echo $role?></td>
          </tr>
      </table>
    </div>
  <?php
    }
  }
  else {
    debug_to_console('nah');
  }
  ?>
<?php
  include_once 'footer.php';
?>