<?php
session_start();
if (isset($_POST["submit"])) {
  $streetAdd = $_POST["street-add"];
  $city = $_POST["city"];
  $state = $_POST["state"];
  $zip = $_POST["zip"];

  require_once "dbh.inc.php";
  require_once "functions.inc.php";

  if (emptyInputClinicAdd($streetAdd, $city, $state, $zip) !== false) {
    header("location: ../info.php?error=emptyinput");
    exit();
  }
  if (invalidZip($zip) !== false) {
    header("location: ../info.php?error=invalidzip");
    exit();
  }
  createClinicAdd($conn, $streetAdd, $city, $state, $zip);
}
else {
  header("location: ../info.php");
  exit();
}
