<?php
session_start();
if (isset($_POST["submit"])) {
  $fname = $_POST["fname"];
  if ($_POST["mname"] !== '') {
    $mname = $_POST["mname"];
  }
  else {
    $mname = NULL;
  }
  $lname = $_POST["lname"];
  $ssn = $_POST["ssn"];
  $sex = $_POST["sex"];
  $bdate = $_POST["bdate"];
  $ethnicity = $_POST["ethnicity"];
  $race = $_POST["race"];
  $streetAdd = $_POST["street-add"];
  if ($_POST["apt-num"] !== '') {
    $aptNum = $_POST["apt-num"];
  }
  else {
    $aptNum = NULL;
  }
  $city = $_POST["city"];
  $state = $_POST["state"];
  $zip = $_POST["zip"];
  $clinic = $_POST["clinic"];

  require_once "dbh.inc.php";
  require_once "functions.inc.php";

  if (emptyInputInfo($fname, $lname, $ssn, $sex, $bdate, $ethnicity, $race, $streetAdd, $city, $state, $zip) !== false) {
    header("location: ../info.php?error=emptyinput");
    exit();
  }
  if (invalidSSN($ssn) !== false) {
    header("location: ../info.php?error=invalidssn");
    exit();
  }

  if (invalidZip($zip) !== false) {
    header("location: ../info.php?error=invalidzip");
    exit();
  }
  createPatient($conn, $fname, $mname, $lname, $ssn, $sex, $bdate, $ethnicity, $race, $streetAdd, $aptNum, $city, $state, $zip, $clinic);
}
else {
  header("location: ../info.php");
  exit();
}
