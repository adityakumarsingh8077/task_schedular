#!/bin/bash
# Cron job: Daily 8AM par cron.php run karega
(crontab -l 2>/dev/null; echo "0 8 * * * /usr/bin/php $(pwd)/cron.php >> $(pwd)/cron.log 2>&1") | crontab -
