<?php
  include_once 'header.php';
  require_once 'includes/dbh.inc.php';
  require_once 'includes/functions.inc.php';
  if ($_SESSION["userRole"] === 'doctor') {
    $result = getDocID($conn, intval($_SESSION["userID"]));
    $docID = mysqli_fetch_assoc($result);
    $result2 = getDocPatients($conn, $docID["doc_ID"]);
  }
  else if ($_SESSION["userRole"] === 'admin') {
    $result2 = getAllPatients($conn);
  }
  else {
    header("location: index.php");
    exit();
  }
?>
<div class='choose-patient'>
  <h2>Choose a Patient</h2>
  <?php
    while ($row = mysqli_fetch_assoc($result2)) {
      $patID = $row["patient_ID"];
      $patFName = $row["f_name"];
      $patLName = $row["l_name"];
  ?>
  <a style='display: block;' href='viewmedrecord.php?patID=<?php echo $patID?>'>Patient <?php echo $patID?>: <?php echo $patFName?> <?php echo $patLName?></a>
</div>
<?php
    }
  include_once 'footer.php';
?>