<?php 

include('../includes/dbcon.php');

// Step 1: Add the autoload at the top if you're using Composer
require '../vendor/autoload.php'; // Include this line if PHPMailer is installed using Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$id = $_POST['id'];
$amount = $_POST['amount'];
$status = $_POST['status'];

$date = date("Y-m-d");

if ($amount <> 0) {
    mysqli_query($con,"INSERT INTO payment(amount,rid,payment_date) 
    VALUES('$amount','$id','$date')") or die(mysqli_error());  
}

mysqli_query($con,"UPDATE reservation SET balance=balance-'$amount',r_status='$status' where rid='$id'") or die(mysqli_error($con)); 

$query = mysqli_query($con, "SELECT * FROM reservation natural join combo WHERE rid='$id'");
$row = mysqli_fetch_array($query);

$rcode = $row['r_code'];
$first = $row['r_first'];
$last = $row['r_last'];
$contact = $row['r_contact'];
$address = $row['r_address'];
$date = $row['r_date'];
$venue = $row['r_venue'];
$balance = $row['balance'];
$payable = number_format($row['payable'], 2);
$ocassion = $row['r_ocassion'];
$status = $row['r_status'];
$email = $row['r_email'];
$motif = $row['r_motif'];
$time = $row['r_time'];
$type = $row['r_type'];
$cid = $row['combo_id'];
$combo = $row['combo_name'];
$msg = "Thank you!";

if ($status == 'Approved') {
    $msg = "Please pay your remaining balance $balance, before the event. Thank you!";
}

if ($status == 'Cancelled') {
    $msg = " Sorry!";
}

// Step 2: Replace mail() with PHPMailer
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();                                      // Send using SMTP
    $mail->Host       = 'sandbox.smtp.mailtrap.io';             // Set Mailtrap SMTP server
    $mail->SMTPAuth   = true;                             // Enable SMTP authentication
    $mail->Username   = '18ab2ddab4169b';        // Your Mailtrap username
    $mail->Password   = '2068d476c349ab';        // Your Mailtrap password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Enable TLS encryption
    $mail->Port       = 587;                              // TCP port to connect to (587 for TLS)

    // Recipients
    $mail->setFrom('Wangska123@gmail.com', 'Cocina\'s Calza Catering Services');  // Set your email here
    $mail->addAddress($email, "$first $last");  // Add the recipient email and name


    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Reservation Status';
    $mail->Body    = "Dear $first $last,<br><br>
    Your reservation status is: $status.<br>$msg<br><br>
    Thanks,<br>
    Cocina\'s Calza Catering Services";

    // Step 3: Send the email
    $mail->send();
    echo 'Message has been sent';
    
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

// Step 4: Continue with the rest of the script
echo "<script type='text/javascript'>alert('Successfully added new payment!');</script>";
echo "<script>document.location='pending.php'</script>";   

?>
