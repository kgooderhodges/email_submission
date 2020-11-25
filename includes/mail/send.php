<?php
// DEBUG ONLY, REMOVE AFTER
ini_set('display_errors', 1);
// TO DO: Takes care of the form submission

// 4. It returns proper info in JSON format [Receipts] [Checked!]
//      a. what is AJAX? - a technology that sends and receives data from a server asynchronously. Submits data without re-loading the entire page. works with js
//      b. What is JSON?
//      c. How to build JSON (in PHP)

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=UTF-8');
$results = [];
$vistor_name = '';
$vistor_email = '';
$vistor_message = '';

// 1. Check the submission -> Validate the data
// $_POST['firstname]

if(isset($_POST['firstname'])) {
    $vistor_name = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
}

if(isset($_POST['lastname'])) {
    $vistor_name .= ' '.filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
}

if(isset($_POST['email'])) {
    $vistor_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
}

if(isset($_POST['message'])) {
    $vistor_message = filter_var(htmlspecialchars($_POST['message']), FILTER_SANITIZE_STRING);    
}

$results['name'] = $vistor_name;
$results['email'] = $vistor_email;
$results['message'] = $vistor_message;

// 2. Prepare the email
$email_subject = 'Inquiry from Portfolio Site';
$email_recipient = 'test@thepan.ca'; // Your email, or AKA, "To" email
$email_message = sprintf('Name: %s, Email: %s, Message: %s', $vistor_name, $vistor_email, $vistor_message);
// Make sure you run the code in PHP 7.4 +
// Otherwise you would need to make $email_headers a string

$email_headers = array(
    // Best practice, but it may need you to have a mail set up in noreply@yourdomain.ca. Do this for client work
    // 'From'=>'noreply@yourdomain.ca',
    // 'Reply-To'=>$vistor_email,

    //You can still use it, if above is too much work
    'From'=>$vistor_email
);

// 3. Send out the email
$email_result = @!mail($email_recipient, $email_subject, $email_message, $email_headers);
if ($email_result) {
    $results['message'] = sprintf('Thank you for contacting us, %s. You will get a reply within 24 hours.', $vistor_name);
} else {
    $result['message'] = sprintf('We are sorry but the email did not go through.');
}

echo json_encode($results);