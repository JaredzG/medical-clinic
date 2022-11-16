<?php
  include_once 'header.php';
  require_once 'includes/dbh.inc.php';
  require_once 'includes/functions.inc.php';
  if ($_SESSION["userRole"] === 'admin' || $_SESSION["userRole"] === 'receptionist') {
    $result = getAllPatients($conn);
  }
  else if ($_SESSION["userRole"] === 'doctor') {
    $sql = "SELECT doc_ID FROM Doctor WHERE doc_user = ? AND deleted_flag = false;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("location: patients.php?error=getpatfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["userID"]);
    mysqli_stmt_execute($stmt);
    $result2 = mysqli_stmt_get_result($stmt);
    $row2 = mysqli_fetch_assoc($result2);
    $docID = $row2["doc_ID"];
    $result = getDocPatients($conn, $docID);
  }
  else {
    header("location: index.php");
    exit();
  }
?>
<div class='choose-patient'>
  <h2>Choose a Patient</h2>
  <table class="table-template">
    <thead>
      <tr>
        <th>User ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
        while ($row = mysqli_fetch_assoc($result)) {
          $userID = $row["pat_user"];
          $fname = $row["f_name"];
          $mname = $row["m_name"];
          $lname = $row["l_name"];
      ?>
        <tr>
          <td><?php echo $userID?></td>
          <td><?php echo $fname?></td>
          <td><?php echo $lname?></td>
          <td>
            <form action='/medical-clinic/viewinfo.php' method='post'>
              <div class='form-element'>
                <input style='display: none;' name='userID' value='<?php echo $userID?>'/>
              </div>
              <div class='form-element'>
                <input style='display: none;' name='userRole' value='patient'/>
              </div>
              <div class='submit-btn'>
                <button type='submit' name='submit'>View Info</button>
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