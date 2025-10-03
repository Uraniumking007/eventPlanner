<?php
/**
 * Email configuration for Zoho Mail
 * 
 * Usage:
 *   require_once __DIR__ . '/email.php';
 *   $mailer = getMailer();
 */

// Load environment variables from .env file
require_once __DIR__ . '/env.php';

// Email sending methods loaded

// Zoho Mail SMTP Configuration
// You can override these with environment variables
define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.zoho.com');
define('SMTP_PORT', getenv('SMTP_PORT') ?: 587);
define('SMTP_USERNAME', getenv('SMTP_USERNAME') ?: 'contact@bhaveshp.dev');
define('SMTP_PASSWORD', getenv('SMTP_PASSWORD') ?: '');
define('SMTP_FROM_EMAIL', getenv('SMTP_FROM_EMAIL') ?: 'contact@bhaveshp.dev');
define('SMTP_FROM_NAME', getenv('SMTP_FROM_NAME') ?: 'Event Planner');
define('SMTP_TO_EMAIL', getenv('SMTP_TO_EMAIL') ?: 'contact@bhaveshp.dev');

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
    
    // Try to send email using mail() function
    $result = mail($to, $subject, $emailBody, implode("\r\n", $emailHeaders));
    
    if (!$result) {
        error_log('Failed to send email to: ' . $to . ' Subject: ' . $subject);
        error_log('SMTP Config - Host: ' . SMTP_HOST . ', Port: ' . SMTP_PORT . ', User: ' . SMTP_USERNAME);
        return false;
    }
    
    error_log('Email sent successfully to: ' . $to . ' Subject: ' . $subject);
    return true;
}

/**
 * Send email using direct SMTP connection (more reliable)
 * 
 * @param string $to Recipient email
 * @param string $subject Email subject
 * @param string $body Email body (HTML)
 * @param string $fromName Sender name
 * @param string $fromEmail Sender email
 * @return bool Success status
 */
