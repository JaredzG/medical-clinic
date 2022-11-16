<?php
  include_once 'header.php';
  require_once 'includes/dbh.inc.php';
  require_once 'includes/functions.inc.php';
  $result = viewDepartments($conn);
?>
<div class='dept-form'>
  <h2>Make a Department</h2>
  <form action='/medical-clinic/includes/dept.inc.php' method='post'>
    <div class='form-element'>
      <label for='dpt-name'>Enter a Department Name</label>
      <input type='text' name='dptname' id='dpt-name' placeholder='Name'/>
    </div>
    <div class='submit-btn'>
      <button type='submit' name='submit'>Submit</button>
    </div>
  </form>
</div>
<div>
  <h2>Departments</h2>
  <table class="table-template">
    <thead>
      <tr>
        <th>Number</th>
        <th>Name</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php
      while ($row = mysqli_fetch_assoc($result)) {
        $depnum = $row["department_number"];
        $depname = $row["dep_name"];
    ?>
      <tr>
        <td><?php echo $depnum?></td>
        <td><?php echo $depname?></td>
        <td>
        <form action='/medical-clinic/delete.php' method='post'>
          <div class='form-element'>
            <input style='display: none;' name='userID' value='none'/>
          </div>
          <div class='form-element'>
            <input style='display: none;' name='userRole' value='none'/>
          </div>
          <div class='form-element'>
            <input style='display: none;' name='otherID' value='<?php echo $depnum?>'/>
          </div>
          <div class='form-element'>
            <input style='display: none;' name='otherRole' value='department'/>
          </div>
          <div class='form-element'>
            <input style='display: none;' name='senderRole' value='admin'/>
          </div>
          <div class='submit-btn'>
            <button type='submit' name='submit'>Delete</button>
          </div>
        </form>
        </td>
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