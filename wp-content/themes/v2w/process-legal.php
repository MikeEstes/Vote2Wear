<?php 

// array to hold validation errors 
$errors=array(); 
// array to pass back data 
$data=array(); 

// validate the variables======================================================
// if any of these variables don 't exist, add an error to our $errors array
if (empty($_POST['name2'])) { $errors['name'] = 'Please enter your name. '; }
if (empty($_POST['message2'])) { $errors['message'] = 'Please leave us a message! '; }
if (empty($_POST['email2'])) { $errors['email'] = 'Please enter a valid email address. '; }
//if ($_SERVER["REQUEST_METHOD"] != "POST") { $errors['message'] = 'There was a problem with your submission, please try again. '; }
    
// return a response ===========================================================
// if there are any errors in our errors array, return a success boolean of false
if (!empty($errors)) 
{
    // if there are items in our errors array, return those errors
    $data['success'] = false;
    $data['errors']  = $errors;
} 
else 
{
    // if there are no errors process our form, then return a message    
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        // Get the form fields and remove whitespace.
        $name    = strip_tags(trim($_POST["name2"]));
        $name    = str_replace(array("\r", "\n"), array(" ", " "), $name);        
        $email   = filter_var(trim($_POST["email2"]), FILTER_SANITIZE_EMAIL);
        $message = trim($_POST["message2"]);
	    
        // Check that data was sent to the mailer.
        if (empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) 
        {	
            // Set a 400 (bad request) response code
            $data['success'] = false;
	    $data['message'] = "Oops! Something went wrong and we couldn't send your message. Please verify your information and try again.";
            http_response_code(400);
        }
        
        // Set the recipient email address.
        $recipient = "legal@vote2wear.com";
        
        // Set the email subject.
        $subject = "New Copyright Infringement message from $name!";
        
        // Build the email content.
        $email_content = "Name: $name\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Message:\n$message\n";
        
        // Build the email headers.
        $email_headers = "From: $name <$email>";
        
        // Send the email.
        if (!isset($data['success']) && mail($recipient, $subject, $email_content, $email_headers)) {
        
	    // show a message of success and provide a true success variable	    
	    $data['success'] = true;
	    $data['message'] = 'Thanks! Your message has been submitted successfully!';
	    
            // Set a 200 (okay) response code.
            http_response_code(200);
	    
        } else if (!isset($data['success'])) {
	    $data['success'] = false;
	    $data['message'] = "Oops! Something went wrong and we couldn't send your message.";
	    
            // Set a 500 (internal server error) response code.
            http_response_code(500);
        }
        
    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
    } 
}
// return all our data to an AJAX call
echo json_encode($data);