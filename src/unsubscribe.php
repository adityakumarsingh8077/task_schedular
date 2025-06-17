<?php

require_once __DIR__ . '/functions.php';

$message = '';
$email = isset($_GET['email']) ? filter_var($_GET['email'], FILTER_SANITIZE_EMAIL) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    
    if ($email) {
        if (unsubscribeEmail($email)) {
            // Redirect to index with unsubscribe success
            header("Location: index.php?unsubscribed=1&email=" . urlencode($email));
            exit;
        } else {
            $message = "This email is not subscribed.";
        }
    } else {
        $message = "Invalid email address!";
    }
}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Unsubscribe</title>
    <style>
 body {
        font-family: 'Poppins', sans-serif;
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        background: linear-gradient(135deg, #ffe6e6, #fff5f5);
        color: #333;
    }

    .container {
        background: rgba(255, 255, 255, 0.45);
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(231, 76, 60, 0.3);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        transition: 0.4s ease;
    }

    #unsubscription-heading {
        color: #e74c3c;
        border-bottom: 3px solid #e74c3c;
        padding-bottom: 10px;
        text-align: center;
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 25px;
    }

    .form-group {
        margin: 20px 0;
    }

    input[type="email"] {
        padding: 15px;
        width: 100%;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 16px;
        transition: 0.3s;
    }

    input[type="email"]:focus {
        outline: none;
        border-color: #e74c3c;
        box-shadow: 0 0 6px rgba(231, 76, 60, 0.3);
    }

    button {
        padding: 15px 30px;
        background: linear-gradient(to right, #e74c3c, #ff6b6b);
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        width: 100%;
        font-size: 16px;
        font-weight: 500;
        transition: 0.3s;
    }

    button:hover {
        background: linear-gradient(to right, #d84339, #ff5252);
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

    .nav-links {
        text-align: center;
        margin: 30px 0;
    }

    .nav-links a {
        margin: 0 15px;
        color: #e74c3c;
        text-decoration: none;
        font-weight: 500;
        transition: 0.3s;
    }

    .nav-links a:hover {
        color: #c0392b;
        text-decoration: underline;
    }
    </style>
</head>
<body>
    <div class="container">
        <h2 id="unsubscription-heading">❌ Unsubscribe from Task Updates</h2>
        
        <div class="nav-links">
            <a href="index.php">🏠 Back to Home</a>
            <a href="verify.php">✅ Verify Email</a>
        </div>
        
        <?php if ($message): ?>
            <div class="error-msg"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <p>We're sorry to see you go! Enter your email below to unsubscribe from task reminders.</p>

        <form method="POST">
            <div class="form-group">
                <input type="email" name="email" 
                       placeholder="Enter your email" 
                       value="<?= htmlspecialchars($email) ?>"
                       <?= $email ? 'readonly' : '' ?>
                       required>
            </div>
            <button type="submit">Unsubscribe</button>
        </form>
    </div>