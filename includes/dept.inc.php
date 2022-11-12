<?php
if(isset($_POST["submit"])) {
  $dptname = $_POST["dptname"];

  require_once 'dbh.inc.php';
  require_once 'functions.inc.php';

  if (emptyInputDeptName($dptname) !== false) {
    header("location: ../dept.php?error=emptyinput");
    exit();
  }

  if (deptNameExists($conn, $dptname) !== false) {
    header("location: ../dept.php?error=nameexists");
    exit();
  }

  createDepartment($conn, $dptname);
}
else {
  header("location: ../dept.php");
  exit();
}