<?php
require_once __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function read_json_file($filename) {
    if (!file_exists($filename)) return [];
    $data = file_get_contents($filename);
    $json = json_decode($data, true);
    return $json ? $json : [];
}

function write_json_file($filename, $data) {
    file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
}

function read_json_assoc_file($filename) {
    if (!file_exists($filename)) return [];
    $data = file_get_contents($filename);
    $json = json_decode($data, true);
    return is_array($json) ? $json : [];
}

function write_json_assoc_file($filename, $data) {
    file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
}

function addTask(string $task_name): bool {
    $file = __DIR__ . '/tasks.txt';
    $tasks = read_json_file($file);

    // Check for duplicate (case-insensitive, trimmed)
    foreach ($tasks as $task) {
       if (strtolower(trim($task['name'])) === strtolower(trim($task_name))) {

            // Duplicate found, do not add
            return false;
        }
    }

    $new_task = [
        'id' => uniqid(),
        'name' => $task_name,
        'completed' => false
    ];
    $tasks[] = $new_task;
    write_json_file($file, $tasks);
    return true;
}
function getAllTasks(): array {
    $file = __DIR__ . '/tasks.txt';
    return read_json_file($file);
}

function markTaskAsCompleted(string $task_id, bool $is_completed): bool {
    $file = __DIR__ . '/tasks.txt';
    $tasks = read_json_file($file);
    
    foreach ($tasks as &$task) {
        if ($task['id'] === $task_id) {
            $task['completed'] = $is_completed;
            write_json_file($file, $tasks);
            return true;
        }
    }
    return false;
}

function deleteTask(string $task_id): bool {
    $file = __DIR__ . '/tasks.txt';
    $tasks = read_json_file($file);
    
    $new_tasks = array_filter($tasks, function($task) use ($task_id) {
        return $task['id'] !== $task_id;
    });
    
    write_json_file($file, array_values($new_tasks));
    return true;
}

function generateVerificationCode(): string {
    return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}

function subscribeEmail(string $email): bool {
    $file = __DIR__ . '/pending_subscriptions.txt';
    $pending = read_json_assoc_file($file);
    
    $code = generateVerificationCode();
    $pending[$email] = $code;
    write_json_assoc_file($file, $pending);
    
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'singhaditya8077@gmail.com';
        // $mail->Password = 'iukh tcuq izri uoys';
        $mail->Password = 'vhvgrisokkfuoqoj';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('singhaditya8077@gmail.com', 'Task Scheduler');
        $mail->addAddress($email);
        $mail->Subject = 'Verify Your Subscription';
        $mail->Body = "Your verification code: $code\n\nEnter this code to confirm subscription.";
        $mail->isHTML(false);
        
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = function($str, $level) {
            file_put_contents(__DIR__ . "/email_log.txt", $str.PHP_EOL, FILE_APPEND);
        };

        $mail->send();
        return true;
    } catch (Exception $e) {
        file_put_contents(__DIR__ . "/email_log.txt", "Mailer Error: {$mail->ErrorInfo}\n", FILE_APPEND);
        return false;
    }
}

function verifySubscription(string $email, string $code): bool {
    $pending_file = __DIR__ . '/pending_subscriptions.txt';
    $subscribers_file = __DIR__ . '/subscribers.txt';
    
    $pending = read_json_assoc_file($pending_file);
    $subscribers = read_json_file($subscribers_file);
    
    if (isset($pending[$email]) && $pending[$email] === $code) {
        unset($pending[$email]);
        write_json_assoc_file($pending_file, $pending);
        
        if (!in_array($email, $subscribers)) {
            $subscribers[] = $email;
            write_json_file($subscribers_file, $subscribers);
        }
        return true;
    }
    return false;
}

function unsubscribeEmail(string $email): bool {
    $subscribers_file = __DIR__ . '/subscribers.txt';
    $subscribers = read_json_file($subscribers_file);
    
    $new_subscribers = array_filter($subscribers, function($e) use ($email) {
        return $e !== $email;
    });
    
    write_json_file($subscribers_file, array_values($new_subscribers));
    return true;
}

function sendTaskReminders(): void {
    $subscribers_file = __DIR__ . '/subscribers.txt';
    $subscribers = read_json_file($subscribers_file);
    $tasks = getAllTasks();
    
    foreach ($subscribers as $email) {
        $pending_tasks = array_filter($tasks, function($task) {
            return !$task['completed'];
        });
        
        if (!empty($pending_tasks)) {
            sendTaskEmail($email, $pending_tasks);
        }
    }
}

function sendTaskEmail(string $email, array $pending_tasks): bool {
    $subject = 'Task Planner - Pending Tasks Reminder';
    $task_list = '';
    
    foreach ($pending_tasks as $index => $task) {
        $task_list .= sprintf("%d. %s\n", $index+1, $task['name']);
    }
    
    $message = "Pending Tasks:\n" . $task_list . "\n";
    $message .= "Unsubscribe here: " . 
                "http://yourdomain.com/unsubscribe.php?email=" . urlencode($email);
    
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sarthakagarwal2104@gmail.com';
        $mail->Password = 'iukh tcuq izri uoys';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('sarthakagarwal2104@gmail.com', 'Task Scheduler');
        $mail->addAddress($email);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->isHTML(false);

        $mail->send();
        return true;
    } catch (Exception $e) {
        file_put_contents(__DIR__ . "/email_log.txt", "Mailer Error: {$mail->ErrorInfo}\n", FILE_APPEND);
        return false;
    }
}

?>
