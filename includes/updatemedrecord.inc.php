<?php
session_start();
if (isset($_POST["submit"])) {
  require_once 'dbh.inc.php';
  require_once 'functions.inc.php';
  $patID = $_POST["patid"];
  $height = $_POST["height"];
  $weight = $_POST["weight"];
  $allergies = $_POST["allergies"];
  $diagnoses = $_POST["diagnoses"];
  $immunizations= $_POST["immunizations"];
  $progress = $_POST["progress"];
  $treatmentPlan = $_POST["treatment-plan"];
  $medication = $_POST["medication"];
  $prescribe = $_POST["prescribe"];
  $brand = $_POST["brand"];
  $name = $_POST["name"];
  $desc = $_POST["desc"];


  $result = getMedicalRecordFromPatientID($conn, $patID);
  $row = mysqli_fetch_assoc($result);
  if (is_null($row)) {
    $sql = "INSERT INTO Medical_Record (pat_ID, allergies, diagnoses, immunizations, progress, treatment_plan, inch_height, pound_weight) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("location: updatemedrecord.php?error=insmedrecfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt, "isssssii", $patID, $allergies, $diagnoses, $immunizations, $progress, $treatmentPlan, $height, $weight);
    mysqli_stmt_execute($stmt);
    $result2 = getDocID($conn, $_SESSION["userID"]);
    $row = mysqli_fetch_assoc($result2);
    $docID = $row["doc_ID"];
    $sql3 = "INSERT INTO Doctor_Maintains_Medical_Record (pat_ID, doc_ID) VALUES (?, ?);";
    $stmt3 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt3, $sql3)) {
      header("location: updatemedrecord.php?error=insdocmedrecfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt3, "ii", $patID, $docID);
    mysqli_stmt_execute($stmt3);
  }
  else {
    if ($row["allergies"] !== $allergies) {
      $sql2 = "UPDATE Medical_Record SET allergies = ? WHERE pat_ID = ?;";
      $stmt2 = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt2, $sql2)) {
        header("location: updatemedrecord.php?error=updmedrecfailed");
        exit();
      }
      mysqli_stmt_bind_param($stmt2, "si", $allergies, $patID);
      mysqli_stmt_execute($stmt2);
    }
    if ($row["diagnoses"] !== $diagnoses) {
      $sql2 = "UPDATE Medical_Record SET diagnoses = ? WHERE pat_ID = ?;";
      $stmt2 = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt2, $sql2)) {
        header("location: updatemedrecord.php?error=updmedrecfailed");
        exit();
      }
      mysqli_stmt_bind_param($stmt2, "si", $diagnoses, $patID);
      mysqli_stmt_execute($stmt2);
    }
    if ($row["immunizations"] !== $immunizations) {
      $sql2 = "UPDATE Medical_Record SET immunizations = ? WHERE pat_ID = ?;";
      $stmt2 = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt2, $sql2)) {
        header("location: updatemedrecord.php?error=updmedrecfailed");
        exit();
      }
      mysqli_stmt_bind_param($stmt2, "si", $immunizations, $patID);
      mysqli_stmt_execute($stmt2);
    }
    if ($row["progress"] !== $progress) {
      $sql2 = "UPDATE Medical_Record SET progress = ? WHERE pat_ID = ?;";
      $stmt2 = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt2, $sql2)) {
        header("location: updatemedrecord.php?error=updmedrecfailed");
        exit();
      }
      mysqli_stmt_bind_param($stmt2, "si", $progress, $patID);
      mysqli_stmt_execute($stmt2);
    }
    if ($row["treatment_plan"] !== $treatmentPlan) {
      $sql2 = "UPDATE Medical_Record SET treatment_plan = ? WHERE pat_ID = ?;";
      $stmt2 = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt2, $sql2)) {
        header("location: updatemedrecord.php?error=updmedrecfailed");
        exit();
      }
      mysqli_stmt_bind_param($stmt2, "si", $treatmentPlan, $patID);
      mysqli_stmt_execute($stmt2);
    }
    if ($row["inch_height"] !== $height) {
      $sql2 = "UPDATE Medical_Record SET inch_height = ? WHERE pat_ID = ?;";
      $stmt2 = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt2, $sql2)) {
        header("location: updatemedrecord.php?error=updmedrecfailed");
        exit();
      }
      mysqli_stmt_bind_param($stmt2, "ii", $height, $patID);
      mysqli_stmt_execute($stmt2);
    }
    if ($row["pound_weight"] !== $weight) {
      $sql2 = "UPDATE Medical_Record SET pound_weight = ? WHERE pat_ID = ?;";
      $stmt2 = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt2, $sql2)) {
        header("location: updatemedrecord.php?error=updmedrecfailed");
        exit();
      }
      mysqli_stmt_bind_param($stmt2, "ii", $weight, $patID);
      mysqli_stmt_execute($stmt2);
    }
  }
  if ($prescribe == '1') {
    $result3 = getDocID($conn, $_SESSION["userID"]);
    $row = mysqli_fetch_assoc($result3);
    $docID = $row["doc_ID"];
    //Insert into Medicine
    $sql4 = "INSERT INTO Medicine (brand, name, description) VALUES (?, ?, ?);";
    $stmt4 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt4, $sql4)) {
      header("location: updatemedrecord.php?error=insmedfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt4, "sss", $brand, $name, $desc);
    mysqli_stmt_execute($stmt4);
    //Insert into Doctor_Prescribes_Medicine_To_Patient
    $sql5 = "SELECT med_ID FROM Medicine WHERE brand = ? AND name = ? AND description = ? AND deleted_flag = false;";
    $stmt5 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt5, $sql5)) {
      header("location: updatemedrecord.php?error=selmedidfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt5, "sss", $brand, $name, $desc);
    mysqli_stmt_execute($stmt5);
    $result4 = mysqli_stmt_get_result($stmt5);
    $row = mysqli_fetch_assoc($result4);
    $sql6 = "INSERT INTO Doctor_Prescribes_Medicine_To_Patient (doc_ID, med_ID, pat_ID) VALUES (?, ?, ?);";
    $stmt6 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt6, $sql6)) {
      header("location: updatemedrecord.php?error=insdocpresmedfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt6, "iii", $docID, $row["med_ID"], $patID);
    mysqli_stmt_execute($stmt6);
    //Insert into Medical_Record_Contains_Medicine
    $sql7 = "INSERT INTO Medical_Record_Contains_Medicine (pat_ID, med_ID) VALUES (?, ?);";
    $stmt7 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt7, $sql7)) {
      header("location: updatemedrecord.php?error=insmedreccontmedfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt7, "ii", $patID, $row["med_ID"]);
    mysqli_stmt_execute($stmt7);
  }
header("location: ../viewmedrecord.php?patID=".$patID);
exit();
}
else {
  require_once 'dbh.inc.php';
  require_once 'functions.inc.php';
}