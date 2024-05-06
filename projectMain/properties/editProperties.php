<?php
require_once('../../lib/nav.php');

// Check if the data needed is available
if (!isset($_POST['edit']) || $_POST['edit'] !== 'edit') {
    echo "Invalid access.";
    exit;
}

// Decrypting data
$address = $_POST['address'];

// Fetch current data from the database
$stmt = mysqli_prepare($connection, "SELECT * FROM PROPERTY WHERE Full_address=?");
mysqli_stmt_bind_param($stmt, "s", $address);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $currentAddress, $currentTax, $currentFBaths,$currentHBaths, $currentBedrooms);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Listing</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <div class="container">
        <div class="row" style="justify-content: center;">
            <div class="col-md-6">
                <h2>Edit Listing</h1>

                
                <!-- Form to change the asking price -->
                <form action="updateProperties.php" method="post">
                    <div class="form-group">
                        <label for="tax">Tax</label>
                        <input type="text" name="tax" value="<?php echo number_format($currentTax, 2, '.', ','); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="fBaths">Full Baths</label>
                        <input type="text" name="fBaths" value="<?php echo $currentFBaths; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="hBaths">Half Baths</label>
                        <input type="text" name="hBaths" value="<?php echo $currentHBaths; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="bedrooms">Bedrooms</label>
                        <input type="text" name="bedrooms" value="<?php echo $currentBedrooms; ?>" required>
                    </div>
                    <input type="hidden" name="oldAddress" value="<?php echo htmlspecialchars($address); ?>">
                    <button type="submit" class="btn btn-default">Update</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>