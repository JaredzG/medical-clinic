<?php
  include_once 'header.php';
  require_once 'includes/dbh.inc.php';
  require_once 'includes/functions.inc.php';
  if ($_SESSION["userRole"] !== 'admin') {
    header("location: index.php");
    exit();
  }
  $result = getMedicine($conn);
?>
<h2>Medicine</h2>
<div class='med-table'>
  <table>
    <tr>
      <th>Medicine ID</th>
      <th>Brand</th>
      <th>Name</th>
      <th>Description</th>
      <th>Action</th>
    </tr>
    <?php
      while ($row = mysqli_fetch_assoc($result)) {
        $medID = $row["med_ID"];
        $medBrand = $row["brand"];
        $medName = $row["name"];
        $medDesc = $row["description"];
    ?>
    <tr>
      <td><?php echo $medID?></td>
      <td><?php echo $medBrand?></td>
      <td><?php echo $medName?></td>
      <td><?php echo $medDesc?></td>
      <td>
      <form action='/medical-clinic/delete.php' method='post'>
          <div class='form-element'>
            <input style='display: none;' name='userID' value='none'/>
          </div>
          <div class='form-element'>
            <input style='display: none;' name='userRole' value='<none'/>
          </div>
          <div class='form-element'>
            <input style='display: none;' name='otherID' value='<?php echo $medID?>'/>
          </div>
          <div class='form-element'>
            <input style='display: none;' name='otherRole' value='medicine'/>
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