<?php require_once 'src/TemplateRenderer.class.php';

$success = null;

if(isset($_POST['submit'])) {
  require_once('src/recaptcha/src/autoload.php');
  require_once('php/localCredentials.php');

  // Google recaptcha site key and secret key
  $siteKey = '6LdLHAsTAAAAAMpMPpkmgNh4FDNEl7WJV_3lNuqH';
  $secret = $recaptchaSecret;

  $recaptcha = new \ReCaptcha\ReCaptcha($secret);
  $resp = $recaptcha->verify(filter_var($_POST['g-recaptcha-response'], FILTER_SANITIZE_STRING));

  // set $success to fail by default on form submission
  $success = 'fail';

  if ($resp->isSuccess()) {
    $name = filter_var($_POST['contact_name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['contact_email'], FILTER_SANITIZE_EMAIL);
    $reason = filter_var($_POST['contact_reason'], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['contact_message'], FILTER_SANITIZE_STRING);

    // TODO: set email back to andrew.torget@unt.edu in production
    $to = "jason.ellis@unt.edu";
    $subject = "Digital Austin Collection: " . $reason;
    $body = $message . "\n\n" . "From: " . $name . " - " . $email;

    if (mail($to, $subject, $body)) {
      $success = 'success';
    }

  } else {
    $errors = $resp->getErrorCodes();
  }
}

$template = new TemplateRenderer();
// Include any variables as an array in the second param
print $template->render('contact.html.twig', array(
                        'success' => $success,
                        'body_id' => 'contact'
));