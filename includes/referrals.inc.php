<?php
session_start();
require_once 'dbh.inc.php';
require_once 'functions.inc.php';
if (isset($_POST["submit"])) {
  $docID = $_POST["doctor"];
  $patID = $_POST["patient"];
  $specID = $_POST["specialist"];
  createReferral($conn, $docID, $patID, $specID);
}