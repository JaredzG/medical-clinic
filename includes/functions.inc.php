<?php
session_start();
/*****************SIGNUP*****************/
function emptyInputSignup($email, $phonenum, $username, $password, $passwordRepeat) {
  $result = false;
  if (empty($email) || empty($phonenum) || empty($username) || empty($password) || empty($passwordRepeat)) {
    $result = true;
  }
  return $result;
}

function invalidEmail($email) {
  $result = false;
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $result = true;
  }
  return $result;
}

function invalidPhoneNum($phonenum) {
  $result = false;
  if (!preg_match("/^[0-9]*$/", $phonenum)) {
    $result = true;
  }
  return $result;
}

function invalidUsername($username) {
  $result = false;
  if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
    $result = true;
  }
  return $result;
}

function passwordMatch($password, $passwordRepeat) {
  $result = false;
  if ($password !== $passwordRepeat) {
    $result = true;
  }
  return $result;
}
/*****************FUNCT USED FOR SIGNUP AND LOGIN*****************/
function usernameOrEmailExists($conn, $username, $email) {
  
  $sql = "SELECT * FROM User_Account WHERE (username = ? OR user_email_address = ?) AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../signup.php?error=stmtfailed");
    exit();
  } 

  mysqli_stmt_bind_param($stmt, "ss", $username, $email);
  mysqli_stmt_execute($stmt);

  $resultData = mysqli_stmt_get_result($stmt);

  if ($row = mysqli_fetch_assoc($resultData)) {
    return $row;
  }
  else {
    $result = false;
    return $result;
  }
  mysqli_stmt_close($stmt);
}

function createUser($conn, $email, $phonenum, $username, $password) {
  $sql = "INSERT INTO User_Account (username, user_pass, user_phone_num, user_email_address) VALUES (?, ?, ?, ?);";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../memp.php?error=stmtfailed");
    exit();
  } 
  
  $hashedPass = password_hash($password, PASSWORD_DEFAULT);
  mysqli_stmt_bind_param($stmt, "ssss", $username, $hashedPass, $phonenum, $email);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  $userExists = usernameOrEmailExists($conn, $username, $email);
  // session_start();
  $_SESSION["userID"] = $userExists["user_ID"];
  $_SESSION["userRole"] = $userExists["user_role"];
  $_SESSION["username"] = $userExists["username"];
  header("location: ../info.php");
  exit();
}
/*****************LOGIN*****************/
function emptyInputLogin($username, $password) {
  $result = false;
  if (empty($username) || empty($password)) {
    $result = true;
  }
  return $result;
}

function getPatient($conn, $userID) {
  
  $sql = "SELECT f_name FROM Patient WHERE pat_user = ? AND deleted_flag = false";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../login.php?error=stmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "i", $userID);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  return $row["f_name"];
}

function loginUser($conn, $username, $password) {
  $userExists = usernameOrEmailExists($conn, $username, $username);
  if (!$userExists) {
    header("location: ../login.php?error=wronglogin");
    exit();
  }
  if ($username !== 'admin1') {
    $hashedPass = $userExists["user_pass"];
    $checkPassword = password_verify($password, $hashedPass);
    if (!$checkPassword) {
      header("location: ../login.php?error=wronglogin");
      exit();
    }
  }
  else {
    if(strcmp($password, $userExists["user_pass"]) !== 0) {
      header("location: ../login.php?error=wronglogin");
      exit();
    }
  }
  $_SESSION["userID"] = $userExists["user_ID"];
  $_SESSION["userRole"] = $userExists["user_role"];
  $_SESSION["username"] = $userExists["username"];
  header("location: ../index.php");
  exit();
}
/*****************INFO*****************/
function emptyInputInfo($fname, $lname, $ssn, $sex, $bdate, $ethnicity, $race, $streetAdd, $city, $state, $zip) {
  $result = false;
  if (empty($fname) || empty($lname) || empty($ssn) ||empty($sex) ||empty($bdate) ||empty($ethnicity) ||empty($race) ||empty($streetAdd) ||empty($city) ||empty($state) ||empty($zip)) {
    $result = true;
  }
  return $result;
}

function invalidSSN($ssn) {
  $result = false;
  if (!preg_match("/^[0-9]*$/", $ssn)) {
    $result = true;
  }
  return $result;
}

function invalidZip($zip) {
  $result = false;
  if (!preg_match("/^[0-9]*$/", $zip)) {
    $result = true;
  }
  return $result;
}

function addressExists($conn, $streetAdd, $aptNum, $city, $state, $zip) {
  
  $sql = "SELECT address_ID FROM Address WHERE street_address = ? AND apt_num = ? AND city = ? AND state = ? AND zip_code = ? AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../info.php?error=findaddstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "ssssi", $streetAdd, $aptNum, $city, $state, $zip);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  if ($row = mysqli_fetch_assoc($result)) {
    return $row;
  }
  else {
    $exists = false;
    return $exists;
  }
}

