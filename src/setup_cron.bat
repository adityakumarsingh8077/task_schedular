@echo off
set "TASK_NAME=TaskPlannerReminders"
set "SCRIPT_PATH=%~dp0cron.php"
set "PHP_EXE=C:\xampp\php\php.exe"
schtasks /create /tn "%TASK_NAME%" /tr "\"%PHP_EXE%\" \"%SCRIPT_PATH%\"" /sc hourly /mo 1 /f
echo Cron job configured! Check Task Scheduler.
pause
