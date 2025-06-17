<?php

require_once __DIR__ . '/functions.php';

$message = '';
$is_error = false;

session_start();

if (isset($_GET['verified']) && $_GET['verified'] == '1') {
$verified_email = $_GET['email'] ?? '';
$message = "🎉 Email verified successfully! You're now subscribed to task reminders: " . htmlspecialchars($verified_email);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['task-name'])) {
    $task_name = trim($_POST['task-name']);
    if (!empty($task_name)) {
        if (addTask($task_name)) {
            $message = "Task added successfully!";
            $is_error = false;
        } else {
            $message = "Task already exists!";
            $is_error = true;
        }
    }
}


    if (isset($_POST['task_id'], $_POST['completed'])) {
        markTaskAsCompleted($_POST['task_id'], $_POST['completed'] === '1');
        $message = "Task status updated!";
    }
    
    if (isset($_POST['delete_id'])) {
        deleteTask($_POST['delete_id']);
        $message = "Task deleted!";
    }
    
    if (isset($_POST['email'])) {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        if ($email) {
            subscribeEmail($email);
            header("Location: verify.php?email=" . urlencode($email));
            exit;
        } else {
            $message = "Invalid email address!";
        }
    }
}

$tasks = getAllTasks();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Task Planner</title>
<style>
 body {
    font-family: 'Poppins', sans-serif;
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
    background: linear-gradient(to right, #e3f2fd, #fff);
    color: #333;
}

.container {
    background: rgba(255, 255, 255, 0.4);
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    transition: 0.3s ease;
}

.header {
    text-align: center;
    margin-bottom: 30px;
    font-weight: 500;
    color: #007bff;
}

.section {
    margin: 30px 0;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #f9fbfd;
    transition: 0.3s;
}

#task-name,
input[type="email"] {
    padding: 12px;
    width: 300px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    transition: 0.3s;
}

#task-name:focus,
input[type="email"]:focus {
    border-color: #007bff;
    box-shadow: 0 0 6px rgba(0, 123, 255, 0.2);
    outline: none;
}

#add-task,
#submit-email {
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    font-size: 14px;
    cursor: pointer;
    font-weight: 500;
    transition: 0.3s ease;
}

#add-task {
    background: linear-gradient(to right, #007bff, #00c6ff);
    color: white;
}

#add-task:hover {
    background: linear-gradient(to right, #0069d9, #00b3e6);
    transform: scale(1.03);
}

#submit-email {
    background: linear-gradient(to right, #28a745, #5cd68a);
    color: white;
}

#submit-email:hover {
    background: linear-gradient(to right, #218838, #4ac77c);
    transform: scale(1.03);
}

#tasks-list {
    list-style: none;
    padding: 0;
}

.task-item {
    padding: 15px;
    border: 1px solid #ddd;
    margin: 8px 0;
    border-radius: 5px;
    background: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: 0.3s;
}

.task-item:hover {
    background: #f0f8ff;
}

.task-status {
    margin-right: 15px;
}

.delete-task {
    background: #dc3545;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 4px;
    cursor: pointer;
    transition: 0.3s ease;
    font-size: 14px;
}

.delete-task:hover {
    background: #c82333;
    transform: scale(1.05);
}

.completed {
    text-decoration: line-through;
    opacity: 0.7;
}

.success-msg {
    background: #d4edda;
    color: #155724;
    padding: 15px;
    border-radius: 5px;
    margin: 10px 0;
    border: 1px solid #c3e6cb;
    font-size: 14px;
}

.error-msg {
    background: #f8d7da;
    color: #721c24;
    padding: 15px;
    border-radius: 5px;
    margin: 10px 0;
    border: 1px solid #f5c6cb;
    font-size: 14px;
}

.nav-links {
    text-align: center;
    margin: 20px 0;
}

.nav-links a {
    margin: 0 15px;
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
    transition: 0.3s;
}

.nav-links a:hover {
    text-decoration: underline;
    color: #0056b3;
}
</style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Task Planner</h1>
            <div class="nav-links">
                <a href="index.php"> Home</a>
                <a href="verify.php"> Verify Email</a>
                <a href="unsubscribe.php">Unsubscribe</a>
            </div>
        </div>

        <?php if ($message): ?>
        <div class="<?= $is_error ? 'error-msg' : 'success-msg' ?>">~
            <?= htmlspecialchars($message) ?>
        </div>
         <?php endif; ?>

      

        <div class="section">
            <h2>Add New Task</h2>
            <form method="POST">
                <input type="text" name="task-name" id="task-name" placeholder="Enter new task" required>
                <button type="submit" id="add-task">Add Task</button>
            </form>
        </div>

        <div class="section">
            <h2>Your Tasks</h2>
            <ul id="tasks-list">
                <?php if (empty($tasks)): ?>
                    <li style="text-align: center; color: #666;">No tasks yet. Add your first task above!</li>
                <?php else: ?>
                    <?php foreach ($tasks as $task): ?>
                        <li class="task-item <?= $task['completed'] ? 'completed' : '' ?>">
                            <form method="POST" style="display: inline;">
                                <input type="checkbox" class="task-status" 
                                    <?= $task['completed'] ? 'checked' : '' ?>
                                    onchange="this.form.submit()">
                                <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                <input type="hidden" name="completed" value="<?= $task['completed'] ? '0' : '1' ?>">
                            </form>
                            
                            <span><?= htmlspecialchars($task['name']) ?></span>
                            
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="delete_id" value="<?= $task['id'] ?>">
                                <button type="submit" class="delete-task" 
                                    onclick="return confirm('Delete this task?')">Delete</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>

        <div class="section">
            <h2>📧 Get Task Reminders</h2>
            <p>Subscribe to get daily email reminders for pending tasks!</p>
            <form method="POST">
                <input type="email" name="email" placeholder="Enter your email" required>
                <button type="submit" id="submit-email">Subscribe</button>
            </form>
            <p style="margin-top: 10px; font-size: 14px; color: #666;">
                Already subscribed? <a href="unsubscribe.php">Unsubscribe here</a>
            </p>
        </div>
    </div>
</body>

</html>