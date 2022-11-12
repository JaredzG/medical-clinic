<?php
session_start();
if (isset($_POST["submit"])) {
  $fname = $_POST["fname"];
  $mname = $_POST["mname"];
  $lname = $_POST["lname"];
  $ssn = $_POST["ssn"];
  $sex = $_POST["sex"];
  $bdate = $_POST["bdate"];
  $streetAdd = $_POST["street-add"];
  $aptNum = $_POST["apt-num"];
  $city = $_POST["city"];
  $state = $_POST["state"];
  $zip = $_POST["zip"];
  $deptNum = $_POST["num"];
  if ($_SESSION["newuserRole"] === 'doctor') {
    $specialty = $_POST["specialty"];
    $credentials = $_POST["credentials"];
    $primary = $_POST["primary"];
  }
  else if ($_SESSION["newuserRole"] === 'nurse') {
    $registered = $_POST["registered"];
  }

  require_once "dbh.inc.php";
  require_once "functions.inc.php";

  if (emptyInputEmpInfo($fname, $lname, $ssn, $sex, $streetAdd, $city, $state, $zip) !== false) {
    header("location: ../empinfo.php?error=emptyinput");
    exit();
  }
  if (invalidSSN($ssn) !== false) {
    header("location: ../empinfo.php?error=invalidssn");
    exit();
  }

  if (invalidZip($zip) !== false) {
    header("location: ../empinfo.php?error=invalidzip");
    exit();
  }

  if ($_SESSION["newuserRole"] === 'doctor') {
    createDoctor($conn, $fname, $mname, $lname, $ssn, $sex, $streetAdd, $aptNum, $city, $state, $zip, $deptNum, $credentials);
  }
  else if ($_SESSION["newuserRole"] === 'nurse') {
    createNurse($conn, $fname, $mname, $lname, $ssn, $sex, $streetAdd, $aptNum, $city, $state, $zip, $deptNum, $registered);
  }
  else {
    createReceptionist($conn, $fname, $mname, $lname, $ssn, $sex, $streetAdd, $aptNum, $city, $state, $zip);
  }
}
else {
  header("location: ../empinfo.php");
  exit();
}
