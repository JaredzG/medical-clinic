<?php
  include_once 'header.php';
  require_once 'includes/dbh.inc.php';
  require_once 'includes/functions.inc.php';
  if ($_SESSION["userRole"] === 'patient') {
    $patID = getPatientID($conn, $_SESSION["username"]);
    $balance = getBalance($conn, $patID["patient_ID"]);
    $result = viewAllPatientUnpaidAppointments($conn, $patID["patient_ID"]);
?>
    <div>
      <h2>Balance:<?php echo ' $'.sprintf('%.2f', $balance)?></h2>
      <?php
      if ($result !== false) {
      ?>
        <div>
          <h2>Unpaid Appointments</h2>
          <table class="table-template">
          <thead>
            <tr>
              <th>Date and Time</th>
              <th>Reason</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
              while ($row = mysqli_fetch_assoc($result)) {
                $dateTime = $row["date_time"];
                $reason = $row["reason"];
                $appID = $row["app_ID"];
            ?>
                <tr>
                  <td><?php echo $dateTime?></td>
                  <td><?php echo $reason?></td>
                  <td><a href='includes/makepayment.inc.php?id=<?php echo $patID["patient_ID"]?>&appid=<?php echo $appID?>'>Make Payment</a></td>
                </tr>
            <?php
              }
            ?>
          </tbody>
          </table>
        </div>
      <?php
      }
      else {
        echo '<h2>No payments to be made.</h2>';
      }
      ?>
    </div>
    <div class='view-payment-form'>
      <h2>View Previous Transactions</h2>
      <form action='/medical-clinic/transactions.php' method='post'>
        <div class='form-element'>
          <label for='mindate'>Select a Minimum Date</label>
          <input type='datetime-local' name='mindate' id='mindate' value='2022-12-01T00:00'  min='2022-1-01T00:00' max='2023-01-01T00:00' required/>
        </div>
        <div class='form-element'>
          <label for='maxdate'>Select a Maximum Date</label>
          <input type='datetime-local' name='maxdate' id='maxdate' value='2022-12-01T00:00' min='2022-1-01T00:00' max='2023-01-01T00:00' required/>
        </div>
        <div class='submit-btn'>
          <button type='submit' name='submit'>Submit</button>
        </div>
      </form>
    </div>
<?php
    if (isset($_POST["submit"])) {
      $mindate = $_POST["mindate"];
      $maxdate = $_POST["maxdate"];
      $result = viewPatientTransactions($conn, $patID["patient_ID"], $mindate, $maxdate);
?>
<table class="table-template">
    <thead>
      <tr>
        <th>Transaction ID</th>
        <th>Date</th>
        <th>Amount</th>
      </tr>
    </thead>
    <tbody>
      <?php
        while ($row = mysqli_fetch_assoc($result)) {
          $transID = $row["transaction_ID"];
          $paydate = $row["transaction_date"];
          $amount = $row["amount"];
      ?>
          <tr>
            <td><?php echo $transID?></td>
            <td><?php echo $paydate?></td>
            <td><?php if ($amount >= 0) echo '$'.$amount; else echo '-$'.sprintf('%.2f',abs($amount));?></td>
          </tr>
      <?php
        }
      ?>
    </tbody>
    </table>
<?php
    }
  }
  else if ($_SESSION["userRole"] === 'receptionist' || $_SESSION["userRole"] === 'admin') {
?>
    <div class='view-payment-form'>
      <?php
        if ($_SESSION["userRole"] === 'admin') {
          echo '<h2>View Previous Transactions</h2>';
        }
        else {
          echo '<h2>View Outstanding Dues</h2>';
        }
      ?>
      <form action='/medical-clinic/transactions.php' method='post'>
        <div class='form-element'>
          <label for='fname'>Patient First Name</label>
          <input type='text' name='fname' id='fname' placeholder='First Name' required/>
        </div>
        <div class='form-element'>
          <label for='lname'>Patient Last Name</label>
          <input type='text' name='lname' id='lname' placeholder='Last Name' required/>
        </div>
          <label for='bdate'>Patient Birthdate</label>
          <input type='date' name='bdate' id='bdate' value='2022-12-01'  min='2022-1-01' max='2023-01-01' required/>
        </div>
          <label for='mindate'>Select a Minimum Date</label>
          <input type='datetime-local' name='mindate' id='mindate' value='2022-12-01T00:00'  min='2022-1-01T00:00' max='2023-01-01T00:00' required/>
        </div>
        <div class='form-element'>
          <label for='maxdate'>Select a Maximum Date</label>
          <input type='datetime-local' name='maxdate' id='maxdate' value='2022-12-01T00:00' min='2022-1-01T00:00' max='2023-01-01T00:00' required/>
        </div>
        <div class='submit-btn'>
          <button type='submit' name='submit'>Submit</button>
        </div>
      </form>
    </div>
<?php
   if (isset($_POST["submit"])) {
    $mindate = $_POST["mindate"];
    $maxdate = $_POST["maxdate"];
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $bdate = $_POST["bdate"];
    if ($_SESSION["userRole"] === 'admin') {
      $result = viewTransactions($conn, $mindate, $maxdate);
    }
  else if ($_SESSION["userRole"] === 'receptionist') {
      $result = viewPatientDues($conn, $mindate, $maxdate, $fname, $lname, $bdate);
    }
    $revenue = 0.00;
?>
    <table class="table-template">
      <thead>
        <tr>
          <?php
            if ($_SESSION["userRole"] === 'admin') {
              echo '<th>Transaction ID</th>';
              echo '<th>Patient ID</th>';
            }
          ?>
          <th>Date</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
        <?php
          while ($row = mysqli_fetch_assoc($result)) {
            $transID = $row["transaction_ID"];
            $patientID = $row["patient_ID"];
            $paydate = $row["transaction_date"];
            $amount = $row["amount"];
            if ($amount < 0) {
              $revenue = $revenue - $amount;
            }
            else {
              $dues = $dues + $amount;
            }
            ?>
            <tr>
              <?php
              if ($_SESSION["userRole"] === 'admin') {
                echo '<td>'.$transID.'</td>';
                echo '<td>'.$patientID.'</td>';
              }
              ?>
              <td><?php echo $paydate?></td>
              <td><?php if ($amount >= 0) echo '$'.$amount; else echo '-$'.sprintf('%.2f',abs($amount));?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
      </table>
<?php
    }
    echo '<p>Dues: $'.sprintf('%.2f', $dues).'</p>';
    if ($_SESSION["userRole"] === 'admin') {
      echo '<p>Outstanding Costs: $'.sprintf('%.2f', $dues - $revenue).'</p>';
      echo '<p>Revenue: $'.sprintf('%.2f', $revenue).'</p>';
    }
  }
?>
<?php
  include_once 'footer.php';
?>