<?php

if (isset($_POST["submit"])) {
  $email = $_POST["email"];
  $phonenum = $_POST["phonenum"];
  $username = $_POST["username"];
  $password = $_POST["password"];
  $passwordRepeat = $_POST["passwordrepeat"];

  require_once "dbh.inc.php";
  require_once "functions.inc.php";

  if(emptyInputSignup($email, $phonenum, $username, $password, $passwordRepeat) !== false) {
    header("location: ../signup.php?error=emptyinput");
    exit();
  }
  if(invalidEmail($email) !== false) {
    header("location: ../signup.php?error=invalidemail");
    exit();
  }
  if(invalidPhoneNum($phonenum) !== false) {
    header("location: ../signup.php?error=invalidphone");
    exit();
  }
  if(invalidUsername($username) !== false) {
    header("location: ../signup.php?error=invaliduser");
    exit();
  }
  if(passwordMatch($password, $passwordRepeat) !== false) {
    header("location: ../signup.php?error=pwdnomatch");
    exit();
  }
  if(usernameOrEmailExists($conn, $username, $email) !== false) {
    header("location: ../signup.php?error=userexists");
    exit();
  }
  createUser($conn, $email, $phonenum, $username, $password);
}
else {
  header("location: ../signup.php");
  exit();
}
