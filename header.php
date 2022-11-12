<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Medical Clinic</title>
</head>
<body>
  <div class='centered-div'>
    <header>
      <nav>
       <div>
         <ul>
           <li><a href='index.php'>Home</a></li>
           <?php
            if (isset($_SESSION["userID"])) {
              if ($_SESSION["userRole"] === 'admin') {
                echo "<li><a href='dept.php'>Departments</a></li>";
                echo "<li><a href='clinicadd.php'>Clinics</a></li>";
                echo "<li><a href='office.php'>Offices</a></li>";
                echo "<li><a href='medicine.php'>Medicine</a></li>";
                echo "<li><a href='memp.php'>Register Employees</a></li>";
                echo "<li><a href='vemp.php'>View Employees</a></li>";
                echo "<li><a href='transactions.php'>Transactions</a></li>";
                echo "<li><a href='medrecord.php'>Medical Records</a></li>";
                echo "<li><a href='users.php'>Users</a></li>";
                echo "<li><a href='referrals.php'>Referrals</a></li>";
              }
              else if ($_SESSION["userRole"] === 'doctor') {
                echo "<li><a href='settings.php'>Settings</a></li>";
                echo "<li><a href='medrecord.php'>Medical Records</a></li>";
                echo "<li><a href='referrals.php'>Referrals</a></li>";
                echo "<li><a href='patients.php'>Patients</a></li>";
              }
              else if ($_SESSION["userRole"] === 'receptionist') {
                  echo "<li><a href='settings.php'>Settings</a></li>";
                  echo "<li><a href='patients.php'>Patients</a></li>";
                  echo "<li><a href='referrals.php'>Referrals</a></li>";
                  echo "<li><a href='transactions.php'>Transactions</a></li>";
                  echo "<li><a href='appointment.php'>Make an Appointment</a></li>";
              }
              else if ($_SESSION["userRole"] === 'patient') {
                echo "<li><a href='settings.php'>Settings</a></li>";
                echo "<li><a href='transactions.php'>Transactions</a></li>";
                echo "<li><a href='appointment.php'>Make an Appointment</a></li>";
                echo "<li><a href='settings.php'>Settings</a></li>";
                echo "<li><a href='viewmedrecord.php'>Medical Record</a></li>";
              }
              echo "<li><a href='viewappointments.php'>View Appointments</a></li>";
              echo "<li><a href='includes/logout.inc.php'>Log Out</a></li>";
            }
            else {
              echo "<li><a href='signup.php'>Sign Up</a></li>";
              echo "<li><a href='login.php'>Log In</a></li>";
            }
           ?>
          </ul>
       </div>
      </nav>
    </header>