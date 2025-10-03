<?php
declare(strict_types=1);

require_once __DIR__ . '/init.php';
require_once __DIR__ . '/../config/email.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['error' => 'Method not allowed'], 405);
}

try {
    // Get form data
    $data = read_json_body();
    
    // Validate required fields
    $requiredFields = ['firstName', 'lastName', 'email', 'subject', 'message'];
    $missingFields = [];
    
    foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            $missingFields[] = $field;
        }
    }
    
    if (!empty($missingFields)) {
        json_response([
            'error' => 'Missing required fields',
            'missing_fields' => $missingFields
        ], 400);
    }
    
    // Validate email format
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        json_response(['error' => 'Invalid email format'], 400);
    }
    
    // Sanitize input data
    $formData = [
        'firstName' => trim($data['firstName']),
        'lastName' => trim($data['lastName']),
        'email' => trim($data['email']),
        'phone' => trim($data['phone'] ?? ''),
        'subject' => trim($data['subject']),
        'message' => trim($data['message']),
        'newsletter' => !empty($data['newsletter'])
    ];
    
    // Validate subject options
    $validSubjects = ['general', 'support', 'billing', 'feature', 'partnership', 'other'];
    if (!in_array($formData['subject'], $validSubjects)) {
        json_response(['error' => 'Invalid subject selection'], 400);
    }
    
    // Convert subject code to readable format
    $subjectMap = [
        'general' => 'General Inquiry',
        'support' => 'Technical Support',
        'billing' => 'Billing Question',
        'feature' => 'Feature Request',
        'partnership' => 'Partnership',
        'other' => 'Other'
    ];
    $formData['subject'] = $subjectMap[$formData['subject']];
    
    // Store contact submission in database (optional)
    try {
        $contactId = insert(
            'INSERT INTO contact_submissions (first_name, last_name, email, phone, subject, message, newsletter_opt_in, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())',
            [
                $formData['firstName'],
                $formData['lastName'],
                $formData['email'],
                $formData['phone'],
                $formData['subject'],
                $formData['message'],
                $formData['newsletter'] ? 1 : 0,
                $_SERVER['REMOTE_ADDR'] ?? '',
                $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]
        );
    } catch (Exception $e) {
        // Log error but don't fail the email sending
        error_log('Failed to store contact submission: ' . $e->getMessage());
    }
    
    // Send email to admin
    $emailSent = sendContactEmail($formData);
    
    if (!$emailSent) {
        json_response(['error' => 'Failed to send email. Please try again later.'], 500);
    }
    
    // Send auto-reply to user
    $autoReplySent = sendAutoReplyEmail($formData);
    
    // Log the submission
    error_log('Contact form submitted: ' . $formData['email'] . ' - ' . $formData['subject']);
    
    // Return success response
    json_response([
        'success' => true,
        'message' => 'Thank you for your message! We\'ll get back to you within 24 hours.',
        'auto_reply_sent' => $autoReplySent
    ]);
    
} catch (Exception $e) {
    error_log('Contact form error: ' . $e->getMessage());
    json_response(['error' => 'An unexpected error occurred. Please try again later.'], 500);
}
