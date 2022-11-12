<?php
session_start();
if (isset($_POST["submit"])) {
  $depnum = $_POST["num"];
  $address = $_POST["address"];
  $offnum = $_POST["offnum"];

  require_once "dbh.inc.php";
  require_once "functions.inc.php";

  if (emptyInputOffice($depnum, $address, $offnum) !== false) {
    header("location: ../info.php?error=emptyinput");
    exit();
  }
  createOffice($conn, $depnum, $address, $offnum);
}
else {
  header("location: ../info.php");
  exit();
}