function sendEmailSMTP($to, $subject, $body, $fromName = null, $fromEmail = null) {
    $fromName = $fromName ?: SMTP_FROM_NAME;
    $fromEmail = $fromEmail ?: SMTP_FROM_EMAIL;
    
    // Check if password is set
    if (empty(SMTP_PASSWORD) || SMTP_PASSWORD === 'YOUR_APP_PASSWORD_HERE') {
        error_log('SMTP Password not configured. Please set SMTP_PASSWORD in your .env file.');
        return false;
    }
    
    // Create boundary for multipart message
    $boundary = md5(uniqid(time()));
    
    // Headers
    $headers = [
        'From: ' . $fromName . ' <' . $fromEmail . '>',
        'Reply-To: ' . $fromEmail,
        'To: ' . $to,
        'Subject: ' . $subject,
        'MIME-Version: 1.0',
        'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
        'X-Mailer: PHP/' . phpversion()
    ];
    
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
    
    // Try different connection methods
    $socket = null;
    $connectionMethod = '';
    
    // Method 1: Try TLS connection
    $socket = fsockopen('tls://' . SMTP_HOST, SMTP_PORT, $errno, $errstr, 30);
    if ($socket) {
        $connectionMethod = 'TLS';
    } else {
        error_log("TLS connection failed: $errstr ($errno)");
        
        // Method 2: Try SSL connection on port 465
        $socket = fsockopen('ssl://' . SMTP_HOST, 465, $errno, $errstr, 30);
        if ($socket) {
            $connectionMethod = 'SSL';
        } else {
            error_log("SSL connection failed: $errstr ($errno)");
            
            // Method 3: Try plain connection and upgrade to TLS
            $socket = fsockopen(SMTP_HOST, SMTP_PORT, $errno, $errstr, 30);
            if ($socket) {
                $connectionMethod = 'PLAIN';
            } else {
                error_log("Plain connection failed: $errstr ($errno)");
                return false;
            }
        }
    }
    
    if (!$socket) {
        error_log("All SMTP connection methods failed");
        return false;
    }
    
    error_log("SMTP connected using: $connectionMethod");
    
    // Read initial response
    $response = fgets($socket, 515);
    if (substr($response, 0, 3) !== '220') {
        error_log("SMTP Initial response error: $response");
        fclose($socket);
        return false;
    }
    
    // Send EHLO
    fwrite($socket, "EHLO " . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "\r\n");
    $response = fgets($socket, 515);
    
    // If we connected via plain connection, upgrade to TLS
    if ($connectionMethod === 'PLAIN') {
        // Check if STARTTLS is supported
        if (strpos($response, 'STARTTLS') !== false) {
            fwrite($socket, "STARTTLS\r\n");
            $response = fgets($socket, 515);
            if (substr($response, 0, 3) === '220') {
                // Enable crypto on the socket
                if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                    error_log("Failed to enable TLS encryption");
                    fclose($socket);
                    return false;
                }
                error_log("Successfully upgraded to TLS");
            } else {
                error_log("STARTTLS failed: $response");
                fclose($socket);
                return false;
            }
        } else {
            error_log("STARTTLS not supported by server");
            fclose($socket);
            return false;
        }
    }
    
    // Send AUTH LOGIN
    fwrite($socket, "AUTH LOGIN\r\n");
    $response = fgets($socket, 515);
    if (substr($response, 0, 3) !== '334') {
        error_log("SMTP AUTH LOGIN error: $response");
        fclose($socket);
        return false;
    }
    
    // Send username (base64 encoded)
    fwrite($socket, base64_encode(SMTP_USERNAME) . "\r\n");
    $response = fgets($socket, 515);
    if (substr($response, 0, 3) !== '334') {
        error_log("SMTP Username error: $response");
        fclose($socket);
        return false;
    }
    
    // Send password (base64 encoded)
    fwrite($socket, base64_encode(SMTP_PASSWORD) . "\r\n");
    $response = fgets($socket, 515);
    if (substr($response, 0, 3) !== '235') {
        error_log("SMTP Authentication failed: $response");
        fclose($socket);
        return false;
    }
    
    // Send MAIL FROM
    fwrite($socket, "MAIL FROM: <$fromEmail>\r\n");
    $response = fgets($socket, 515);
    if (substr($response, 0, 3) !== '250') {
        error_log("SMTP MAIL FROM error: $response");
        fclose($socket);
        return false;
    }
    
    // Send RCPT TO
    fwrite($socket, "RCPT TO: <$to>\r\n");
    $response = fgets($socket, 515);
    if (substr($response, 0, 3) !== '250') {
        error_log("SMTP RCPT TO error: $response");
        fclose($socket);
        return false;
    }
    
    // Send DATA
    fwrite($socket, "DATA\r\n");
    $response = fgets($socket, 515);
    if (substr($response, 0, 3) !== '354') {
        error_log("SMTP DATA error: $response");
        fclose($socket);
        return false;
    }
    
    // Send email headers and body
    $emailData = implode("\r\n", $headers) . "\r\n\r\n" . $emailBody . "\r\n.\r\n";
    fwrite($socket, $emailData);
    $response = fgets($socket, 515);
    if (substr($response, 0, 3) !== '250') {
        error_log("SMTP Email send error: $response");
        fclose($socket);
        return false;
    }
    
    // Send QUIT
    fwrite($socket, "QUIT\r\n");
    fclose($socket);
    
    error_log('Email sent successfully via SMTP to: ' . $to . ' Subject: ' . $subject);
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
    
    // Send email using SMTP
    return sendEmailSMTP(SMTP_TO_EMAIL, $subject, $body);
}

/**
 * Send notification email to admin about new contact submission
 * Note: We don't send emails to user-provided addresses for security
 * 
 * @param array $formData Contact form data
 * @return bool Success status
 */
function sendContactNotificationEmail($formData) {
    $subject = 'New Contact Form Submission - ' . htmlspecialchars($formData['subject']);
    
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
    $body .= '<p><strong>Action Required:</strong> Please respond to the user at ' . htmlspecialchars($formData['email']) . '</p>';
    $body .= '</div>';
    
    $body .= '</div></body></html>';
    
    // Send only to admin email, not to user's email
    $result = sendEmailSMTP(SMTP_TO_EMAIL, $subject, $body);
    if (!$result) {
        error_log('SMTP method failed for contact notification, trying webhook fallback');
        $result = sendEmailWebhook(SMTP_TO_EMAIL, $subject, $body);
    }
    return $result;
}