function createPatient($conn, $fname, $mname, $lname, $ssn, $sex, $bdate, $ethnicity, $race, $streetAdd, $aptNum, $city, $state, $zip, $clinic) {
  
  $ssn = intval($ssn);
  $row = addressExists($conn, $streetAdd, $aptNum, $city, $state, $zip);
  if (!$row) {
    //Insert address into DB
    $sql = "INSERT INTO Address (street_address, apt_num, city, state, zip_code) VALUES (?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("location: ../info.php?error=stmtfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt, "sssss", $streetAdd, $aptNum, $city, $state, $zip);
    mysqli_stmt_execute($stmt);

    //Get address ID of address just inserted
    $sql2 = "SELECT address_ID FROM Address WHERE street_address = ? AND zip_code = ? AND deleted_flag = false;";
    $stmt2 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt2, $sql2)) {
      header("location: ../info.php?error=stmtfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt2, "si", $streetAdd, $zip);
    mysqli_stmt_execute($stmt2);
    $result = mysqli_stmt_get_result($stmt2);
    $row = mysqli_fetch_assoc($result);
  }
  //Insert new patient into DB
  if ($sex === 'male') {
    $sex = 'M';
  }
  else {
    $sex = 'F'; 
  }

  //Get doctors that work at specified clinic
  $sql5 = "SELECT doctor_ID FROM Doctor_Works_In_Office WHERE office_ID = ? AND deleted_flag = false;";
  $stmt5 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt5, $sql5)) {
    header("location: ../appointment.php?error=getoffstmtfailed");
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
    header("location: ../appointment.php?error=getoffstmtfailed");
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

  $sql3 = "INSERT INTO Patient (ssn, f_name, m_name, l_name, sex, pat_user, address_ID, clinic_ID, prim_doc_ID) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?);";
  $stmt3 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt3, $sql3)) {
    header("location: ../info.php?error=stmtfailed");
    exit();
  } 
  mysqli_stmt_bind_param($stmt3, "issssisii", $ssn, $fname, $mname, $lname, $sex, $_SESSION["userID"], $row["address_ID"], $clinic, $doctors[0]);
  mysqli_stmt_execute($stmt3);
  $_SESSION["fname"] = $fname;

  $sql4 = "SELECT patient_ID FROM Patient WHERE pat_user = ? AND deleted_flag = false;";
  $stmt4 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt4, $sql4)) {
    header("location: ../info.php?error=getpatidfailed");
    exit();
  } 
  mysqli_stmt_bind_param($stmt4, "i", $_SESSION["userID"]);
  mysqli_stmt_execute($stmt4);
  $result = mysqli_stmt_get_result($stmt4);
  $row = mysqli_fetch_assoc($result);
  $patID = intval($row["patient_ID"]);

  //Insert new info into a new Medical Record
  $sql7 = "INSERT INTO Medical_Record (pat_ID, b_date, ethnicity, race) VALUES (?, ?, ?, ?);";
  $stmt7 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt7, $sql7)) {
    header("location: ../info.php?error=insdeminfofailed");
    exit();
  } 
  mysqli_stmt_bind_param($stmt7, "isss", $patID, $bdate, $ethnicity, $race);
  mysqli_stmt_execute($stmt7);

  //Insert into Doctor_For_Patient
  $sql8 = "INSERT INTO Doctor_For_Patient (doc_ID, pat_ID) VALUES (?, ?);";
  $stmt8 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt8, $sql8)) {
    header("location: ../info.php?error=insdocforpatfailed");
    exit();
  } 
  mysqli_stmt_bind_param($stmt8, "ii", $doctors[0], $patID);
  mysqli_stmt_execute($stmt8);

  header("location: ../emergencycontact.php?patID=".$patID);
  exit();
}

/*****************DEPARTMENT NAME*****************/
function emptyInputDeptName($dptname) {
  $result = false;
  if (empty($dptname)) {
    $result = true;
  }
  return $result;
}

function deptNameExists($conn, $dptname) {
  
  $sql = "SELECT department_number FROM Department WHERE dep_name = ? AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../dept.php?error=namestmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "s", $dptname);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  if ($row = mysqli_fetch_assoc($result)) {
    return $row;
  }
  else {
    $exists2 = false;
    return $exists2;
  }
}

function createDepartment($conn, $dptname) {
  $sql = "INSERT INTO Department (dep_name) VALUES (?);";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../dept.php?error=createstmtfailed");
    exit();
  } 
  mysqli_stmt_bind_param($stmt, "s", $dptname);
  mysqli_stmt_execute($stmt);
  header("location: ../dept.php");
  exit();
}

function viewDepartments($conn) {
  
  $sql = "SELECT * FROM Department WHERE deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../dept.php?error=viewstmtfailed");
    exit();
  }
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

/*****************CLINIC ADDRESSES*****************/
function emptyInputClinicAdd($streetAdd, $city, $state, $zip) {
  $result = false;
  if (empty($streetAdd) ||empty($city) ||empty($state) ||empty($zip)) {
    $result = true;
  }
  return $result;
}

function createClinicAdd($conn, $streetAdd, $city, $state, $zip) {
  $officeAdd = 1;
  $sql = "INSERT INTO Address (street_address, city, state, zip_code, office_add) VALUES (?, ?, ?, ?, ?);";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../info.php?error=stmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "ssssi", $streetAdd, $city, $state, $zip, $officeAdd);
  mysqli_stmt_execute($stmt);
  header("location: ../clinicadd.php");
  exit();
}

function viewClinicLocations($conn) {
  
  $officeAdd = 1;
  $sql = "SELECT * FROM Address WHERE office_add = ? AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../dept.php?error=viewstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "i", $officeAdd);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

/*****************OFFICES*****************/
function emptyInputOffice($deptNum, $address, $offnum) {
  $result = false;
  if (empty($deptNum) ||empty($address) ||empty($offnum)) {
    $result = true;
  }
  return $result;
}

function createOffice($conn, $deptNum, $address, $offnum) {
  $officeAdd = 1;
  $sql = "INSERT INTO Office (dep_number, address_ID, phone_number) VALUES (?, ?, ?);";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../info.php?error=stmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "iis", $deptNum, $address, $offnum);
  mysqli_stmt_execute($stmt);
  header("location: ../office.php");
  exit();
}

function viewOffices($conn) {
  
  $sql = "SELECT * FROM Office WHERE deleted_flag = false";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../dept.php?error=viewstmtfailed");
    exit();
  }
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function getDepartmentName($conn, $deptNum) {
  
  $sql = "SELECT dep_name FROM Department WHERE department_number = ? AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../dept.php?error=viewstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "i", $deptNum);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function getAddress($conn, $addID) {
  
  $sql = "SELECT * FROM Address WHERE address_ID = ? AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../info.php?error=stmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "i", $addID);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

/*****************CREATING/VIEWING EMPLOYEES*****************/
function createEmpUser($conn, $email, $phonenum, $username, $password, $role) {
  $sql = "INSERT INTO User_Account (username, user_pass, user_role, user_phone_num, user_email_address) VALUES (?, ?, ?, ?, ?);";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../memp.php?error=stmtfailed");
    exit();
  } 
  $hashedPass = password_hash($password, PASSWORD_DEFAULT);
  mysqli_stmt_bind_param($stmt, "sssss", $username, $hashedPass, $role, $phonenum, $email);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  $userExists = usernameOrEmailExists($conn, $username, $email);
  // session_start();
  $_SESSION["newuserID"] = $userExists["user_ID"];
  $_SESSION["newuserRole"] = $userExists["user_role"];
  $_SESSION["newuserName"] = $userExists["username"];
  header("location: ../empinfo.php");
  exit();
}

