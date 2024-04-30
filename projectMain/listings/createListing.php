<?php
// using sessions to transfer sensitive data between pages
require_once('../../lib/nav.php');
// check if listing creation succeeded or failed
if(isset($_GET["message"])) {
    if($_GET["message"]=="success") {
        echo "<div class='alert alert-success' role='alert'>
            Listing Created!
        </div>";
    } elseif($_GET["message"]=="fail") {
        echo "<div class='alert alert-danger' role='alert'>
            Listing creation failed.
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
                    <h2>Post a Property Listing</h2>
                </div>
                <!-- Redirect realtor to schedule time after selecting initial data-->
                <form action="generateListing.php" method="post">
                    <!-- Form start -->
                        <!-- Agent input-->
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
                        <!-- Select Property Address-->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="address">Property Address</label>
                                <select id="address" name="address" class="form-control">
                                    <?php
                                        $query = "SELECT Full_address FROM PROPERTY WHERE Full_address NOT IN (SELECT Full_address FROM LISTING)";
                                        $result = mysqli_query($connection,$query);
                                        while($row=mysqli_fetch_assoc($result)) {
                                            // echo name and last 4 ssn digits
                                            echo "<option>$row[Full_address]</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- Asking Price Text Input -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="price">Asking Price:</label>
                                <input type="number" name="price" required><br>

                            </div>
                        </div>
                        <!-- Button -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <!-- Redirect to page where realtor can pick from available times on that date -->
                                <button id="singlebutton" name="submitButton" class="btn btn-default">Create Listing</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- form end -->
            </div>
        </div>
    </div>
</div>