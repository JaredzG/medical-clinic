<?php

require_once 'dbh.inc.php';
require_once 'functions.inc.php';

$id = intval($_REQUEST["id"]);
$status = $_REQUEST["status"];
$mindate = $_REQUEST["mindate"];
$maxdate = $_REQUEST["maxdate"];
//Update appointment
$sql = "UPDATE Appointment SET status_flag = ?, receptionist_ID = ? WHERE app_ID = ?;";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
  header("location: viewappointments.php?error=updappstatstmtfailed");
  exit();
}
if ($status === 'approve') {
  $statusVal = 1;
}
else if ($status === 'complete') {
  $statusVal = 2;
}
else {
  $statusVal = 3;
}
$result = getReceptionistID($conn, $_SESSION["userID"]);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_bind_param($stmt, "iii", $statusVal, $row["rec_ID"], $id);
mysqli_stmt_execute($stmt);
if ($statusVal = 2) {
  //Get appointment info
  $sql2 = "SELECT * FROM Appointment WHERE app_ID = ?;";
  $stmt2 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt2, $sql2)) {
    header("location: viewappointments.php?error=getappinfofailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt2, "i", $id);
  mysqli_stmt_execute($stmt2);
  $result2 = mysqli_stmt_get_result($stmt2);
  $row2 = mysqli_fetch_assoc($result2);
  //Delete referral for appointment
  $sql3 = "UPDATE Referral SET deleted_flag = true WHERE pat_ID = ? AND specialist_ID = ?;";
  $stmt3 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt3, $sql3)) {
    header("location: viewappointments.php?error=delreffailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt3, "ii", $row2["patient_ID"], $row2["doctor_ID"]);
  mysqli_stmt_execute($stmt3);
}
header("location: ../viewappointments.php?mindate=".$mindate."&maxdate=".$maxdate);
exit();