function createDoctor($conn, $fname, $mname, $lname, $ssn, $sex, $streetAdd, $aptNum, $city, $state, $zip, $deptNum, $credentials) {
  $row = addressExists($conn, $streetAdd, $aptNum, $city, $state, $zip);
  
  if (!$row) {
    //Insert address into DB
    $sql = "INSERT INTO Address (street_address, apt_num, city, state, zip_code) VALUES (?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("location: ../empinfo.php?error=stmtfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt, "sssss", $streetAdd, $aptNum, $city, $state, $zip);
    mysqli_stmt_execute($stmt);

    //Get address ID of address just inserted
    $sql2 = "SELECT address_ID FROM Address WHERE street_address = ? AND zip_code = ? AND deleted_flag = false;";
    $stmt2 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt2, $sql2)) {
      header("location: ../empinfo.php?error=stmtfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt2, "si", $streetAdd, $zip);
    mysqli_stmt_execute($stmt2);
    $result = mysqli_stmt_get_result($stmt2);
    $row = mysqli_fetch_assoc($result);
  }
  if ($sex === 'male') {
    $sex = 'M';
  }
  else {
    $sex = 'F'; 
  }
  $sql3 = "INSERT INTO Doctor (ssn, dep_num, f_name, m_name, l_name, address_ID, credentials, sex, doc_user) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
  $stmt3 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt3, $sql3)) {
    header("location: ../empinfo.php?error=stmtfailed");
    exit();
  } 
  mysqli_stmt_bind_param($stmt3, "iisssissi", $ssn, $deptNum, $fname, $mname, $lname, $row["address_ID"], $credentials, $sex, $_SESSION["newuserID"]);
  mysqli_stmt_execute($stmt3);
  docToOffice($conn, $_SESSION["newuserID"]);
  $_SESSION["fname"] = 'admin';
  header("location: ../index.php");
  exit();
}

function createNurse($conn, $fname, $mname, $lname, $ssn, $sex, $streetAdd, $aptNum, $city, $state, $zip, $deptNum, $registered) {
  $row = addressExists($conn, $streetAdd, $aptNum, $city, $state, $zip);
  
  if (!$row) {
    //Insert address into DB
    $sql = "INSERT INTO Address (street_address, apt_num, city, state, zip_code) VALUES (?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("location: ../empinfo.php?error=stmtfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt, "sssss", $streetAdd, $aptNum, $city, $state, $zip);
    mysqli_stmt_execute($stmt);

    //Get address ID of address just inserted
    $sql2 = "SELECT address_ID FROM Address WHERE street_address = ? AND zip_code = ? AND deleted_flag = false;";
    $stmt2 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt2, $sql2)) {
      header("location: ../empinfo.php?error=stmtfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt2, "si", $streetAdd, $zip);
    mysqli_stmt_execute($stmt2);
    $result = mysqli_stmt_get_result($stmt2);
    $row = mysqli_fetch_assoc($result);
  }
  if ($sex === 'male') {
    $sex = 'M';
  }
  else {
    $sex = 'F'; 
  }
  $sql3 = "INSERT INTO Nurse (ssn, dep_num, f_name, m_name, l_name, sex, nurse_user, registered, address_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
  $stmt3 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt3, $sql3)) {
    header("location: ../empinfo.php?error=stmtfailed");
    exit();
  } 
  mysqli_stmt_bind_param($stmt3, "iissssiii", $ssn, $deptNum, $fname, $mname, $lname, $sex, $_SESSION["newuserID"], $registered, $row["address_ID"]);
  mysqli_stmt_execute($stmt3);
  NurseToDoctorAndOffice($conn, $_SESSION["newuserID"]);
  $_SESSION["fname"] = 'admin';
  header("location: ../index.php");
  exit();
}

