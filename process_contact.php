<?php
require_once 'config/config.php';

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJSONResponse(['success' => false, 'message' => 'Invalid request method'], 405);
}

// Verify CSRF token
if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    sendJSONResponse(['success' => false, 'message' => 'Invalid security token'], 403);
}

// Validate required fields
$required_fields = ['firstName', 'lastName', 'email', 'subject', 'message'];
$errors = [];

foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $errors[] = ucfirst($field) . ' is required';
    }
}

// Validate email
if (!empty($_POST['email']) && !validateEmail($_POST['email'])) {
    $errors[] = 'Please provide a valid email address';
}

// If there are validation errors
if (!empty($errors)) {
    sendJSONResponse(['success' => false, 'message' => implode(', ', $errors)], 400);
}

// Sanitize input data
$firstName = sanitizeInput($_POST['firstName']);
$lastName = sanitizeInput($_POST['lastName']);
$email = sanitizeInput($_POST['email']);
$subject = sanitizeInput($_POST['subject']);
$message = sanitizeInput($_POST['message']);
$newsletter = isset($_POST['newsletter']) ? 1 : 0;

try {
    // Insert into database
    $sql = "INSERT INTO contact_messages (first_name, last_name, email, subject, message, newsletter_subscription) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $messageId = insert($sql, [$firstName, $lastName, $email, $subject, $message, $newsletter]);
    
    if ($messageId) {
        // Log the contact message
        logMessage("New contact message received from $email: $subject", 'INFO');
        
        // Send email notification (optional)
        sendContactNotification($firstName, $lastName, $email, $subject, $message);
        
        sendJSONResponse([
            'success' => true, 
            'message' => 'Thank you for your message! We\'ll get back to you soon.'
        ]);
    } else {
        sendJSONResponse(['success' => false, 'message' => 'Failed to save message'], 500);
    }
    
} catch (Exception $e) {
    logMessage("Error processing contact form: " . $e->getMessage(), 'ERROR');
    sendJSONResponse(['success' => false, 'message' => 'An error occurred. Please try again.'], 500);
}

/**
 * Send email notification for new contact message
 */
function sendContactNotification($firstName, $lastName, $email, $subject, $message) {
    $to = 'info@eventplanner.com'; // Replace with your email
    $subject_line = "New Contact Message: $subject";
    
    $emailBody = "
    New contact message received:
    
    Name: $firstName $lastName
    Email: $email
    Subject: $subject
    
    Message:
    $message
    
    ---
    This message was sent from the Event Planner contact form.
    ";
    
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    // Uncomment the line below to actually send emails
    // mail($to, $subject_line, $emailBody, $headers);
}
