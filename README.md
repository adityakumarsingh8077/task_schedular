## ⏰ Task Scheduler

A lightweight **PHP-based task scheduling system** that automates repetitive jobs such as sending emails, managing subscriptions, and executing cron jobs.  
Built with **PHPMailer** for email handling and supports both Windows (`.bat`) and Linux (`.sh`) cron setup scripts.

---

### 📊 Badges

![Stars](https://img.shields.io/github/stars/adityakumarsingh8077/task_schedular?style=flat&logo=github) 
![Forks](https://img.shields.io/github/forks/adityakumarsingh8077/task_schedular?style=flat&logo=github) 
![Issues](https://img.shields.io/github/issues/adityakumarsingh8077/task_schedular) 
![License](https://img.shields.io/github/license/adityakumarsingh8077/task_schedular) 
![Contributors](https://img.shields.io/github/contributors/adityakumarsingh8077/task_schedular) 
![Last Commit](https://img.shields.io/github/last-commit/adityakumarsingh8077/task_schedular)

---

### 📖 Table of Contents

- [✨ Features](#-features)  
- [🛠 Tech Stack](#-tech-stack)  
- [⚡ Installation & Setup](#-installation--setup)  
- [📂 Folder Structure](#-folder-structure)  
- [🚀 Usage](#-usage)  
- [📸 Screenshots / Demo](#-screenshots--demo)  
- [🤝 Contribution Guide](#-contribution-guide)    
- [📬 Contact](#-contact)

---

### ✨ Features

- 📌 Schedule recurring tasks via cron jobs  
- 📧 Email notifications using **PHPMailer**  
- 📄 Logging system for executed tasks & errors  
- ⚙️ Supports Windows (`.bat`) and Linux (`.sh`) scripts  
- ✅ Simple and extensible design for task automation  
- 🗂 Manages **subscribers list, pending tasks, and logs**  

---

### 🛠 Tech Stack

**Languages & Tools Used:**  

![PHP](https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=white)
![PHPMailer](https://img.shields.io/badge/PHPMailer-Library-blue) 
![Shell Script](https://img.shields.io/badge/Shell_Script-121011?logo=gnu-bash&logoColor=white)  
![Batch Script](https://img.shields.io/badge/Batch-Windows-0078D6?logo=windows&logoColor=white)  
![Cron](https://img.shields.io/badge/Cron-Jobs-orange)  

---

### ⚡ Installation & Setup

Clone the repository:

```bash
git clone https://github.com/adityakumarsingh8077/task_schedular.git
cd task_schedular/src


```
Install Dependencies

PHPMailer is already included in the repo (PHPMailer-master). No extra installation required.

Setup Cron Jobs

Linux:
```
chmod +x setup_cron.sh
./setup_cron.sh
```

Windows:
```
setup_cron.bat
```
### 📂 Folder Structure
```
src/
│── PHPMailer-master/        # Email handling library
│── cron.php                 # Main cron execution script
│── cron_log.txt             # Cron job logs
│── email_log.txt            # Email logs
│── functions.php            # Core helper functions
│── index.php                # Entry point
│── pending_subscriptions.txt# Pending subscription records
│── setup_cron.bat           # Windows cron setup
│── setup_cron.sh            # Linux cron setup
│── subscribers.txt          # Subscriber list
│── tasks.txt                # Scheduled tasks
│── unsubscribe.php          # Unsubscribe endpoint
│── verify.php               # Verification script
```

### 🚀 Usage
Run Manually
```
php cron.php
```
Add New Task

Edit tasks.txt and append your task.

Example format:
```
task_name | schedule_time | command
```
Email Logs

Logs are stored in:
```
cron_log.txt

email_log.txt
```
#### 📸 Screenshots / Demo


### 🤝 Contribution Guide

#### Fork the repository

Create a new feature branch (```git checkout -b feature-name```)

Commit your changes (```git commit -m "Added new feature"```)

Push to your branch (```git push origin feature-name```)

Open a Pull Request 🎉

### 📜 License

This project is licensed under the MIT License.
See the LICENSE file for details.

### 📬 Contact

👤 Aditya Kumar Singh

GitHub: https://github.com/adityakumarsingh8077

LinkedIn: www.linkedin.com/in/aditya-kumar-singh-ab139a242

Email: singhaditya80777@gmail.com