function docToOffice($conn, $docUserID) {
  $docUserID = intval($docUserID);
  $sql = 'SELECT doc_ID, dep_num FROM Doctor WHERE doc_user = ?;';
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../empinfo.php?error=docstmtfailed");
    exit();
  } 
  mysqli_stmt_bind_param($stmt, "i", $docUserID);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  $docID = intval($row["doc_ID"]);
  $deptNum = intval($row["dep_num"]);

  $sql2 = 'SELECT office_ID FROM Office WHERE dep_number = ?;';
  $stmt2 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt2, $sql2)) {
    header("location: ../empinfo.php?error=offstmtfailed");
    exit();
  } 
  mysqli_stmt_bind_param($stmt2, "i", $deptNum);
  mysqli_stmt_execute($stmt2);
  $result2 = mysqli_stmt_get_result($stmt2);
  $row2 = mysqli_fetch_assoc($result2);
  $offID = intval($row2["office_ID"]);

  $sql3 = "INSERT INTO Doctor_Works_In_Office (office_ID, doctor_ID) VALUES (?, ?);";
  $stmt3 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt3, $sql3)) {
    header("location: ../empinfo.php?error=docoffstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt3, "ii", $offID, $docID);
  mysqli_stmt_execute($stmt3);
}

function NurseToDoctorAndOffice($conn, $nurseUserID) {
  $nurseUserID = intval($nurseUserID);
  $sql = 'SELECT nurse_ID, dep_num FROM Nurse WHERE nurse_user = ?;';
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../empinfo.php?error=nurstmtfailed");
    exit();
  } 
  mysqli_stmt_bind_param($stmt, "i", $nurseUserID);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  $nurseID = intval($row["nurse_ID"]);
  $deptNum = intval($row["dep_num"]);

  $sql2 = 'SELECT office_ID FROM Office WHERE dep_number = ? AND deleted_flag = false;';
  $stmt2 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt2, $sql2)) {
    header("location: ../empinfo.php?error=offstmtfailed");
    exit();
  } 
  mysqli_stmt_bind_param($stmt2, "i", $deptNum);
  mysqli_stmt_execute($stmt2);
  $result2 = mysqli_stmt_get_result($stmt2);
  $row2 = mysqli_fetch_assoc($result2);
  $offID = intval($row2["office_ID"]);

  $sql3 = "INSERT INTO Nurse_Works_In_Office (office_ID, nurse_ID) VALUES (?, ?);";
  $stmt3 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt3, $sql3)) {
    header("location: ../empinfo.php?error=nuroffstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt3, "ii", $offID, $nurseID);
  mysqli_stmt_execute($stmt3);

  $sql4 = 'SELECT doc_ID FROM Doctor WHERE dep_num = ?;';
  $stmt4 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt4, $sql4)) {
    header("location: ../empinfo.php?error=nurdocstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt4, "i", $deptNum);
  mysqli_stmt_execute($stmt4);
  $result3 = mysqli_stmt_get_result($stmt4);
  while ($row3 = mysqli_fetch_assoc($result3)) {
    $row3["doc_ID"] = intval($row3["doc_ID"]);
    $sql5 = "INSERT INTO Nurse_Works_With_Doctor (nurse_ID, doc_ID) VALUES (?, ?);";
    $stmt5 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt5, $sql5)) {
      header("location: ../empinfo.php?error=nurdocstmtfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt5, "ii", $nurseID, $row3["doc_ID"]);
    mysqli_stmt_execute($stmt5);
  }
}

function createReceptionist($conn, $fname, $mname, $lname, $ssn, $sex, $streetAdd, $aptNum, $city, $state, $zip) {
  $row = addressExists($conn, $streetAdd, $aptNum, $city, $state, $zip);
  
  if (!$row) {
    //Insert address into DB
    $sql = "INSERT INTO Address (street_address, apt_num, city, state, zip_code) VALUES (?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("location: ../empinfo.php?error=maddstmtfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt, "sssss", $streetAdd, $aptNum, $city, $state, $zip);
    mysqli_stmt_execute($stmt);

    //Get address ID of address just inserted
    $sql2 = "SELECT address_ID FROM Address WHERE street_address = ? AND zip_code = ? AND deleted_flag = false;";
    $stmt2 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt2, $sql2)) {
      header("location: ../empinfo.php?error=seladdstmtfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt2, "si", $streetAdd, $zip);
    mysqli_stmt_execute($stmt2);
    $result = mysqli_stmt_get_result($stmt2);
    $row = mysqli_fetch_assoc($result);
  }
  if ($sex === 'male') {
    $sex = 'M';
  }
  else {
    $sex = 'F'; 
  }
  $sql3 = "INSERT INTO Receptionist (ssn, f_name, m_name, l_name, sex, rec_user, address_ID) VALUES (?, ?, ?, ?, ?, ?, ?);";
  $stmt3 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt3, $sql3)) {
    header("location: ../empinfo.php?error=recstmtfailed");
    exit();
  } 
  mysqli_stmt_bind_param($stmt3, "issssii", $ssn, $fname, $mname, $lname, $sex, $_SESSION["newuserID"], $row["address_ID"]);
  mysqli_stmt_execute($stmt3);
  $_SESSION["fname"] = 'admin';
  header("location: ../index.php");
  exit();
}

function emptyInputEmpInfo($fname, $lname, $ssn, $sex, $streetAdd, $city, $state, $zip) {
  $result = false;
  if (empty($fname) || empty($lname) || empty($ssn) ||empty($sex) ||empty($streetAdd) ||empty($city) ||empty($state) ||empty($zip)) {
    $result = true;
  }
  return $result;
}

function getDepartment($conn, $deptNum) {
  
  $sql = "SELECT * FROM Department WHERE department_number = ? AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../vemp.php?error=viewstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "i", $deptNum);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function getEmployees($conn, $deptNum, $empRole) {
  $stmt = mysqli_stmt_init($conn);
  if ($deptNum !== '0') {
    $deptNum = intval($deptNum);
    if ($empRole === 1) {
      $sql = "SELECT user_ID, f_name, l_name, user_role FROM User_Account, Doctor WHERE user_ID = doc_user AND dep_num = ? AND User_Account.deleted_flag = false AND Doctor.deleted_flag = false;";
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: vemp.php?error=docviewstmtfailed");
        exit();
      }
      mysqli_stmt_bind_param($stmt, "i", $deptNum);
    }
    else if ($empRole === 2) {
      $sql = "SELECT user_ID, f_name, l_name, user_role FROM User_Account, Nurse WHERE user_ID = nurse_user AND dep_num = ? AND User_Account.deleted_flag = false AND Nurse.deleted_flag = false;";
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: vemp.php?error=nurviewstmtfailed");
        exit();
      }
      mysqli_stmt_bind_param($stmt, "i", $deptNum);
    }
  }
  else {
    $sql = "SELECT user_ID, f_name, l_name, user_role FROM User_Account, Receptionist WHERE user_ID = rec_user AND User_Account.deleted_flag = false AND Receptionist.deleted_flag = false;";
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("location: vemp.php?error=nonviewstmtfailed");
      exit();
    }
  }
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

/*****************APPPOINTMENTS*****************/
function getDoctors($conn) {
  
  $sql = "SELECT dep_num, doc_ID, f_name, l_name FROM Doctor WHERE deleted_flag = false";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: appoinment.php?error=getdocstmtfailed");
    exit();
  }
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function getPatientID($conn, $username) {
  
  $sql = "SELECT patient_ID FROM Patient WHERE pat_user = (SELECT user_ID FROM User_Account WHERE username = ?) AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../appointment.php?error=getpatstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "s", $username);
  mysqli_stmt_execute($stmt);
  $result1 = mysqli_stmt_get_result($stmt);
  $row1 = mysqli_fetch_assoc($result1);
  return $row1;
}

