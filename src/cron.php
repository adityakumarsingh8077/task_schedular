<?php

require_once __DIR__ . '/functions.php';

$subscribers = read_json_file(__DIR__ . '/subscribers.txt');
$tasks = getAllTasks();

foreach ($subscribers as $email) {
    $pending_tasks = array_filter($tasks, function($task) {
        return !$task['completed'];
    });

    if (!empty($pending_tasks)) {
        $subject = 'Task Planner - Pending Tasks Reminder';
        $unsubscribe_link = "http://yourdomain.com/unsubscribe.php?email=".urlencode($email);
        
        $html_message = '<html>
        <body>
            <h2>Pending Tasks Reminder</h2>
            <ul>';
        
        foreach ($pending_tasks as $task) {
            $html_message .= "<li>".htmlspecialchars($task['name'])."</li>";
        }
        
        $html_message .= '</ul>
            <p><a href="'.$unsubscribe_link.'">Unsubscribe from notifications</a></p>
        </body>
        </html>';

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: Task Planner <noreply@taskplanner.com>\r\n";

        mail($email, $subject, $html_message, $headers);
    }
}
?>
