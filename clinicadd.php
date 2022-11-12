<?php
  include_once 'header.php';
?>
<div class='clinic-form'>
  <h2>Add a Clinic</h2>
  <form action='/medical-clinic/includes/clinicadd.inc.php' method='post'>
      <div class='form-element'>
        <label for='street-add'>Enter the Street Address</label>
        <input type='text' name='street-add' id='street-add' placeholder='100 Main St' required/>
      </div>
      <div class='form-element'>
        <label for='city'>Enter the City</label>
        <input type='text' name='city' id='city' placeholder='Houston'/>
      </div>
      <div class='form-element'>
        <label for='state'>Select a State</label>
        <select name='state' id='state' required>
          <option value="AL">Alabama</option>
          <option value="AK">Alaska</option>
          <option value="AZ">Arizona</option>
          <option value="AR">Arkansas</option>
          <option value="CA">California</option>
          <option value="CO">Colorado</option>
          <option value="CT">Connecticut</option>
          <option value="DE">Delaware</option>
          <option value="DC">District Of Columbia</option>
          <option value="FL">Florida</option>
          <option value="GA">Georgia</option>
          <option value="HI">Hawaii</option>
          <option value="ID">Idaho</option>
          <option value="IL">Illinois</option>
          <option value="IN">Indiana</option>
          <option value="IA">Iowa</option>
          <option value="KS">Kansas</option>
          <option value="KY">Kentucky</option>
          <option value="LA">Louisiana</option>
          <option value="ME">Maine</option>
          <option value="MD">Maryland</option>
          <option value="MA">Massachusetts</option>
          <option value="MI">Michigan</option>
          <option value="MN">Minnesota</option>
          <option value="MS">Mississippi</option>
          <option value="MO">Missouri</option>
          <option value="MT">Montana</option>
          <option value="NE">Nebraska</option>
          <option value="NV">Nevada</option>
          <option value="NH">New Hampshire</option>
          <option value="NJ">New Jersey</option>
          <option value="NM">New Mexico</option>
          <option value="NY">New York</option>
          <option value="NC">North Carolina</option>
          <option value="ND">North Dakota</option>
          <option value="OH">Ohio</option>
          <option value="OK">Oklahoma</option>
          <option value="OR">Oregon</option>
          <option value="PA">Pennsylvania</option>
          <option value="RI">Rhode Island</option>
          <option value="SC">South Carolina</option>
          <option value="SD">South Dakota</option>
          <option value="TN">Tennessee</option>
          <option value="TX">Texas</option>
          <option value="UT">Utah</option>
          <option value="VT">Vermont</option>
          <option value="VA">Virginia</option>
          <option value="WA">Washington</option>
          <option value="WV">West Virginia</option>
          <option value="WI">Wisconsin</option>
          <option value="WY">Wyoming</option>
        </select>
      </div>
      <div class='form-element'>
        <label for='zip'>Enter the Zip Code</label>
        <input type='text' name='zip' id='zip' placeholder='12345' required/>
      </div>
      <div class='submit-btn'>
        <button type='submit' name='submit'>Submit</button>
      </div>
    </form>
    <?php
      if (isset($_GET["error"])) {
        if ($_GET["error"] === "emptyinput") {
          echo "<p>Fill in all fields.</p>";
        }
        if ($_GET["error"] === "invalidzip") {
          echo "<p>Enter a proper zip code.</p>";
        }
      }
    ?>
</div>
<?php
  require_once 'includes/dbh.inc.php';
  require_once 'includes/functions.inc.php';
  $result = viewClinicLocations($conn);
?>
<div>
  <h2>Clinic Locations</h2>
  <table>
    <tr>
      <th>Address ID</th>
      <th>Street Address</th>
      <th>City</th>
      <th>State</th>
      <th>Zip Code</th>
      <th>Action</th>
    </tr>
    <?php
      while ($row = mysqli_fetch_assoc($result)) {
        $addID = $row["address_ID"];
        $streetAdd = $row["street_address"];
        $city = $row["city"];
        $state = $row["state"];
        $zip = $row["zip_code"];
    ?>
        <tr>
          <td><?php echo $addID?></td>
          <td><?php echo $streetAdd?></td>
          <td><?php echo $city?></td>
          <td><?php echo $state?></td>
          <td><?php echo $zip?></td>
          <td>
          <form action='/medical-clinic/delete.php' method='post'>
          <div class='form-element'>
            <input style='display: none;' name='userID' value='none'/>
          </div>
          <div class='form-element'>
            <input style='display: none;' name='userRole' value='<none'/>
          </div>
          <div class='form-element'>
            <input style='display: none;' name='otherID' value='<?php echo $addID?>'/>
          </div>
          <div class='form-element'>
            <input style='display: none;' name='otherRole' value='clinic'/>
          </div>
          <div class='submit-btn'>
            <button type='submit' name='submit'>Delete</button>
          </div>
        </form>
          </td>
        </tr>
    <?php
      }
    ?>
  </table>
</div>
<?php
  include_once 'footer.php';
?>