function getOfficeID($conn, $doctor) {
  
  $doctor = intval($doctor);
  $sql2 = "SELECT office_ID FROM Doctor_Works_In_Office WHERE doctor_ID = ? AND deleted_flag = false;";
  $stmt2 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt2, $sql2)) {
    header("location: ../appointment.php?error=getoffstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt2, "i", $doctor);
  mysqli_stmt_execute($stmt2);
  $result2 = mysqli_stmt_get_result($stmt2);
  $row2 = mysqli_fetch_assoc($result2);
  return $row2;
}

function getNurseID($conn, $doctor) {
  
  $doctor = intval($doctor);
  $sql3 = "SELECT nurse_ID FROM Nurse_Works_With_Doctor WHERE doc_ID = ? AND deleted_flag = false;";
  $stmt3 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt3, $sql3)) {
    header("location: ../appointment.php?error=getnurstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt3, "i", $doctor);
  mysqli_stmt_execute($stmt3);
  $result3 = mysqli_stmt_get_result($stmt3);
  $row3 = mysqli_fetch_assoc($result3);
  return $row3;
}

function createAppointment($conn, $date, $doctor, $reason, $username) {
  
  //Step 1: Get Patient ID from querying Patient using $username
  $row1 = getPatientID($conn, $username);
  // //Step 2: Get Office ID from querying Doctor_Works_In_Office using $doctor
  $row2 = getOfficeID($conn, $doctor);
  //Step 3: Get Nurse ID from querying Nurse_Works_With_Doctor using $doctor
  $row3 = getNurseID($conn, $doctor);
  //Step 4: Parse $date to remove the 'T' and replace it with a ' ' (space)
  $date = str_replace("T", " ", $date);
  //Step 5a: Insert appointment with date_time, reason, office_ID, doctor_ID, patient_ID
  $sql4 = "INSERT INTO Appointment (date_time, reason, office_ID, doctor_ID, patient_ID) VALUES (?, ?, ?, ?, ?);";
  $stmt4 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt4, $sql4)) {
    header("location: ../appoinment.php?error=addappstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt4, "ssiii", $date, $reason, $row2["office_ID"], $doctor, $row1["patient_ID"]);
  mysqli_stmt_execute($stmt4);

  $sql8 = "SELECT app_ID FROM Appointment WHERE patient_ID = ? AND date_time = ?;";
  $stmt8 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt8, $sql8)) {
    header("location: ../appointment.php?error=getappstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt8, "is", $row1["patient_ID"], $date);
  mysqli_stmt_execute($stmt8);
  $result8 = mysqli_stmt_get_result($stmt8);
  $row8 = mysqli_fetch_assoc($result8);
  debug_to_console($row8);
  if (is_null($row8)) {
    header("location: ../appointment.php?error=createappfailed");
    exit();
  }
  else {
    //Step 5b: Check if record in Doctor_For_Patient exists. If it does, do not insert.
    $sql4c = "SELECT * FROM Doctor_For_Patient WHERE doc_ID = ? and pat_ID = ? AND deleted_flag = false;";
    $stmt4c = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt4c, $sql4c)) {
      header("location: ../appoinment.php?error=patdocstmtfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt4c, "ii", $doctor, $row1["patient_ID"]);
    mysqli_stmt_execute($stmt4c);
    $result6 = mysqli_stmt_get_result($stmt4c);
    $row6 = mysqli_fetch_assoc($result6);
    if (!$row6) {
      //Step 5c: Insert into Doctor_For_Patient
      $sql4b = "INSERT INTO Doctor_For_Patient (doc_ID, pat_ID) VALUES (?, ?);";
      $stmt4b = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt4b, $sql4b)) {
        header("location: ../appoinment.php?error=patdocstmtfailed");
        exit();
      }
      mysqli_stmt_bind_param($stmt4b, "ii", $doctor, $row1["patient_ID"]);
      mysqli_stmt_execute($stmt4b);
    }
    //Step 6: Select appointment ID from querying Appointment using patient_ID and date_time
    $sql5 = "SELECT app_ID FROM Appointment WHERE patient_ID = ? AND date_time = ?;";
    $stmt5 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt5, $sql5)) {
      header("location: ../appointment.php?error=getappstmtfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt5, "is", $row1["patient_ID"], $date);
    mysqli_stmt_execute($stmt5);
    $result5 = mysqli_stmt_get_result($stmt5);
    $row5 = mysqli_fetch_assoc($result5);
    //Step 7: Insert into Nurse_Works_On_Appointment using nurse_ID and app_ID
    $sql6 = "INSERT INTO Nurse_Works_On_Appointment (nurse_ID, appointment_ID) VALUES (?, ?);";
    $stmt6 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt6, $sql6)) {
      header("location: ../appointment.php?error=addnwastmtfailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt6, "ii", $row3["nurse_ID"], $row5["app_ID"]);
    mysqli_stmt_execute($stmt6);

    header("location: ../viewappointments.php");
    exit();
  }
}

