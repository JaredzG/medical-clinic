<?php
if (isset($_POST["submit"])) {
  require_once 'dbh.inc.php';
  require_once 'functions.inc.php';
  $patID = $_POST["patID"];
  $fname = $_POST["fname"];
  $mname = $_POST["mname"];
  $lname = $_POST["lname"];
  $relationship = $_POST["relationship"];
  $phonenum = $_POST["phonenum"];
  $sex = $_POST["sex"];
  if ($sex === 'male') {
    $sex = 'M';
  }
  else {
    $sex = 'F'; 
  }
  createEmergencyContact($conn, $patID, $fname, $mname, $lname, $relationship, $phonenum, $sex);
}