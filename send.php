<?php

$name = $_POST['contact_name'];
$email = $_POST['contact_email'];
$reason = $_POST['contact_reason'];
$message = $_POST['contact_message'];

 $to = "andrewtorget@gmail.com";
 $subject = "Digital Austin Collection: " . $reason;
 $body = $message . "\n\n" . "From: " . $name . " - " . $email;
 
 if (mail($to, $subject, $body)) {
   echo("<p>Message successfully sent!</p>");
  } else {
   echo("<p>Message delivery failed...</p>");
  }
 ?>