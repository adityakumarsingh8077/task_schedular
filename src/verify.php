<?php
require_once __DIR__ . '/functions.php';

$message = '';
$email = isset($_GET['email']) ? filter_var($_GET['email'], FILTER_SANITIZE_EMAIL) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $code = trim($_POST['code'] ?? '');

    if ($email && $code) {
        if (verifySubscription($email, $code)) {
            // Success - redirect back to index with success message
            header("Location: index.php?verified=1&email=" . urlencode($email));
            exit;
        } else {
            $message = "❌ Invalid code or email. Please check and try again.";
        }
    } else {
        $message = "⚠️ Please fill all fields correctly.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify Subscription</title>
    <style>
        /* body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        #verification-heading { color: #2c3e50; border-bottom: 3px solid #3498db; padding-bottom: 10px; text-align: center; }
        .form-group { margin: 20px 0; }
        input[type="email"], input[type="text"] { padding: 15px; width: 100%; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; }
        button { padding: 15px 30px; background: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-size: 16px; }
        .error-msg { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .nav-links { text-align: center; margin: 30px 0; }
        .nav-links a { margin: 0 15px; color: #3498db; text-decoration: none; }
        .info-box { background: #e7f3ff; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #3498db; } */
      body {
        font-family: 'Poppins', sans-serif;
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
        background: linear-gradient(135deg, #e0f7fa, #e3f2fd);
        color: #333;
    }

    .container {
        background: rgba(255, 255, 255, 0.45);
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 8px 24px rgba(0, 128, 255, 0.3);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        transition: 0.4s ease;
    }

    #verification-heading {
        color: #007bff;
        border-bottom: 3px solid #007bff;
        padding-bottom: 12px;
        text-align: center;
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 30px;
    }

    .form-group {
        margin: 25px 0;
    }

    input[type="email"],
    input[type="text"] {
        padding: 14px;
        width: 100%;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    input[type="email"]:focus,
    input[type="text"]:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
    }

    button {
        padding: 14px 30px;
        background: linear-gradient(to right, #007bff, #00bfff);
        color: #fff;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
        font-size: 16px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    button:hover {
        background: linear-gradient(to right, #006fe6, #00aee6);
        transform: scale(1.02);
    }

    .error-msg {
        background: #f8d7da;
        color: #721c24;
        padding: 15px;
        border-radius: 6px;
        margin: 15px 0;
        border: 1px solid #f5c6cb;
        font-size: 14px;
    }

    .info-box {
        background: #e7f3ff;
        padding: 20px;
        border-radius: 10px;
        margin: 25px 0;
        border-left: 5px solid #007bff;
        font-size: 15px;
    }

    .nav-links {
        text-align: center;
        margin: 30px 0;
    }

    .nav-links a {
        margin: 0 15px;
        color: #007bff;
        text-decoration: none;
        font-weight: 500;
        transition: 0.3s;
    }

    .nav-links a:hover {
        color: #0056b3;
        text-decoration: underline;
    }
    
    </style>
</head>
<body>
    <div class="container">
        <h2 id="verification-heading">📧 Subscription Verification</h2>
        
        <div class="nav-links">
            <a href="index.php">🏠 Back to Home</a>
            <a href="unsubscribe.php">❌ Unsubscribe</a>
        </div>
        
        <?php if ($message): ?>
            <div class="error-msg"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="info-box">
            <strong>📩 Check your email!</strong><br>
            We've sent a 6-digit verification code to your email address. 
            Enter it below to complete your subscription.
        </div>

        <form method="POST">
            <div class="form-group">
                <label>Email Address:</label>
                <input type="email" name="email" 
                       value="<?= htmlspecialchars($email) ?>" 
                       <?= $email ? 'readonly' : '' ?> 
                       placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label>Verification Code:</label>
                <input type="text" name="code" placeholder="Enter 6-digit verification code" 
                       maxlength="6" pattern="[0-9]{6}" required>
            </div>
            <button type="submit">✅ Verify Now</button>
        </form>
    </div>
</body>
</html>
