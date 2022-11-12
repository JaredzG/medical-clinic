<?php

require_once 'dbh.inc.php';
require_once 'functions.inc.php';

$patID = intval($_REQUEST["id"]);
$appID = intval($_REQUEST["appid"]);

$amount = -50.00;
//Pay the Appointment
$sql = "INSERT INTO Transaction (patient_ID, app_ID, amount) VALUES (?, ?, ?);";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
  header("location: transactions.php?error=inspaystmtfailed");
  exit();
}
mysqli_stmt_bind_param($stmt, "iid", $patID, $appID, $amount);
mysqli_stmt_execute($stmt);
//Get the latest transaction ID
$sql2 = "SELECT MAX(CAST(`transaction_ID` AS UNSIGNED)) AS MAX FROM Transaction;";
$stmt2 = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt2, $sql2)) {
  header("location: transactions.php?error=getpayidstmtfailed");
  exit();
}
mysqli_stmt_execute($stmt2);
$result = mysqli_stmt_get_result($stmt2);
$row = mysqli_fetch_assoc($result);
//Attach the payment to the appointment just paid for
$sql3 = "UPDATE Appointment SET payment_ID = ? WHERE app_ID = ?;";
$stmt3 = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt3, $sql3)) {
  header("location: transactions.php?error=updappstmtfailed");
  exit();
}
mysqli_stmt_bind_param($stmt3, "ii", $row["MAX"], $appID);
mysqli_stmt_execute($stmt3);
//Update payment ID on both transactions (charge for the appointment, and payment for the appointment)
$sql3 = "UPDATE Transaction SET payment_ID = ? WHERE payment_ID IS NULL AND app_ID = ?;";
$stmt3 = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt3, $sql3)) {
  header("location: transactions.php?error=updappstmtfailed");
  exit();
}
mysqli_stmt_bind_param($stmt3, "ii", $row["MAX"], $appID);
mysqli_stmt_execute($stmt3);

header("location: ../transactions.php");
exit();