<?php
// using sessions to transfer sensitive data between pages
require_once('../../lib/nav.php');
// check if scheduling succes or failed
if(isset($_GET["message"])) {
if($_GET["message"]=="success") {
    echo "<div id='customerSuccess' class='alert alert-success' role='alert'>
        Customer added!
    </div>";
} elseif($_GET["message"]=="fail") {
    echo "<div id='customerSuccess' class='alert alert-danger' role='alert'>
        Adding new customer failed.
    </div>";
}
}
?>
<?php
#functionality for when submit button pressed
if(isset($_POST['submitButton'])) { 
  //inserting new customer
  $stmt = mysqli_prepare($connection,"INSERT INTO CUSTOMER VALUES(?,?,?,?)");
  mysqli_stmt_bind_param($stmt,"ssss",$_POST["cSSN"], $_POST["cName"], $_POST["age"],$_POST["gender"]);
  mysqli_stmt_execute($stmt);

  //inserting new agent_helps tuple
  $ssn = decrypt($encryptionKey,$_POST["agent"]);
  $stmt = mysqli_prepare($connection,"INSERT INTO AGENT_HELPS VALUES(?,?,?,NULL)");
  mysqli_stmt_bind_param($stmt,"sss",$ssn, $_POST["cSSN"], date("Y-m-d"));
  if(mysqli_stmt_execute($stmt)) {
    header("Location: newCustomer.php?message=success");
  } else {
    header("Location: newCustomer.php?message=fail");
  }
} 
?>
<link rel="stylesheet" href="./style.css">
<div class="container">
    <div class="row" style="justify-content: center;">
        <div class="col-md-6">
            <div class="well-block">
                <div class="well-title">
                    <h2>Add New Customer</h2>
                </div>
                <!-- Redirect realtor to schedule time after selecting initial data-->
                <form action="newCustomer.php" method="post">
                    <!-- Form start -->
                     <!-- Text input-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="cSSN">Customer SSN</label>
                            <input id="cSSN" maxlength="9" minlength="9" name="cSSN" type="text" class="form-control input-md" required>
                        </div>
                    </div>
                     <!-- Text input-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="cName">Full Name</label>
                            <input id="cName" name="cName" type="text" class="form-control input-md" required>
                        </div>
                    </div>
                    <!-- Numeric input-->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="age">Age</label>
                            <input id="age" name="age" type="number" min=18 max=130 class="form-control input-md" required>
                        </div>
                    </div>
                    <!-- Select Basic -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="gender">Gender</label>
                            <select id="gender" name="gender" class="form-control" required>
                                <option value="m">Male</option>
                                <option value="f">Female</option>
                            </select>
                        </div>
                    </div>
                    <!-- Select Basic -->
                    <div class="col-md-6">
                        <div class="form-group" id="agentDiv">
                            <label class="control-label" for="agent">Agent</label>
                            <select id="agent" name="agent" class="form-control">
                                <?php
                                $query = "SELECT Name,Agent_SSN FROM AGENT";
                                $result = mysqli_query($connection,$query);
                                while($row=mysqli_fetch_assoc($result)) {
                                    // encrypting ssn for security
                                    $ssn=encrypt($encryptionKey,$row["Agent_SSN"]);
                                    // echo name and last 4 ssn digits
                                    echo "<option value=$ssn>$row[Name] *****".substr($row["Agent_SSN"],5)."</option>";
                                }
                                ?>
                            </select>
                        </div>
                      </div>
                    <!-- Button -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <!-- Redirect to page where realtor can pick from available times on that date -->
                            <button id="submitButton" name="submitButton" class="btn btn-default">Add customer</button>
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
  if ($("#customerSuccess").length) {
    $('#customerSuccess').delay(5000).fadeOut(400)
  } else if($("#customerFail").length) {
    $('#customerFail').delay(5000).fadeOut(400)
  }
  //jquery company input search
  $("#agent").chosen();
</script>