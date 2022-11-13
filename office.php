<?php
  include_once 'header.php';
?>
<div class='office-form'>
  <h2>Make an Office</h2>
  <form action='/medical-clinic/includes/office.inc.php' method='post'>
    <div class='form-element'>
    <label for='num'>Select a Department</label>
      <select name='num' id='num'>
        <option>Select</option>
      <?php
        require_once 'includes/dbh.inc.php';
        require_once 'includes/functions.inc.php';
        $result = viewDepartments($conn);
        while ($row = mysqli_fetch_assoc($result)) {
          $depnum = $row["department_number"];
          $depname = $row["dep_name"];
      ?>
        <option value='<?php echo $depnum; ?>'><?php echo $depnum.':   '.$depname ?></option>
      <?php
        }
      ?>
      </select>
    </div>
    <div class='form-element'>
      <label for='address'>Select an Address</label>
      <select name='address' id='address'>
        <option>Select</option>
      <?php
        require_once 'includes/dbh.inc.php';
        require_once 'includes/functions.inc.php';
        $result = viewClinicLocations($conn);
        while ($row = mysqli_fetch_assoc($result)) {
          $addID = $row["address_ID"];
          $streetAdd = $row["street_address"];
          $city = $row["city"];
          $state = $row["state"];
          $zip = $row["zip_code"];
      ?>
        <option value='<?php echo $addID; ?>'><?php echo $addID.':     '.$streetAdd.' '.$city.', '.$state.' '.$zip ?></option>
      <?php
        }
      ?>
      </select>
    </div>
    <div class='form-element'>
      <label for='office-num'>Enter a Phone Number</label>
      <input type='text' name='offnum' id='office-num' placeholder='Phone Number'/>
    </div>
    <div class='submit-btn'>
      <button type='submit' name='submit'>Submit</button>
    </div>
  </form>
</div>
<?php
  require_once 'includes/dbh.inc.php';
  require_once 'includes/functions.inc.php';
  $result = viewOffices($conn);
?>
<div>
  <h2>Offices</h2>
  <table>
    <tr>
      <th>Office ID</th>
      <th>Department</th>
      <th>Location</th>
      <th>Phone Number</th>
      <th>Action</th>
    </tr>
    <?php
      while ($row = mysqli_fetch_assoc($result)) {
        $offID = $row["office_ID"];
        $depNum = $row["dep_number"];
        $addID = $row["address_ID"];
        $phoneNum = $row["phone_number"];
    ?>
        <tr>
          <td><?php echo $offID?></td>
    <?php
      $result2 = getDepartmentName($conn, $depNum);
      $row2 = mysqli_fetch_assoc($result2);
      $result3 = getAddress($conn, $addID);
      $row3 = mysqli_fetch_assoc($result3);
    ?>
          <td><?php echo $row2["dep_name"]?></td>
          <td><?php echo $row3["street_address"].' '.$row3["city"].', '.$row3["state"].' '.$row3["zip_code"]?></td>
          <td><?php echo $phoneNum?></td>
          <td>
          <form action='/medical-clinic/delete.php' method='post'>
          <div class='form-element'>
            <input style='display: none;' name='userID' value='none'/>
          </div>
          <div class='form-element'>
            <input style='display: none;' name='userRole' value='<none'/>
          </div>
          <div class='form-element'>
            <input style='display: none;' name='otherID' value='<?php echo $offID?>'/>
          </div>
          <div class='form-element'>
            <input style='display: none;' name='otherRole' value='office'/>
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
  </table>
</div>
<?php
  include_once 'footer.php';
?>