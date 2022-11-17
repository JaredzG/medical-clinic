<?php 
  include_once 'header.php';
?>
<div class='welcome'>
  <h1>Welcome to the Medical Clinic</h1>
  <?php
  if (isset($_SESSION["username"])) {
      echo '<p>Hello there, '.$_SESSION["username"].'</p>';
  }
  ?>
</div>
<?php
  if ($_REQUEST["status"] === 'success') {
    echo '<script>alert("Account Created")</script>';
  }
  include_once 'footer.php';
?>