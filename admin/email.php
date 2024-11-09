<?php

    ini_set( 'display_errors', 1 );
    
    error_reporting( E_ALL );
    
    $from = "wangska1283@gmail.com";
    
    $to = "wangska_12@yahoo.com";
    
    $subject = "Checking PHP mail";
    
    $message = "Sample Email";
    
    $headers = "From:" . $from;
    
    mail($to,$subject,$message, $headers);
    
    echo "<script>
		alert('Check Your Email Inbox for the details');		
	</script>";
	
?>
