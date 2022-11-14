<?php
require_once 'dbh.inc.php';
require_once 'functions.inc.php';
if (isset($_SESSION["userRole"])) {
  if ($_SESSION["userRole"] === 'patient' || $_SESSION["userRole"] === 'admin') {
    deleteEntity($conn, $_POST["role"], $_POST["id"]);
    if ($_SESSION["userRole"] === 'admin') {
      switch ($_POST["role"]) {
        case 'admin':
        case 'doctor':
        case 'nurse':
        case 'receptionist':
        case 'patient':
          header("location: ../users.php");
          exit();
        case 'department':
          header("location: ../dept.php");
          exit();
          break;
        case 'clinic':
          header("location: ../clinicadd.php");
          exit();
          break;
        case 'office':
          header("location: ../office.php");
          exit();
          break;
        case 'medicine':
          header("location: ../medicine.php");
          exit();
          break;
      }
    }
    else {
      header("location: logout.inc.php");
      exit();
    }
  }
  else {
    header("location: ../index.php");
    exit();
  }
}
else {
  header("location: ../index.php");
  exit();
}