<?php 
session_start();
include('includes/dbcon.php');

$id = $_SESSION['id'];
$venue = mysqli_real_escape_string($con, $_POST['venue']);
$date = $_POST['date'];
$time = $_POST['time'];
$motif = mysqli_real_escape_string($con, $_POST['motif']);
$pax = $_POST['pax'];
$type = mysqli_real_escape_string($con, $_POST['type']);
$ocassion = mysqli_real_escape_string($con, $_POST['ocassion']);
$cid = $_POST['combo_id'];
$date = date("Y-m-d", strtotime($date));

// Check if the date is already reserved
$query = mysqli_query($con, "SELECT * FROM `reservation` WHERE r_date='$date' AND r_status = 'Approved'");
if (mysqli_num_rows($query) > 0) {
    echo "<script>alert('Date is already reserved'); window.history.back();</script>";
} else {
    // Retrieve the combo price
    $comboQuery = mysqli_query($con, "SELECT * FROM combo WHERE combo_id='$cid'");
    $row = mysqli_fetch_array($comboQuery);
    $price = $row['combo_price'];
    $payable = $pax * $price;

    // Update reservation details
    $updateQuery = "UPDATE reservation SET payable='$payable', balance='$payable', r_venue='$venue', r_date='$date', r_time='$time', r_motif='$motif', r_ocassion='$ocassion', r_type='$type', pax='$pax', combo_id='$cid', price='$price' WHERE rid='$id'";

    mysqli_query($con, $updateQuery) or die(mysqli_error($con));

    $_SESSION['id'] = $id;
    echo "<script>document.location='payment.php'</script>";
}
?>
