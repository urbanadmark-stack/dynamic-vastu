<?php
/**
 * Password Hash Generator
 * Run this file once to generate a password hash for your admin user
 * Then update the database schema.sql file or directly in phpMyAdmin
 * 
 * Usage: 
 * 1. Open this file in your browser: yoursite.com/setup-password.php
 * 2. Enter your desired password
 * 3. Copy the generated hash
 * 4. Update the admin_users table in your database with the new hash
 * 5. DELETE THIS FILE after use for security!
 */

$password_hash = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Hash Generator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            background: #2563eb;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #1e40af;
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            background: #f0f9ff;
            border: 1px solid #2563eb;
            border-radius: 4px;
        }
        .hash {
            font-family: monospace;
            word-break: break-all;
            background: white;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }
        .warning {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Admin Password Hash Generator</h1>
        
        <form method="POST">
            <div class="form-group">
                <label for="password">Enter your desired admin password:</label>
                <input type="password" id="password" name="password" required autofocus>
            </div>
            <button type="submit">Generate Hash</button>
        </form>
        
        <?php if (!empty($password_hash)): ?>
            <div class="result">
                <h3>✅ Password Hash Generated!</h3>
                <p>Copy this hash and use it to update your admin user password in the database:</p>
                <div class="hash"><?php echo htmlspecialchars($password_hash); ?></div>
                <p style="margin-top: 15px; font-size: 14px; color: #666;">
                    <strong>To update in phpMyAdmin:</strong><br>
                    1. Go to your database → admin_users table<br>
                    2. Edit the admin user<br>
                    3. Replace the password field with the hash above<br>
                    4. Save
                </p>
            </div>
        <?php endif; ?>
        
        <div class="warning">
            <strong>⚠️ Security Warning:</strong><br>
            Delete this file (setup-password.php) immediately after generating your password hash!
        </div>
    </div>
</body>
</html>

