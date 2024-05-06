<?php
require_once('../../lib/nav.php');

if (isset($_POST['oldAddress'])) {
    $newTax = $_POST['tax'];
    $newFBaths = $_POST['fBaths'];
    $newHBaths = $_POST['hBaths'];
    $newBedrooms = $_POST['bedrooms'];

    $stmt = mysqli_prepare($connection, "UPDATE PROPERTY SET tax=?,Fullbath=?,Halfbath=?,Bedrooms=? WHERE Full_address = ?");
    mysqli_stmt_bind_param($stmt, "dddds", $newTax, $newFBaths, $newHBaths,$newBedrooms,$_POST["oldAddress"]);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: viewProperties.php?update=success");
        exit;
    } else {
        header("Location: viewProperties.php?update=fail");
        exit;
    }
} else {
    echo "Invalid request.";
}
?>