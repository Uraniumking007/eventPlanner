<?php
// Test file to verify Docker watch plugin is working
// Change this timestamp and refresh the page to see if it updates

$timestamp = "2024-12-19 15:30:00 - Watch Plugin Test";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watch Plugin Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .test-box { 
            background: #f0f0f0; 
            padding: 20px; 
            border-radius: 8px; 
            margin: 20px 0;
            border-left: 4px solid #007cba;
        }
        .timestamp { 
            color: #007cba; 
            font-weight: bold; 
            font-size: 18px; 
        }
        .instructions { 
            background: #e7f3ff; 
            padding: 15px; 
            border-radius: 5px; 
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>🐳 Docker Watch Plugin Test</h1>
    
    <div class="test-box">
        <h2>Current Timestamp:</h2>
        <div class="timestamp"><?php echo $timestamp; ?></div>
    </div>
    
    <div class="instructions">
        <h3>To test if the watch plugin is working:</h3>
        <ol>
            <li>Change the timestamp variable in this file (line 4)</li>
            <li>Save the file</li>
            <li>Refresh this page in your browser</li>
            <li>If you see the new timestamp, the watch plugin is working! 🎉</li>
        </ol>
    </div>
    
    <div class="test-box">
        <h3>CSS Test:</h3>
        <p>Check your browser's developer tools to see if the CSS file is being updated with the new comment we added.</p>
        <p>Look for: <code>/* Event Planner Styles - Last Updated: 2024-12-19 */</code></p>
    </div>
    
    <div class="test-box">
        <h3>If it's not working:</h3>
        <ul>
            <li>Make sure you're running <code>docker compose watch</code></li>
            <li>Check that containers are running with <code>docker compose ps</code></li>
            <li>Verify the watch plugin configuration in docker-compose.yml</li>
            <li>Try restarting with <code>docker compose down && docker compose watch</code></li>
        </ul>
    </div>
    
    <p><a href="/">← Back to Event Planner</a></p>
</body>
</html>