function viewPatientScheduledApps($conn, $patID, $mindate, $maxdate) {
  $statflag0 = 0;
  $sql = "SELECT * FROM Appointment WHERE patient_ID = ? AND (date_time BETWEEN ? AND ?) AND status_flag = ?;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: viewappointments.php?error=getsorastmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, 'issi', $patID, $mindate, $maxdate, $statflag0);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function viewPatientApprovedApps($conn, $patID, $mindate, $maxdate) {
  $statflag1 = 1;
  $sql = "SELECT * FROM Appointment WHERE patient_ID = ? AND (date_time BETWEEN ? AND ?) AND status_flag = ?;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: viewappointments.php?error=getsorastmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, 'issi', $patID, $mindate, $maxdate, $statflag1);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function viewPatientCancelledApps($conn, $patID, $mindate, $maxdate) {
  $statflag3 = 3;
  $sql = "SELECT * FROM Appointment WHERE patient_ID = ? AND (date_time BETWEEN ? AND ?) AND status_flag = ?;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: viewappointments.php?error=getsorastmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, 'issi', $patID, $mindate, $maxdate, $statflag3);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function getDoctorCompletedApps($conn, $mindate, $maxdate) {
  
  $mindate = str_replace("T", " ", $mindate);
  $maxdate = str_replace("T", " ", $maxdate);
  //Get doctor ID
  $sql = "SELECT doc_ID FROM Doctor WHERE doc_user = ? AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: viewappointments.php?error=getdocstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "i", $_SESSION["userID"]);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  $docID = intval($row["doc_ID"]);
  $statusVal = 2;
  $sql = "SELECT * FROM Appointment WHERE doctor_ID = ? AND status_flag = ? AND (date_time BETWEEN ? AND ?);";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: viewappointments.php?error=getcomappsstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "iiss", $docID, $statusVal, $mindate, $maxdate);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function getDoctorIncompleteApps($conn, $mindate, $maxdate) {
  
  $mindate = str_replace("T", " ", $mindate);
  $maxdate = str_replace("T", " ", $maxdate);
  //Get doctor ID
  $sql = "SELECT doc_ID FROM Doctor WHERE doc_user = ? AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: viewappointments.php?error=getdocstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "i", $_SESSION["userID"]);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  $docID = intval($row["doc_ID"]);
  $statusVal = 1;
  $sql = "SELECT * FROM Appointment WHERE doctor_ID = ? AND status_flag = ? AND (date_time BETWEEN ? AND ?);";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: viewappointments.php?error=getcomappsstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "iiss", $docID, $statusVal, $mindate, $maxdate);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function getNurseCompletedApps($conn, $mindate, $maxdate) {
  
  $mindate = str_replace("T", " ", $mindate);
  $maxdate = str_replace("T", " ", $maxdate);
  //Get nurse ID
  $sql = "SELECT nurse_ID FROM Nurse WHERE nurse_user = ? AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: viewappointments.php?error=getnursestmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "i", $_SESSION["userID"]);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  $nurseID = intval($row["nurse_ID"]);
  $sql2 = "SELECT appointment_ID FROM Nurse_Works_On_Appointment WHERE nurse_ID = ? AND deleted_flag = false;";
  $stmt2 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt2, $sql2)) {
    header("location: viewappointments.php?error=getappsstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt2, "i", $nurseID);
  mysqli_stmt_execute($stmt2);
  $result2 = mysqli_stmt_get_result($stmt2);
  $rows = mysqli_fetch_all($result2);
  $i = 0;
  while ($i < count($rows)) {
    $apps[$i] = intval($rows[$i][0]);
    $i = $i + 1;
  }
  $in = str_repeat('?, ', count($apps) - 1).'?';
  $types = str_repeat('i', count($apps));
  $statusVal = 2;
  $sql3 = "SELECT * FROM Appointment WHERE status_flag = ? AND (date_time BETWEEN ? AND ?) AND app_ID IN ($in);";
  $stmt3 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt3, $sql3)) {
    header("location: viewappointments.php?error=getcomappsstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt3, "iss".$types, $statusVal, $mindate, $maxdate, ...$apps);
  mysqli_stmt_execute($stmt3);
  $result3 = mysqli_stmt_get_result($stmt3);
  return $result3;
}

function getNurseIncompleteApps($conn, $mindate, $maxdate) {
  
  $mindate = str_replace("T", " ", $mindate);
  $maxdate = str_replace("T", " ", $maxdate);
  //Get nurse ID
  $sql = "SELECT nurse_ID FROM Nurse WHERE nurse_user = ? AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: viewappointments.php?error=getnursestmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "i", $_SESSION["userID"]);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  $nurseID = intval($row["nurse_ID"]);
  $sql2 = "SELECT appointment_ID FROM Nurse_Works_On_Appointment WHERE nurse_ID = ? AND deleted_flag = false;";
  $stmt2 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt2, $sql2)) {
    header("location: viewappointments.php?error=getappsstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt2, "i", $nurseID);
  mysqli_stmt_execute($stmt2);
  $result2 = mysqli_stmt_get_result($stmt2);
  $rows = mysqli_fetch_all($result2);
  $i = 0;
  while ($i < count($rows)) {
    $apps[$i] = intval($rows[$i][0]);
    $i = $i + 1;
  }
  $in = str_repeat('?, ', count($apps) - 1).'?';
  $types = str_repeat('i', count($apps));
  $statusVal = 1;
  $sql3 = "SELECT * FROM Appointment WHERE status_flag = ? AND (date_time BETWEEN ? AND ?) AND app_ID IN ($in);";
  $stmt3 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt3, $sql3)) {
    header("location: viewappointments.php?error=getcomappsstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt3, "iss".$types, $statusVal, $mindate, $maxdate, ...$apps);
  mysqli_stmt_execute($stmt3);
  $result3 = mysqli_stmt_get_result($stmt3);
  return $result3;
}

function getScheduledApps($conn, $mindate, $maxdate) {
  $statusVal = 0;
  $sql = "SELECT * FROM Appointment WHERE status_flag = ? AND (date_time BETWEEN ? AND ?);";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: viewappointments.php?error=getschappsstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "iss", $statusVal, $mindate, $maxdate);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function getApprovedApps($conn, $mindate, $maxdate) {
  $statusVal = 1;
  $sql = "SELECT * FROM Appointment WHERE status_flag = ? AND (date_time BETWEEN ? AND ?);";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: viewappointments.php?error=getappappsstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "iss", $statusVal, $mindate, $maxdate);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function getCompletedApps($conn, $mindate, $maxdate) {
  $statusVal = 2;
  $sql = "SELECT * FROM Appointment WHERE status_flag = ? AND (date_time BETWEEN ? AND ?);";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: viewappointments.php?error=getcomappsstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "iss", $statusVal, $mindate, $maxdate);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function getCancelledApps($conn, $mindate, $maxdate) {
  $statusVal = 3;
  $sql = "SELECT * FROM Appointment WHERE status_flag = ? AND (date_time BETWEEN ? AND ?);";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: viewappointments.php?error=getcanappsstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "iss", $statusVal, $mindate, $maxdate);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function getReceptionistID($conn, $userID) {
  
  $sql = "SELECT rec_ID FROM Receptionist WHERE rec_user = ? AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: viewappointments.php?error=getcanappsstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "i", $userID);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

/*****************transactions*****************/

//       MODIFY FOR NEW WAY TO GET BALANCE
function getBalance($conn, $patID) {
  $patID = intval($patID);
  $balance = 0;
  $sql = "SELECT SUM(amount) FROM Transaction WHERE patient_ID = ? AND payment_ID is NULL;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: transactions.php?error=getbalstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "i", $patID);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  $balance = $row["amount"];
  return $balance;
}

function viewAllPatientUnpaidAppointments($conn, $patID) {
  $patID = intval($patID);
  $status_flag = 2;
  $sql = "SELECT * FROM Appointment WHERE patient_ID = ? AND status_flag = ? AND payment_ID IS NULL;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: viewappointments.php?error=getunappstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "ii", $patID, $status_flag);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function viewPatientUnpaidAppointments($conn, $patID, $mindate, $maxdate) {
  $patID = intval($patID);
  $status_flag = 2;
  $sql = "SELECT * FROM Appointment WHERE patient_ID = ? AND status_flag = ? AND payment_ID IS NULL AND (date_time BETWEEN ? and ?);";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: transactions.php?error=getunappstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "iiss", $patID, $status_flag, $mindate, $maxdate);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function viewPatientPaidAppointments($conn, $patID, $mindate, $maxdate) {
  $patID = intval($patID);
  $status_flag = 2;
  $sql = "SELECT * FROM Appointment WHERE patient_ID = ? AND status_flag = ? AND payment_ID IS NOT NULL AND (date_time BETWEEN ? and ?);";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: viewappointments.php?error=getunappstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "iiss", $patID, $status_flag, $mindate, $maxdate);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}


//         MODIFY FOR NEW WAY OF KEEPING TRACK OF TRANSACTIONS
function viewPatientTransactions($conn, $patID, $mindate, $maxdate) {
  $patID = intval($patID);
  $sql = "SELECT * FROM Transaction WHERE patient_ID = ? AND (transaction_date BETWEEN ? AND ?);";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: transactions.php?error=gettransactionsfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "iss", $patID, $mindate, $maxdate);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

//         MODIFY FOR NEW WAY OF KEEPING TRACK OF TRANSACTIONS
function viewTransactions($conn, $mindate, $maxdate) {
  $sql = "SELECT * FROM Transaction WHERE (transaction_date BETWEEN ? AND ?);";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: transactions.php?error=gettransactionsfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "ss", $mindate, $maxdate);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function viewPatientDues($conn, $mindate, $maxdate, $lname, $bdate) {
  $sql = "SELECT * FROM Transaction WHERE (transaction_date BETWEEN ? AND ?) AND amount > 0 AND payment_ID IS NULL AND patient_ID = (SELECT patient_ID FROM Patient WHERE l_name = ? AND patient_ID = (SELECT pat_ID FROM Medical_Record WHERE b_date = ?));";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: transactions.php?error=gettransactionsfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "ssss", $mindate, $maxdate, $lname, $bdate);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

/*****************MEDICAL RECORD*****************/
function getMedicalRecordFromUserID($conn, $userID) {
  
  $sql = "SELECT * FROM Patient WHERE pat_user = ? AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: viewmedrecord.php?error=getpatidfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, 'i', $userID);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  $patID = intval($row["patient_ID"]);
  $sql2 = "SELECT * FROM Medical_Record WHERE pat_ID = ? AND deleted_flag = false;";
  $stmt2 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt2, $sql2)) {
    header("location: viewmedrecord.php?error=getmedrecfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt2, 'i', $patID);
  mysqli_stmt_execute($stmt2);
  $result2 = mysqli_stmt_get_result($stmt2);
  return $result2;
}

function getMedicalRecordFromPatientID($conn, $patID) {
  
  $sql = "SELECT * FROM Medical_Record WHERE pat_ID = ? AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: viewmedrecord.php?error=getmedrecfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, 'i', $patID);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function getMedicineID($conn, $patID) {
  
  $sql = "SELECT * FROM Medical_Record_Contains_Medicine WHERE pat_ID = ? AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: viewmedrecord.php?error=getmedidsfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, 'i', $patID);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function getMedicineName($conn, $medID) {
  
  $sql = "SELECT * FROM Medicine WHERE med_ID = ? AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: viewmedrecord.php?error=getmednamefailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, 'i', $medID);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function getDocID($conn, $userID) {
  
  $sql = "SELECT * FROM Doctor WHERE doc_user = ? AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: medrecord.php?error=getdocidfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, 'i', $userID);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function getDocPatients ($conn, $docID) {
  
  $sql = "SELECT pat_ID FROM Doctor_For_Patient WHERE doc_ID = ? AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: medrecord.php?error=getpatidsfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, 'i', $docID);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $rows = mysqli_fetch_all($result);
  $i = 0;
  while ($i < count($rows)) {
    $pats[$i] = intval($rows[$i][0]);
    $i = $i + 1;
  }
  $in = str_repeat('?, ', count($pats) - 1).'?';
  $types = str_repeat('i', count($pats));
  $sql2 = "SELECT * FROM Patient WHERE deleted_flag = false AND patient_ID IN ($in);";
  $stmt2 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt2, $sql2)) {
    header("location: viewappointments.php?error=getpatsstmtfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt2, $types, ...$pats);
  mysqli_stmt_execute($stmt2);
  $result2 = mysqli_stmt_get_result($stmt2);
  return $result2;
}

function getAllPatients ($conn) {
  
  $sql = "SELECT * FROM Patient WHERE deleted_flag = false";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: medrecord.php?error=getdocidfailed");
    exit();
  }
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

/*****************REFERRALS*****************/
function getAllSpecialists($conn) {
  $primDepNum = 1;
  $sql = "SELECT * FROM Doctor WHERE dep_num <> ? AND deleted_flag = false";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: referrals.php?error=getdocidfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "i", $primDepNum);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function createReferral($conn, $docID, $patID, $specID) {
  $sql = "INSERT INTO Referral (primary_ID, pat_ID, specialist_ID) VALUES (?, ?, ?);";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../info.php?error=insdeminfofailed");
    exit();
  } 
  mysqli_stmt_bind_param($stmt, "iii", $docID, $patID, $specID);
  mysqli_stmt_execute($stmt);
  header("location: ../referrals.php");
  exit();
}

