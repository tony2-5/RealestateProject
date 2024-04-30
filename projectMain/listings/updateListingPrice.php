<?php
require_once('../../lib/nav.php');

if (isset($_POST['price'], $_POST['ssn'], $_POST['date'], $_POST['address'])) {
    $newPrice = $_POST['price'];
    $decryptedSSN = decrypt($encryptionKey, $_POST['ssn']);
    $date = $_POST['date'];
    $address = $_POST['address'];

    $stmt = mysqli_prepare($connection, "UPDATE LISTING SET asking_price = ? WHERE Agent_SSN = ? AND list_date = ? AND Full_address = ?");
    mysqli_stmt_bind_param($stmt, "dsss", $newPrice, $decryptedSSN, $date, $address);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: viewListings.php?update=success");
        exit;
    } else {
        header("Location: viewListings.php?update=fail");
        exit;
    }
} else {
    echo "Invalid request.";
}
?>