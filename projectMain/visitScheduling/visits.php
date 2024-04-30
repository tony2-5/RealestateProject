<?php
// using sessions to transfer sensitive data between pages
require_once('../../lib/nav.php');
// check if scheduling succes or failed
if(isset($_GET["message"])) {
if($_GET["message"]=="success") {
    echo "<div class='alert alert-success' role='alert'>
        Scheduling Success!
    </div>";
} elseif($_GET["message"]=="fail") {
    echo "<div class='alert alert-danger' role='alert'>
        Scheduling failed.
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
                    <h2>Book an Appointment</h2>
                </div>
                <!-- Redirect realtor to schedule time after selecting initial data-->
                <form action="timeSchedule.php" method="post">
                    <!-- Form start -->
                        <!-- Text input-->
                        <div class="col-md-6">
                            <div class="form-group" id="customerDiv">
                                <label class="control-label" for="customer">Customer</label>
                                <select id="customer" name="customer" class="form-control">
                                    <?php
                                    $query = "SELECT Name,Customer_SSN FROM CUSTOMER";
                                    $result = mysqli_query($connection,$query);
                                    while($row=mysqli_fetch_assoc($result)) {
                                        // encrypting ssn for security
                                        $ssn=encrypt($encryptionKey,$row["Customer_SSN"]);
                                        // echo name and last 4 ssn digits
                                        echo "<option value=$ssn>$row[Name] *****".substr($row["Customer_SSN"],5)."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- Text input-->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="date">Date</label>
                                <input id="date" name="date" type="date" placeholder="Preferred Date" class="form-control input-md" required>
                            </div>
                        </div>
                        <!-- Select Basic -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="address">Property Address</label>
                                <select id="address" name="address" class="form-control">
                                    <?php
                                        $query = "SELECT Full_address FROM PROPERTY";
                                        $result = mysqli_query($connection,$query);
                                        while($row=mysqli_fetch_assoc($result)) {
                                            // echo name and last 4 ssn digits
                                            echo "<option>$row[Full_address]</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- Button -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <!-- Redirect to page where realtor can pick from available times on that date -->
                                <button id="singlebutton" name="singlebutton" class="btn btn-default">Schedule time</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- form end -->
            </div>
        </div>
    </div>
</div>
<script>
    //jquery customer input search
    $("#customer").chosen();
    //jquery property input search
    $("#address").chosen();
</script>

