<?php
session_start();
require_once 'dbh.inc.php';
require_once 'functions.inc.php';
if (isset($_POST["submit"])) {
  $sql = "UPDATE Office SET dep_number = ?, phone_number = ? WHERE office_ID = ?;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../updateoffice.php?error=updofffailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "isi", $_POST["officeDepNum"], $_POST["officePhoneNum"], $_POST["officeID"]);
  mysqli_stmt_execute($stmt);
  header("location: ../office.php");
  exit();
}
else {
  header("location: ../office.php");
  exit();
}