function getActiveDocReferrals($conn, $docID) {
  $sql = "SELECT * FROM Referral WHERE primary_ID = ? AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../referrals.php?error=getdocreffailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "i", $docID);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function getInactiveDocReferrals($conn, $docID) {
  $sql = "SELECT * FROM Referral WHERE primary_ID = ? AND deleted_flag = true;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../referrals.php?error=getdocreffailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "i", $docID);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function getActiveReferrals($conn) {
  $sql = "SELECT * FROM Referral WHERE deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../referrals.php?error=getallreffailed");
    exit();
  }
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function getInactiveReferrals($conn) {
  $sql = "SELECT * FROM Referral WHERE deleted_flag = true;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../referrals.php?error=getallreffailed");
    exit();
  }
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function getDocNameFromDocID($conn, $docID) {
  $sql = "SELECT f_name, l_name, dep_num FROM Doctor WHERE doc_ID = ? AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../referrals.php?error=getallreffailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "i", $docID);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function getPatientNameFromPatientID($conn, $patID) {
  $sql = "SELECT f_name, l_name FROM Patient WHERE patient_ID = ? AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: ../referrals.php?error=getallreffailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "i", $patID);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

/*****************EMERGENCY CONTACT*****************/
function createEmergencyContact($conn, $patID, $fname, $mname, $lname, $relationship, $phonenum, $sex) {
  $sql = "INSERT INTO Emergency_Contact (patient_ID, f_name, m_name, l_name, relationship, phone_num, sex) VALUES (?, ?, ?, ?, ?, ?, ?);";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: emergencycontact.php?error=insemconfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "issssss", $patID, $fname, $mname, $lname, $relationship, $phonenum, $sex);
  mysqli_stmt_execute($stmt);
  header("location: ../index.php");
  exit();
}

