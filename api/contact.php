<?php
declare(strict_types=1);
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/../config/env.php';

// Load PHPMailer if installed via composer
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$usePhpMailer = class_exists('PHPMailer\PHPMailer\PHPMailer');

function sendContactEmail(array $data): bool {
    global $usePhpMailer;
    
    $firstName = htmlspecialchars($data['firstName'] ?? '');
    $lastName = htmlspecialchars($data['lastName'] ?? '');
    $email = $data['email'] ?? '';
    $phone = htmlspecialchars($data['phone'] ?? '');
    $subject = htmlspecialchars($data['subject'] ?? '');
    $message = htmlspecialchars($data['message'] ?? '');
    $newsletter = isset($data['newsletter']) && $data['newsletter'] ? 'Yes' : 'No';
    
    $fullName = trim("$firstName $lastName");
    
    // Get subject label
    $subjectLabels = [
        'general' => 'General Inquiry',
        'support' => 'Technical Support',
        'billing' => 'Billing Question',
        'feature' => 'Feature Request',
        'partnership' => 'Partnership',
        'other' => 'Other'
    ];
    $subjectLabel = $subjectLabels[$subject] ?? 'General Inquiry';
    
    // Email content
    $emailSubject = "Contact Form: $subjectLabel - from $fullName";
    $emailBody = "
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 100%); color: white; padding: 20px; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #06b6d4; }
        .value { margin-top: 5px; padding: 10px; background: white; border-left: 3px solid #06b6d4; }
        .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2 style='margin: 0;'>New Contact Form Submission</h2>
        </div>
        <div class='content'>
            <div class='field'>
                <div class='label'>Name:</div>
                <div class='value'>$fullName</div>
            </div>
            <div class='field'>
                <div class='label'>Email:</div>
                <div class='value'><a href='mailto:$email'>$email</a></div>
            </div>
            " . ($phone ? "
            <div class='field'>
                <div class='label'>Phone:</div>
                <div class='value'>$phone</div>
            </div>
            " : "") . "
            <div class='field'>
                <div class='label'>Subject:</div>
                <div class='value'>$subjectLabel</div>
            </div>
            <div class='field'>
                <div class='label'>Message:</div>
                <div class='value'>" . nl2br($message) . "</div>
            </div>
            <div class='field'>
                <div class='label'>Newsletter Subscription:</div>
                <div class='value'>$newsletter</div>
            </div>
            <div class='footer'>
                <p>This email was sent from the Event Planner contact form.</p>
                <p>Submitted on: " . date('F j, Y \a\t g:i A') . "</p>
            </div>
        </div>
    </div>
</body>
</html>
    ";
    
    $toEmail = getenv('CONTACT_EMAIL') ?: (getenv('SMTP_TO_EMAIL') ?: 'hello@eventplanner.com');
    
    if ($usePhpMailer) {
        // Use PHPMailer for better Gmail support
        try {
            $mail = new PHPMailer(true);
            
            // Server settings
            $mail->isSMTP();
            $mail->Host = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = getenv('SMTP_USERNAME');
            $mail->Password = getenv('SMTP_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = (int)(getenv('SMTP_PORT') ?: 587);
            
            // Recipients
            $mail->setFrom(getenv('SMTP_FROM_EMAIL'), getenv('SMTP_FROM_NAME') ?: 'Event Planner');
            $mail->addAddress($toEmail);
            $mail->addReplyTo($email, $fullName);
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = $emailSubject;
            $mail->Body = $emailBody;
            $mail->AltBody = strip_tags(str_replace('<br>', "\n", $emailBody));
            
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("PHPMailer Error: {$mail->ErrorInfo}");
            return false;
        }
    } else {
        // Fallback to PHP mail() function
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . (getenv('SMTP_FROM_NAME') ?: 'Event Planner') . " <" . (getenv('SMTP_FROM_EMAIL') ?: 'noreply@eventplanner.com') . ">\r\n";
        $headers .= "Reply-To: $fullName <$email>\r\n";
        
        return mail($toEmail, $emailSubject, $emailBody, $headers);
    }
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'POST') {
    $body = read_json_body();
    
    // Validate required fields
    $firstName = trim((string)($body['firstName'] ?? ''));
    $lastName = trim((string)($body['lastName'] ?? ''));
    $email = trim((string)($body['email'] ?? ''));
    $subject = trim((string)($body['subject'] ?? ''));
    $message = trim((string)($body['message'] ?? ''));
    
    if ($firstName === '' || $lastName === '' || $email === '' || $subject === '' || $message === '') {
        json_response(['error' => 'All required fields must be filled'], 422);
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        json_response(['error' => 'Invalid email format'], 422);
    }
    
    // Store in feedback table for record keeping
    try {
        $fullName = "$firstName $lastName";
        insert('INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)', [
            $fullName,
            $email,
            "[$subject] $message"
        ]);
    } catch (Exception $e) {
        // Continue even if database insert fails
        error_log("Failed to store contact form: " . $e->getMessage());
    }
    
    // Send email
    $emailSent = sendContactEmail($body);
    
    if ($emailSent) {
        json_response(['success' => true, 'message' => 'Your message has been sent successfully!'], 200);
    } else {
        json_response(['success' => false, 'message' => 'Your message was saved but email delivery failed. We will respond as soon as possible.'], 500);
    }
} else {
    json_response(['error' => 'Method not allowed'], 405);
}

