<?php
require_once 'dbh.inc.php';
require_once 'functions.inc.php';
if (isset($_SESSION["userRole"])) {
  if ($_SESSION["userRole"] === 'patient' || $_SESSION["userRole"] === 'admin') {
    deleteEntity($conn, $_POST["role"], $_POST["id"]);
  }
  else {
    header("location: ../index.php");
    exit();
  }
}
else {
  header("location: ../index.php");
  exit();
}