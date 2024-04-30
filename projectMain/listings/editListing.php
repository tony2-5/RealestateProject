<?php
require_once('../../lib/nav.php');

// Check if the data needed is available
if (!isset($_POST['edit']) || $_POST['edit'] !== 'edit') {
    echo "Invalid access.";
    exit;
}

// Decrypting data
$decryptedSSN = decrypt($encryptionKey, $_POST['ssn']);
$date = $_POST['date'];
$address = $_POST['address'];
$price = $_POST['price'];

// Fetch current data from the database
$stmt = mysqli_prepare($connection, "SELECT L.asking_price, A.Name, A.Agent_SSN FROM LISTING L JOIN AGENT A ON L.Agent_SSN = A.Agent_SSN WHERE L.Agent_SSN=? AND L.list_date=? AND L.Full_address=?");
mysqli_stmt_bind_param($stmt, "sss", $decryptedSSN, $date, $address);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $currentPrice, $agentName, $agentSSN);
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
                
                <!-- Displaying the selected listing details -->
                <div class="listing-details">
                    <p><strong>List Date:</strong> <?php echo htmlspecialchars($date); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
                    <p><strong>Agent:</strong> <?php echo htmlspecialchars($agentName) . " *****" . substr($agentSSN, -4); ?></p>
                    <p><strong>Current Asking Price:</strong> $<?php echo number_format($currentPrice, 2); ?></p>
                </div>

                
                <!-- Form to change the asking price -->
                <form action="updateListingPrice.php" method="post">
                    <div class="form-group">
                        <label for="price">New Asking Price: $</label>
                        <input type="text" name="price" value="<?php echo htmlspecialchars($currentPrice); ?>" required>
                    </div>
                    <input type="hidden" name="ssn" value="<?php echo $_POST['ssn']; ?>">
                    <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>">
                    <input type="hidden" name="address" value="<?php echo htmlspecialchars($address); ?>">
                    <button type="submit" class="btn btn-default">Update Price</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>