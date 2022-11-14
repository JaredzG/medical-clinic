<?php
  include_once 'header.php';
  require_once 'includes/dbh.inc.php';
  require_once 'includes/functions.inc.php';
  if ($_POST["senderRole"] === 'admin') {
?>
<h2>Are you sure you want to Delete:</h2>
<div class='table'>
  <table>
    <?php
    if (isset($_SESSION["userID"]) && ($_SESSION["userRole"] === 'patient' || $_SESSION["userRole"] === 'admin')) {
      if (isset($_POST["userID"]) && $_POST["otherID"] === 'none') {
        $user = getUserInfo($conn, $_POST["userID"], $_POST["userRole"]);
        $row = mysqli_fetch_assoc($user);
      }
      else if (isset($_POST["otherID"]) && $_POST["userID"] === 'none') {
        $feature = getFeature($conn, $_POST["otherID"], $_POST["otherRole"]);
        $row = mysqli_fetch_assoc($feature);
      }
    }
    if ($_POST["userID"] === 'none') {
      switch ($_POST["otherRole"]) {
        case 'department':
          echo '<tr>';
          echo '<th>Department Number</th>';
          echo '<th>Department Name</th>';
          echo '</tr>';
          echo '<tr>';
          echo '<td>'.$row["department_number"].'</td>';
          echo '<td>'.$row["dep_name"].'</td>';
          echo '</tr>';
          break;
        case 'clinic':
          echo '<tr>';
          echo '<th>Address ID</th>';
          echo '<th>Street Address</th>';
          echo '<th>City</th>';
          echo '<th>State</th>';
          echo '<th>Zip Code</th>';
          echo '</tr>';
          echo '<tr>';
          echo '<td>'.$row["address_ID"].'</td>';
          echo '<td>'.$row["street_address"].'</td>';
          echo '<td>'.$row["city"].'</td>';
          echo '<td>'.$row["state"].'</td>';
          echo '<td>'.$row["zip_code"].'</td>';
          echo '</tr>';
          break;
        case 'office':
          echo '<tr>';
          echo '<th>Office ID</th>';
          echo '<th>Department Number</th>';
          echo '<th>Address ID</th>';
          echo '<th>Phone Number</th>';
          echo '</tr>';
          echo '<tr>';
          echo '<td>'.$row["office_ID"].'</td>';
          echo '<td>'.$row["dep_number"].'</td>';
          echo '<td>'.$row["address_ID"].'</td>';
          echo '<td>'.$row["phone_number"].'</td>';
          echo '</tr>';
          break;
        case 'medicine':
          echo '<tr>';
          echo '<th>Medicine ID</th>';
          echo '<th>Brand</th>';
          echo '<th>Name</th>';
          echo '<th>Description</th>';
          echo '</tr>';
          echo '<tr>';
          echo '<td>'.$row["med_ID"].'</td>';
          echo '<td>'.$row["brand"].'</td>';
          echo '<td>'.$row["name"].'</td>';
          echo '<td>'.$row["description"].'</td>';
          echo '</tr>';
          break;
      }
    }
    else {
      switch ($_POST["userRole"]) {
        case 'admin':
          echo '<tr>';
          echo '<th>User ID</th>';
          echo '<th>Username</th>';
          echo '<th>User Role</th>';
          echo '<th>Phone Number</th>';
          echo '<th>Email Address</th>';
          echo '</tr>';
          echo '<tr>';
          echo '<td>'.$row["user_ID"].'</td>';
          echo '<td>'.$row["username"].'</td>';
          echo '<td>'.$row["user_role"].'</td>';
          echo '<td>'.$row["user_phone_num"].'</td>';
          echo '<td>'.$row["user_email_address"].'</td>';
          echo '</tr>';
          break;
        case 'doctor':
          echo '<tr>';
          echo '<th>Doctor ID</th>';
          echo '<th>Department Number</th>';
          echo '<th>First Name</th>';
          echo '<th>Middle Name</th>';
          echo '<th>Last Name</th>';
          echo '<th>Address ID</th>';
          echo '<th>Credentials</th>';
          echo '</tr>';
          echo '<tr>';
          echo '<td>'.$row["doc_ID"].'</td>';
          echo '<td>'.$row["dep_num"].'</td>';
          echo '<td>'.$row["f_name"].'</td>';
          echo '<td>'.$row["m_name"].'</td>';
          echo '<td>'.$row["l_name"].'</td>';
          echo '<td>'.$row["address_ID"].'</td>';
          echo '<td>'.$row["credentials"].'</td>';
          echo '</tr>';
          break;
        case 'nurse':
          echo '<tr>';
          echo '<th>Nurse ID</th>';
          echo '<th>Department Number</th>';
          echo '<th>First Name</th>';
          echo '<th>Middle Name</th>';
          echo '<th>Last Name</th>';
          echo '<th>Address ID</th>';
          echo '<th>Registered</th>';
          echo '</tr>';
          echo '<tr>';
          echo '<td>'.$row["nurse_ID"].'</td>';
          echo '<td>'.$row["dep_num"].'</td>';
          echo '<td>'.$row["f_name"].'</td>';
          echo '<td>'.$row["m_name"].'</td>';
          echo '<td>'.$row["l_name"].'</td>';
          echo '<td>'.$row["address_ID"].'</td>';
          echo '<td>'.$row["registered"].'</td>';
          echo '</tr>';
          break;
        case 'receptionist':
          echo '<tr>';
          echo '<th>Receptionist ID</th>';
          echo '<th>First Name</th>';
          echo '<th>Middle Name</th>';
          echo '<th>Last Name</th>';
          echo '<th>Address ID</th>';
          echo '</tr>';
          echo '<tr>';
          echo '<td>'.$row["rec_ID"].'</td>';
          echo '<td>'.$row["f_name"].'</td>';
          echo '<td>'.$row["m_name"].'</td>';
          echo '<td>'.$row["l_name"].'</td>';
          echo '<td>'.$row["address_ID"].'</td>';
          echo '</tr>';
          break;
        case 'patient':
          echo '<tr>';
          echo '<th>Patient ID</th>';
          echo '<th>First Name</th>';
          echo '<th>Middle Name</th>';
          echo '<th>Last Name</th>';
          echo '<th>Address ID</th>';
          echo '<th>Preferred Clinic ID</th>';
          echo '<th>Primary Doctor ID</th>';
          echo '</tr>';
          echo '<tr>';
          echo '<td>'.$row["patient_ID"].'</td>';
          echo '<td>'.$row["f_name"].'</td>';
          echo '<td>'.$row["m_name"].'</td>';
          echo '<td>'.$row["l_name"].'</td>';
          echo '<td>'.$row["address_ID"].'</td>';
          echo '<td>'.$row["clinic_ID"].'</td>';
          echo '<td>'.$row["prim_doc_ID"].'</td>';
          echo '</tr>';
          break;
      }
    }
  ?>
  </table>
</div>
<div class='form'>
  <form action='/medical-clinic/includes/delete.inc.php' method='post'>
    <?php
      if ($_POST["userID"] === 'none') {
        switch ($_POST["otherRole"]) {
          case 'department':
            echo "<input style='display: none;' type='text' name='role' value='department'/>";
            echo "<input style='display: none;' type='text' name='id' value='".$_POST["otherID"]."'/>";
            break;
          case 'clinic':
            echo "<input style='display: none;' type='text' name='role' value='clinic'/>";
            echo "<input style='display: none;' type='text' name='id' value='".$_POST["otherID"]."'/>";
            break;
          case 'office':
            echo "<input style='display: none;' type='text' name='role' value='office'/>";
            echo "<input style='display: none;' type='text' name='id' value='".$_POST["otherID"]."'/>";
            break;
          case 'medicine':
            echo "<input style='display: none;' type='text' name='role' value='medicine'/>";
            echo "<input style='display: none;' type='text' name='id' value='".$_POST["otherID"]."'/>";
            break;
        }
      }
      else {
        switch ($_POST["userRole"]) {
          case 'admin':
            echo "<input style='display: none;' type='text' name='role' value='admin'/>";
            echo "<input style='display: none;' type='text' name='id' value='".$_POST["userID"]."'/>";
            break;
          case 'doctor':
            echo "<input style='display: none;' type='text' name='role' value='doctor'/>";
            echo "<input style='display: none;' type='text' name='id' value='".$_POST["userID"]."'/>";
            break;
          case 'nurse':
            echo "<input style='display: none;' type='text' name='role' value='nurse'/>";
            echo "<input style='display: none;' type='text' name='id' value='".$_POST["userID"]."'/>";
            break;
          case 'receptionist':
            echo "<input style='display: none;' type='text' name='role' value='receptionist'/>";
            echo "<input style='display: none;' type='text' name='id' value='".$_POST["userID"]."'/>";
            break;
          case 'patient':
            echo "<input style='display: none;' type='text' name='role' value='patient'/>";
            echo "<input style='display: none;' type='text' name='id' value='".$_POST["userID"]."'/>";
            break;
        }
      }
    ?>
    <button type='submit' name='submit'>YES</button>
  </form>
<?php
  if ($_POST["userID"] === 'none') {
    switch ($_POST["otherRole"]) {
      case 'department':
        echo "<a href='dept.php'>NO</a>";
        break;
      case 'clinic':
        echo "<a href='clinicadd.php'>NO</a>";
        break;
      case 'office':
        echo "<a href='office.php'>NO</a>";
        break;
      case 'medicine':
        echo "<a href='medicine.php'>NO</a>";
        break;
    }
  }
  else {
    if ($_POST["userRole"] === 'admin' || $_POST["userRole"] === 'doctor' || $_POST["userRole"] === 'nurse' || $_POST["userRole"] === 'receptionist') {
      echo "<a href='users.php'>NO</a>";
    }
    else {
      if ($_SESSION["userRole"] === 'admin') {
        echo "<a href='users.php'>NO</a>";
      }
      else if ($_SESSION["userRole"] === 'patient') {
        echo "<a href='viewinfo.php'>NO</a>";
      }
    }
  }
?>
</div>
<?php
  }
  else if ($_POST["senderRole"] === 'patient') {
?>
<h2>Are you sure you want to delete your account?</h2>
<div class='delete-form'>
  <form action='/medical-clinic/includes/delete.inc.php' method='post'>
    <div class='form-element'>
      <input style='display: none;' type='text' name='role' value='patient'/>
      <input style='display: none;' type='text' name='id' value='<?php echo $_POST["userID"]?>'/>
    </div>
    <button type='submit' name='submit'>YES</button>
  </form>
</div>
<div class='delete-form'>
  <form action='/medical-clinic/settings.php' method='post'>
    <div class='form-element'>
      <input style='display: none;' type='text' name='patient' value='<?php echo $_POST["userID"]?>'/>
    </div>
    <button type='submit' name='submit'>NO</button>
  </form>
</div>
<?php
  }
  include_once 'footer.php';
?>