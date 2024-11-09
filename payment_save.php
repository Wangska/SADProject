<?php 
session_start();
include('includes/dbcon.php');

// Step 1: Add the autoload at the top if you're using Composer
require 'vendor/autoload.php'; // Include this line if PHPMailer is installed using Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Fetch data from the database as usual
$id = $_SESSION['id'];
$mode = $_POST['mode'];

mysqli_query($con,"UPDATE reservation SET modeofpayment='$mode',r_status='pending' where rid='$id'") or die(mysqli_error($con)); 

$query = mysqli_query($con, "SELECT * FROM reservation natural join combo WHERE rid='$id'");
$row = mysqli_fetch_array($query);
$rcode = $row['r_code'];
$first = $row['r_first'];
$last = $row['r_last'];
$contact = $row['r_contact'];
$address = $row['r_address'];
$email = $row['r_email'];
$date = $row['r_date'];
$venue = $row['r_venue'];
$balance = $row['balance'];
$payable = $row['payable'];
$ocassion = $row['r_ocassion'];
$status = $row['r_status'];
$motif = $row['r_motif'];
$time = $row['r_time'];
$type = $row['r_type'];
$cid = $row['combo_id'];
$combo = $row['combo_name'];

// Step 2: Replace mail() with PHPMailer
$mail = new PHPMailer(true);  // Create a new PHPMailer instance

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
    $mail->setFrom('noreply@example.com', 'Cocina\'s Calza Catering Services');  // Set your email here
    $mail->addAddress($email, "$first $last");  // Add the recipient email and name


    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Reservation Details';
    $mail->Body    = "Dear $first $last. Below are your reservation details to Cocina\'s Calza Catering<br>
    	Reservation Code: $rcode<br>
    	Event Date: $date<br>
    	Event Time: $time<br>
    	Venue: $venue<br>
    	Motif: $motif<br>
    	Ocassion: $ocassion<br>
    	Total Payable: $payable<br>
    	Package: $combo";

    // Step 3: Send the email
    $mail->send();
    echo 'Message has been sent';
    
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

// Continue with the rest of your script
echo "<script>alert('Check Your Email Inbox for the details');</script>";
echo "<script>document.location='summary.php'</script>";   
?>
