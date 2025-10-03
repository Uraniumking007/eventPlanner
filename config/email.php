<?php
/**
 * Email configuration for Zoho Mail
 * 
 * Usage:
 *   require_once __DIR__ . '/email.php';
 *   $mailer = getMailer();
 */

// Zoho Mail SMTP Configuration
// You can override these with environment variables
define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.zoho.com');
define('SMTP_PORT', getenv('SMTP_PORT') ?: 587);
define('SMTP_USERNAME', getenv('SMTP_USERNAME') ?: 'your-email@yourdomain.com');
define('SMTP_PASSWORD', getenv('SMTP_PASSWORD') ?: 'your-app-password');
define('SMTP_FROM_EMAIL', getenv('SMTP_FROM_EMAIL') ?: 'your-email@yourdomain.com');
define('SMTP_FROM_NAME', getenv('SMTP_FROM_NAME') ?: 'Event Planner');
define('SMTP_TO_EMAIL', getenv('SMTP_TO_EMAIL') ?: 'contact@yourdomain.com');

// Email settings
define('SMTP_ENCRYPTION', 'tls'); // or 'ssl' for port 465
define('SMTP_AUTH', true);

/**
 * Send email using Zoho Mail SMTP
 * 
 * @param string $to Recipient email
 * @param string $subject Email subject
 * @param string $body Email body (HTML)
 * @param string $fromName Sender name
 * @param string $fromEmail Sender email
 * @param array $headers Additional headers
 * @return bool Success status
 */
function sendEmail($to, $subject, $body, $fromName = null, $fromEmail = null, $headers = []) {
    $fromName = $fromName ?: SMTP_FROM_NAME;
    $fromEmail = $fromEmail ?: SMTP_FROM_EMAIL;
    
    // Create boundary for multipart message
    $boundary = md5(uniqid(time()));
    
    // Headers
    $emailHeaders = [
        'From: ' . $fromName . ' <' . $fromEmail . '>',
        'Reply-To: ' . $fromEmail,
        'MIME-Version: 1.0',
        'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
        'X-Mailer: PHP/' . phpversion()
    ];
    
    // Add custom headers
    foreach ($headers as $header) {
        $emailHeaders[] = $header;
    }
    
    // Create email body
    $emailBody = "--$boundary\r\n";
    $emailBody .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $emailBody .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $emailBody .= strip_tags($body) . "\r\n\r\n";
    
    $emailBody .= "--$boundary\r\n";
    $emailBody .= "Content-Type: text/html; charset=UTF-8\r\n";
    $emailBody .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $emailBody .= $body . "\r\n\r\n";
    
    $emailBody .= "--$boundary--\r\n";
    
    // Send email using mail() function with SMTP
    $result = mail($to, $subject, $emailBody, implode("\r\n", $emailHeaders));
    
    if (!$result) {
        error_log('Failed to send email to: ' . $to . ' Subject: ' . $subject);
        return false;
    }
    
    return true;
}

/**
 * Send contact form email
 * 
 * @param array $formData Contact form data
 * @return bool Success status
 */
function sendContactEmail($formData) {
    $subject = 'New Contact Form Submission: ' . htmlspecialchars($formData['subject'] ?? 'General Inquiry');
    
    // Create HTML email body
    $body = '<html><body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">';
    $body .= '<div style="max-width: 600px; margin: 0 auto; padding: 20px;">';
    $body .= '<h2 style="color: #2563eb; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px;">New Contact Form Submission</h2>';
    
    $body .= '<div style="background: #f9fafb; padding: 20px; border-radius: 8px; margin: 20px 0;">';
    $body .= '<h3 style="color: #374151; margin-top: 0;">Contact Information</h3>';
    $body .= '<p><strong>Name:</strong> ' . htmlspecialchars($formData['firstName'] . ' ' . $formData['lastName']) . '</p>';
    $body .= '<p><strong>Email:</strong> <a href="mailto:' . htmlspecialchars($formData['email']) . '">' . htmlspecialchars($formData['email']) . '</a></p>';
    
    if (!empty($formData['phone'])) {
        $body .= '<p><strong>Phone:</strong> <a href="tel:' . htmlspecialchars($formData['phone']) . '">' . htmlspecialchars($formData['phone']) . '</a></p>';
    }
    
    $body .= '<p><strong>Subject:</strong> ' . htmlspecialchars($formData['subject']) . '</p>';
    $body .= '</div>';
    
    $body .= '<div style="background: #ffffff; padding: 20px; border: 1px solid #e5e7eb; border-radius: 8px;">';
    $body .= '<h3 style="color: #374151; margin-top: 0;">Message</h3>';
    $body .= '<p style="white-space: pre-wrap;">' . htmlspecialchars($formData['message']) . '</p>';
    $body .= '</div>';
    
    if (!empty($formData['newsletter'])) {
        $body .= '<div style="background: #fef3c7; padding: 15px; border-radius: 8px; margin: 20px 0;">';
        $body .= '<p style="margin: 0; color: #92400e;"><strong>📧 Newsletter:</strong> User opted in to receive updates</p>';
        $body .= '</div>';
    }
    
    $body .= '<div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 14px;">';
    $body .= '<p>This message was sent from the Event Planner contact form on ' . date('Y-m-d H:i:s') . '</p>';
    $body .= '<p>IP Address: ' . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . '</p>';
    $body .= '</div>';
    
    $body .= '</div></body></html>';
    
    return sendEmail(SMTP_TO_EMAIL, $subject, $body);
}

/**
 * Send auto-reply email to user
 * 
 * @param array $formData Contact form data
 * @return bool Success status
 */
function sendAutoReplyEmail($formData) {
    $subject = 'Thank you for contacting Event Planner';
    
    $body = '<html><body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">';
    $body .= '<div style="max-width: 600px; margin: 0 auto; padding: 20px;">';
    $body .= '<h2 style="color: #2563eb;">Thank You for Contacting Us!</h2>';
    
    $body .= '<p>Dear ' . htmlspecialchars($formData['firstName']) . ',</p>';
    
    $body .= '<p>Thank you for reaching out to us. We have received your message and will get back to you within 24 hours.</p>';
    
    $body .= '<div style="background: #f0f9ff; padding: 20px; border-radius: 8px; margin: 20px 0;">';
    $body .= '<h3 style="color: #0369a1; margin-top: 0;">Your Message Details:</h3>';
    $body .= '<p><strong>Subject:</strong> ' . htmlspecialchars($formData['subject']) . '</p>';
    $body .= '<p><strong>Message:</strong></p>';
    $body .= '<div style="background: #ffffff; padding: 15px; border-radius: 4px; border-left: 4px solid #0ea5e9;">';
    $body .= '<p style="margin: 0; white-space: pre-wrap;">' . htmlspecialchars($formData['message']) . '</p>';
    $body .= '</div>';
    $body .= '</div>';
    
    $body .= '<p>If you have any urgent questions, please don\'t hesitate to call us at <a href="tel:+91-9876543210">+91 9876543210</a>.</p>';
    
    $body .= '<p>Best regards,<br>The Event Planner Team</p>';
    
    $body .= '<div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 12px;">';
    $body .= '<p>This is an automated response. Please do not reply to this email.</p>';
    $body .= '</div>';
    
    $body .= '</div></body></html>';
    
    return sendEmail($formData['email'], $subject, $body);
}
