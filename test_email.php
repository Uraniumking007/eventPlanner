<?php
/**
 * Email Test Script with Zoho API Support
 * Test both SMTP and Zoho API methods
 */

require_once __DIR__ . '/config/email.php';

echo "<h2>Email Configuration Test</h2>";

// Display current configuration
echo "<h3>Current Configuration:</h3>";
echo "<ul>";
echo "<li><strong>SMTP Host:</strong> " . SMTP_HOST . "</li>";
echo "<li><strong>SMTP Port:</strong> " . SMTP_PORT . "</li>";
echo "<li><strong>SMTP Username:</strong> " . SMTP_USERNAME . "</li>";
echo "<li><strong>SMTP Password:</strong> " . (SMTP_PASSWORD === 'YOUR_APP_PASSWORD_HERE' ? 'NOT SET (using placeholder)' : 'SET') . "</li>";
echo "<li><strong>From Email:</strong> " . SMTP_FROM_EMAIL . "</li>";
echo "<li><strong>To Email:</strong> " . SMTP_TO_EMAIL . "</li>";
echo "</ul>";

// SMTP Configuration Status
echo "<h3>SMTP Configuration Status:</h3>";
echo "<ul>";
echo "<li><strong>SMTP Method:</strong> Primary email sending method</li>";
echo "<li><strong>Connection:</strong> Direct SMTP to " . SMTP_HOST . ":" . SMTP_PORT . "</li>";
echo "<li><strong>Authentication:</strong> " . (SMTP_PASSWORD === 'YOUR_APP_PASSWORD_HERE' ? '❌ Not Configured' : '✅ Configured') . "</li>";
echo "</ul>";

// Test email sending
if (isset($_POST['test_email'])) {
    $testEmail = $_POST['test_email'];
    
    if (!filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
        echo "<div style='color: red;'>Invalid email address!</div>";
    } else {
        echo "<h3>Testing Email to: $testEmail</h3>";
        
        $subject = 'Test Email from Event Planner';
        $body = '<html><body>';
        $body .= '<h2>Test Email</h2>';
        $body .= '<p>This is a test email from your Event Planner contact form.</p>';
        $body .= '<p><strong>Test User Email:</strong> ' . htmlspecialchars($testEmail) . '</p>';
        $body .= '<p>If you receive this email, your email configuration is working correctly!</p>';
        $body .= '<p>Sent at: ' . date('Y-m-d H:i:s') . '</p>';
        $body .= '</body></html>';
        
        echo "<p>Sending test email...</p>";
        
        // Test sending to admin email (not user email for security)
        $adminEmail = SMTP_TO_EMAIL;
        $testSubject = 'Test Email from Event Planner - ' . $testEmail;
        $testBody = '<html><body>';
        $testBody .= '<h2>Test Email</h2>';
        $testBody .= '<p>This is a test email from your Event Planner contact form.</p>';
        $testBody .= '<p><strong>Test User Email:</strong> ' . htmlspecialchars($testEmail) . '</p>';
        $testBody .= '<p>If you receive this email, your email configuration is working correctly!</p>';
        $testBody .= '<p>Sent at: ' . date('Y-m-d H:i:s') . '</p>';
        $testBody .= '</body></html>';
        
        // Send email using SMTP
        $result = sendEmailSMTP($adminEmail, $testSubject, $testBody);
        
        if ($result) {
            echo "<div style='color: green;'>✅ Email sent successfully to admin using SMTP!</div>";
            echo "<div style='color: blue;'>📧 Email sent to: " . htmlspecialchars($adminEmail) . "</div>";
            echo "<div style='color: blue;'>👤 User email included in content: " . htmlspecialchars($testEmail) . "</div>";
        } else {
            echo "<div style='color: red;'>❌ SMTP failed. Check your configuration and server logs.</div>";
            echo "<div style='color: orange;'>💡 Make sure to set your Zoho app password in the .env file</div>";
        }
    }
}

// Check server requirements
echo "<h3>Server Requirements:</h3>";
echo "<ul>";
echo "<li><strong>cURL Extension:</strong> " . (extension_loaded('curl') ? '✅ Available' : '❌ Not Available') . "</li>";
echo "<li><strong>mail() Function:</strong> " . (function_exists('mail') ? '✅ Available' : '❌ Not Available') . "</li>";
echo "<li><strong>PHP Version:</strong> " . phpversion() . "</li>";
echo "<li><strong>OpenSSL:</strong> " . (extension_loaded('openssl') ? '✅ Available' : '❌ Not Available') . "</li>";
echo "</ul>";

// Show error log if available
$errorLog = ini_get('error_log');
if ($errorLog && file_exists($errorLog)) {
    echo "<h3>Recent Error Log Entries:</h3>";
    $logContent = file_get_contents($errorLog);
    $logLines = explode("\n", $logContent);
    $recentLines = array_slice($logLines, -10);
    echo "<pre style='background: #f5f5f5; padding: 10px; border-radius: 5px;'>";
    foreach ($recentLines as $line) {
        if (strpos($line, 'email') !== false || strpos($line, 'SMTP') !== false || strpos($line, 'Zoho') !== false) {
            echo htmlspecialchars($line) . "\n";
        }
    }
    echo "</pre>";
}
?>

<form method="POST" style="margin-top: 20px; padding: 20px; border: 1px solid #ccc; border-radius: 5px;">
    <h3>Send Test Email</h3>
    <p>Enter a test email address (this will be included in the email content sent to admin):</p>
    <input type="email" name="test_email" placeholder="test-user@example.com" required style="padding: 8px; width: 300px;">
    <button type="submit" style="padding: 8px 16px; background: #007cba; color: white; border: none; border-radius: 3px; cursor: pointer;">Send Test Email</button>
    <div style="margin-top: 10px; padding: 10px; background: #e7f3ff; border-radius: 3px; font-size: 14px;">
        <strong>Note:</strong> The test email will be sent to <code><?php echo SMTP_TO_EMAIL; ?></code> (admin email) with the test email address included in the content for security.
    </div>
</form>

<div style="margin-top: 20px; padding: 15px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px;">
    <h4>⚠️ Important Setup Steps:</h4>
    <ol>
        <li><strong>Update SMTP_PASSWORD</strong> in your <code>.env</code> file with your Zoho app password</li>
        <li><strong>Generate App Password:</strong> Go to Zoho Mail → Settings → Security → App Passwords</li>
        <li><strong>Use App Password:</strong> Not your regular Zoho password, but the generated app password</li>
        <li><strong>Check Domain:</strong> Make sure your domain <code>bhaveshp.dev</code> is properly configured in Zoho</li>
    </ol>
</div>

<div style="margin-top: 20px; padding: 15px; background: #d1ecf1; border: 1px solid #bee5eb; border-radius: 5px;">
    <h4>📧 Email Method:</h4>
    <p><strong>SMTP</strong> - Direct connection to smtp.zoho.com:587 using TLS encryption</p>
    <p><strong>Benefits:</strong> Simple, reliable, and secure email delivery using standard SMTP protocol.</p>
</div>
