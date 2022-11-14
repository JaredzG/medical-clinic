<?php
session_start();
if (isset($_POST["submit"])) {
  require_once 'dbh.inc.php';
  require_once 'functions.inc.php';
  $userID = $_POST["userid"];
  $userRole = $_POST["userrole"];
  $fname = $_POST["fname"];
  $mname = $_POST["mname"];
  $lname = $_POST["lname"];
  $streetAdd = $_POST["street-add"];
  $aptNum = $_POST["apt-num"];
  $city = $_POST["city"];
  $state = $_POST["state"];
  $zip = $_POST["zip"];
  if ($userRole === 'patient') {
    $clinic = $_POST["clinic"];
    $sql5 = "SELECT doctor_ID FROM Doctor_Works_In_Office WHERE office_ID = ? AND deleted_flag = false;";
    $stmt5 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt5, $sql5)) {
      header("location: ../updateinfo.php?error=getoffstmtfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt5, "i", $clinic);
    mysqli_stmt_execute($stmt5);
    $result5 = mysqli_stmt_get_result($stmt5);
    $rows = mysqli_fetch_all($result5);
    $i = 0;
    while ($i < count($rows)) {
      $doctors[$i] = intval($rows[$i][0]);
      $i = $i + 1;
    }
    //Get doctors from the specified clinic and that are primary doctors
    $in = str_repeat('?, ', count($doctors) - 1).'?';
    $types = str_repeat('i', count($doctors));
    $primCareNum = 1;
    $sql6 = "SELECT doc_ID FROM Doctor WHERE dep_num = ? AND deleted_flag = false AND doc_ID IN ($in);";
    $stmt6 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt6, $sql6)) {
      header("location: ../updateinfo.php?error=getoffstmtfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt6, "i".$types, $primCareNum, ...$doctors);
    mysqli_stmt_execute($stmt6);
    $result6 = mysqli_stmt_get_result($stmt6);
    $rows2 = mysqli_fetch_all($result6);
    $i = 0;
    $doctors = [];
    while ($i < count($rows2)) {
      $doctors[$i] = intval($rows2[$i][0]);
      $i = $i + 1;
    }
    $sql2 = "UPDATE Patient SET f_name = ?, m_name = ?, l_name = ?, clinic_ID = ?, prim_doc_ID = ? WHERE pat_user = ? AND deleted_flag = false;";
    $stmt2 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt2, $sql2)) {
      header("location: updateinfo.php?error=updinfofailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt2, "sssiii", $fname, $mname, $lname, $clinic, $doctors[0], $userID);
    mysqli_stmt_execute($stmt2);
    $sql3 = "SELECT address_ID FROM Patient WHERE pat_user = ? and deleted_flag = false;";
    $stmt3 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt3, $sql3)) {
      header("location: updateinfo.php?error=getaddfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt3, "i", $userID);
    mysqli_stmt_execute($stmt3);
    $result = mysqli_stmt_get_result($stmt3);
    $row = mysqli_fetch_assoc($result);
  }
  else if ($userRole === 'doctor') {
    $clinic = $_POST["clinic"];
    $depNum = $_POST["num"];
    $credentials = $_POST["credentials"];
    $sql2 = "UPDATE Doctor SET dep_num = ?, f_name = ?, m_name = ?, l_name = ?, credentials = ? WHERE doc_user = ? AND deleted_flag = false;";
    $stmt2 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt2, $sql2)) {
      header("location: updateinfo.php?error=updinfofailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt2, "issssi", $depNum, $fname, $mname, $lname, $credentials, $userID);
    mysqli_stmt_execute($stmt2);
    docToOffice($conn, $userID, $clinic, 'update');
    $sql3 = "SELECT address_ID FROM Doctor WHERE doc_user = ? and deleted_flag = false;";
    $stmt3 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt3, $sql3)) {
      header("location: updateinfo.php?error=getaddfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt3, "i", $userID);
    mysqli_stmt_execute($stmt3);
    $result = mysqli_stmt_get_result($stmt3);
    $row = mysqli_fetch_assoc($result);
  }
  else if ($userRole === 'nurse') {
    $clinic = $_POST["clinic"];
    $depNum = $_POST["num"];
    $sql2 = "UPDATE Nurse SET dep_num = ?, f_name = ?, m_name = ?, l_name = ? WHERE nurse_user = ? AND deleted_flag = false;";
    $stmt2 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt2, $sql2)) {
      header("location: updateinfo.php?error=updinfofailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt2, "isssi", $depNum, $fname, $mname, $lname, $userID);
    mysqli_stmt_execute($stmt2);
    NurseToDoctorAndOffice($conn, $userID, $clinic, 'update');
    $sql3 = "SELECT address_ID FROM Nurse WHERE nurse_user = ? and deleted_flag = false;";
    $stmt3 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt3, $sql3)) {
      header("location: updateinfo.php?error=getaddfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt3, "i", $userID);
    mysqli_stmt_execute($stmt3);
    $result = mysqli_stmt_get_result($stmt3);
    $row = mysqli_fetch_assoc($result);
  }
  else if ($userRole === 'receptionist') {
    $sql2 = "UPDATE Receptionist SET f_name = ?, m_name = ?, l_name = ? WHERE rec_user = ? AND deleted_flag = false;";
    $stmt2 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt2, $sql2)) {
      header("location: updateinfo.php?error=updinfofailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt2, "sssi", $fname, $mname, $lname, $userID);
    mysqli_stmt_execute($stmt2);
    $sql3 = "SELECT address_ID FROM Receptionist WHERE nurse_user = ? and deleted_flag = false;";
    $stmt3 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt3, $sql3)) {
      header("location: updateinfo.php?error=getaddfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt3, "i", $userID);
    mysqli_stmt_execute($stmt3);
    $result = mysqli_stmt_get_result($stmt3);
    $row = mysqli_fetch_assoc($result);
  }
$sql4 = "UPDATE Address SET street_address = ?, apt_num = ?, city = ?, state = ?, zip_code = ? WHERE address_ID = ?;";
$stmt4 = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt4, $sql4)) {
  header("location: updateinfo.php?error=updaddfailed");
  exit();
}
mysqli_stmt_bind_param($stmt4, "sssssi", $streetAdd, $aptNum, $city, $state, $zip, $row["address_ID"]);
mysqli_stmt_execute($stmt4);
if ($_SESSION["userRole"] === 'admin') {
  header("location: ../users.php");
  exit();
}
else if ($_SESSION["userRole"] === 'patient') {
  header("location: ../settings.php");
  exit();
}
}