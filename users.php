<?php
  include_once 'header.php';
  require_once 'includes/dbh.inc.php';
  require_once 'includes/functions.inc.php';
  if ($_SESSION["userRole"] === 'admin') {
    $result = getUsers($conn);
  }
  else {
    header("location: index.php");
    exit();
  }
?>
<div class='choose-user'>
  <h2>Choose a User</h2>
  <?php
    while ($row = mysqli_fetch_assoc($result)) {
      $userRole = $row["user_role"];
      $userID = $row["user_ID"];
      $result2 = getUserInfo($conn, $userID, $userRole);
      if ($result2 !== 'admin') {
        $row2 = mysqli_fetch_assoc($result2);
        $fname = $row2["f_name"];
        $lname = $row2["l_name"];
      }
  ?>
  <table>
    <tr>
      <th>User ID</th>
      <th>User Role</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Action</th>
    </tr>
    <tr>
      <td><?php echo $userID?></td>
      <td><?php echo $userRole?></td>
      <td><?php echo $fname?></td>
      <td><?php echo $lname?></td>
      <td>
      <?php if ($_SESSION["userRole"] === 'admin') {
        if ($userRole !== 'admin') {
      ?>
        <form action='/medical-clinic/viewinfo.php' method='post'>
          <div class='form-element'>
            <input style='display: none;' name='userID' value='<?php echo $userID?>'/>
          </div>
          <div class='form-element'>
            <input style='display: none;' name='userRole' value='<?php echo $userRole?>'/>
          </div>
          <div class='submit-btn'>
            <button type='submit' name='submit'>View Info</button>
          </div>
        </form>
      <?php
        }
      ?>
        <form action='/medical-clinic/delete.php' method='post'>
          <div class='form-element'>
            <input style='display: none;' name='userID' value='<?php echo $userID?>'/>
          </div>
          <div class='form-element'>
            <input style='display: none;' name='userRole' value='<?php echo $userRole?>'/>
          </div>
          <div class='form-element'>
            <input style='display: none;' name='otherID' value='none'/>
          </div>
          <div class='form-element'>
            <input style='display: none;' name='otherRole' value='none'/>
          </div>
          <div class='form-element'>
            <input style='display: none;' name='senderRole' value='admin'/>
          </div>
          <div class='submit-btn'>
            <button type='submit' name='submit'>Delete</button>
          </div>
        </form>
      <?php }?>
      </td>
    </tr>
  </table>
</div>
<?php
    }
  include_once 'footer.php';
?>