/*****************INFO*****************/
function getUsers($conn) {
  
  $sql = "SELECT * FROM User_Account WHERE deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: emergencycontact.php?error=getusersfailed");
    exit();
  }
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

//////////////////           MODIFY FOR PATIENTS TO GET THEIR DEMOGRAPHIC INFO
function getUserInfo($conn, $userID, $userRole) {
  
  $userID = intval($userID);
  if ($userRole === 'patient') {
    $sql = "SELECT * FROM Patient WHERE pat_user = ? AND deleted_flag = false;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("location: emergencycontact.php?error=getuserinfofailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result;
  }
  else if ($userRole === 'doctor') {
    $sql = "SELECT * FROM doctor WHERE doc_user = ? AND deleted_flag = false;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("location: emergencycontact.php?error=getuserinfofailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result;
  }
  else if ($userRole === 'nurse') {
    $sql = "SELECT * FROM Nurse WHERE nurse_user = ? AND deleted_flag = false;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("location: emergencycontact.php?error=getuserinfofailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result;
  }
  else if ($userRole === 'receptionist') {
    $sql = "SELECT * FROM Receptionist WHERE rec_user = ? AND deleted_flag = false;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("location: emergencycontact.php?error=getuserinfofailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result;
  }
  else if ($userRole === 'admin') {
    $sql = "SELECT * FROM User_Account WHERE user_ID = ? AND deleted_flag = false;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("location: emergencycontact.php?error=getuserinfofailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result;
  }
}

function getClinicAddressOfPrimaryDoctor($conn, $primDoc) {
  $sql = "SELECT * FROM Doctor_Works_In_Office WHERE doctor_ID = ? AND deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: updateinfo.php?error=getclinicaddfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt, "i", $primDoc);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);
  $sql2 = "SELECT * FROM Office WHERE office_ID = ? AND deleted_flag = false;";
  $stmt2 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt2, $sql2)) {
    header("location: updateinfo.php?error=getclinicaddfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt2, "i", $row["office_ID"]);
  mysqli_stmt_execute($stmt2);
  $result2 = mysqli_stmt_get_result($stmt2);
  $row2 = mysqli_fetch_assoc($result2);
  $sql3 = "SELECT * FROM Address WHERE address_ID = ? AND deleted_flag = false;";
  $stmt3 = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt3, $sql3)) {
    header("location: updateinfo.php?error=getclinicaddfailed");
    exit();
  }
  mysqli_stmt_bind_param($stmt3, "i", $row2["address_ID"]);
  mysqli_stmt_execute($stmt3);
  $result3 = mysqli_stmt_get_result($stmt3);
  return $result3;
}

/*****************DELETIONS*****************/

function getMedicine($conn) {
  $sql = "SELECT * FROM Medicine WHERE deleted_flag = false;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: emergencycontact.php?error=getusersfailed");
    exit();
  }
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return $result;
}

function getFeature($conn, $featureID, $featureRole) {
  if ($featureRole === 'department') {
    $sql = "SELECT * FROM Department WHERE department_number = ? AND deleted_flag = false;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("location: emergencycontact.php?error=getfeatureinfofailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $featureID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result;
  }
  else if ($featureRole === 'clinic') {
    $sql = "SELECT * FROM Address WHERE address_ID = ? AND deleted_flag = false;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("location: emergencycontact.php?error=getfeatureinfofailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $featureID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result;
  }
  else if ($featureRole === 'office') {
    $sql = "SELECT * FROM Office WHERE office_ID = ? AND deleted_flag = false;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("location: emergencycontact.php?error=getfeatureinfofailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $featureID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result;
  }
  else if ($featureRole === 'medicine') {
    $sql = "SELECT * FROM Medicine WHERE med_ID = ? AND deleted_flag = false;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("location: emergencycontact.php?error=getfeatureinfofailed");
      exit();
    }
    mysqli_stmt_bind_param($stmt, "i", $featureID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result;
  }
}

/*****************DEBUGGING HELPER*****************/
function debug_to_console($data) {
      $output = $data;
      if (is_array($output))
          $output = implode(',', $output);
  
      echo "<script>console.log('Debug Objects: " . $output . "');</script>";
}
  