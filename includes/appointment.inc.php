<?php
session_start();
if (isset($_POST["submit"])) {
	$date = $_POST["adate-time"];
  $doctor = $_POST["doctor"];
  $reason = $_POST["reason"];
  $username = $_POST["username"];
  
  require_once "dbh.inc.php";
  require_once "functions.inc.php";
  
  createAppointment($conn, $date, $doctor, $reason, $username);
}
else {
  header("location: ../appointment.php");
  exit();
}
