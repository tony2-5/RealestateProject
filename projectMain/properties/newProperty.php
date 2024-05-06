<?php
require_once('../../lib/nav.php');
// check if success or fail
if(isset($_GET["message"])) {
  if($_GET["message"]=="success") {
      echo "<div id='propertySuccess' class='alert alert-success' role='alert'>
          Property added!
      </div>";
  } elseif($_GET["message"]=="fail") {
      echo "<div id='propertyFail' class='alert alert-danger' role='alert'>
          Adding new property failed.
      </div>";
  }
}
?>
<link rel="stylesheet" href="./style.css">
<div class="container">
    <div class="row" style="justify-content: center;">
        <div class="col-md-6">
            <div class="well-block">
                <div class="well-title">
                    <h2>Add New Property</h2>
                </div>
                <!-- Redirect realtor to schedule time after selecting initial data-->
                <form action="ownerInfo.php" method="post">
                    <!-- Form start -->
                    <div class="form-group">
                      <label for="pType" class="control-label">Property Type</label>
                      <select onchange='checkPropertyType()' class="select form-control" id="pType" name="pType" required>
                        <option selected="true" disabled="disabled">Select Property Type</option>
                        <option id="house" value="house">House</option>
                        <option id="apartment" value="apartment">Apartment</option>
                        <option id="condo" value="condo">Condo</option>
                      </select>
                    </div>
                     <!-- Text input-->
                     <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="address">Full Address</label>
                            <input id="address" name="address" type="text" class="form-control input-md" required>
                        </div>
                    </div>
                    <!-- Numeric input-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="tax">Tax</label>
                            <input id="tax" name="tax" type="number" class="form-control input-md" required>
                        </div>
                    </div>
                    <!-- Numeric inputs-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="fBaths">Full Baths</label>
                            <input id="fBaths" name="fBaths" type="number" class="form-control input-md" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="hBaths">Half Baths</label>
                            <input id="hBaths" name="hBaths" type="number" class="form-control input-md" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="bedrooms">Bedrooms</label>
                            <input id="bedrooms" name="bedrooms" type="number" class="form-control input-md" required>
                        </div>
                    </div>
                     <!-- Extra inputs depending on property type-->
                    <div id="hExtra" name="hExtra" style="display: none; margin-bottom:2vh;">
                      <label class="control-label" for="style">Style</label>
                      <input class="form-control" type="text" id="style" name="style" required disabled>
                    </div>
                    <div id="aExtra" name="aExtra" style="display: none; margin-bottom:2vh;">
                      <label class="control-label" for="aFloors">Floors</label>
                      <input class="form-control" type="number" id="aFloors" name="aFloors" required disabled>
                      <label class="control-label" for="securityDeposit">Security Deposit</label>
                      <input class="form-control" type="number" id="securityDeposit" name="securityDeposit" required disabled>
                    </div>
                    <div id="cExtra" name="cExtra" style="display: none; margin-bottom:2vh;">
                      <label class="control-label" for="units">Units</label>
                      <input class="form-control" type="number" id="units" name="units" required disabled>
                      <label class="control-label" for="cFloors">Floors</label>
                      <input class="form-control" type="number" id="cFloors" name="cFloors" required disabled>
                      <label class="control-label" for="fees">Fees</label>
                      <input class="form-control" type="number" id="fees" name="fees" required disabled>
                    </div>
                    <!-- Button -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <!-- Redirect to page where realtor can pick from available times on that date -->
                            <button id="submitButton" name="submitButton" class="btn btn-default">Add Property</button>
                        </div>
                    </div>
                </form>
                <!-- form end -->
            </div>
        </div>
    </div>
</div>
<script>
  // jquery to check if success or fail message exists and to have it fade out
  if ($("#propertySuccess").length) {
    $('#propertySuccess').delay(5000).fadeOut(400)
  } else if($("#propertyFail").length) {
    $('#propertyFail').delay(5000).fadeOut(400)
  }
  //jquery agent input search
  $("#agent").chosen();
  
  //javascript to open additional form fields depending on type of property
  function checkPropertyType() {
      if (document.getElementById('pType').value == 'house') {
        // disable the other extra form fields
        document.getElementById('cExtra').style.display = 'none';
        document.getElementById('aExtra').style.display = 'none';

        document.getElementById('hExtra').style.display = '';
        document.getElementById('style').disabled = false;
      } else if(document.getElementById('pType').value == 'apartment') {
        // disable the other extra form fields
        document.getElementById('hExtra').style.display = 'none';
        document.getElementById('cExtra').style.display = 'none';

        document.getElementById('aExtra').style.display = '';
        document.getElementById('aFloors').disabled = false;
        document.getElementById('securityDeposit').disabled = false;
      } else if(document.getElementById('pType').value == 'condo') {
        // disable the other extra form fields
        document.getElementById('hExtra').style.display = 'none';
        document.getElementById('aExtra').style.display = 'none';

        document.getElementById('cExtra').style.display = '';
        document.getElementById('units').disabled = false;
        document.getElementById('cFloors').disabled = false;
        document.getElementById('fees').disabled = false;
      }
  }
</script>