<?php 
  include_once 'header.php';
?>
<div class='welcome'>
  <h1>Welcome to the Medical Clinic</h1>
  <img src="download.jpeg"></img>
  <?php
  if (isset($_SESSION["username"])) {
      echo '<p>Hello there, '.$_SESSION["username"].'</p>';
  }
  ?>
</div>
<?php
  include_once 'footer.php';
?>