# Automatic Birthday Wish
Send birthday wish to your friends, family, colleagues using PHP, PHPMailer &amp; Cron
<ul>
  <li>Create a directory named "phpMailer" in the main directory.</li>
  <li>Open terminal in "phpMailer" directory and run "composer require phpmailer/phpmailer". For more info, goto https://github.com/PHPMailer/PHPMailer</li>
  <li>Create app password (not your account password) in your google account. (Help: https://support.google.com/mail/answer/185833?hl=en#:~:text=Create%20%26%20use%20App%20Passwords)</li>
  <li>Set your email id as "Username" & newly generated app password as "Password"</li>
  <li>Edit the birthdays.txt as per your need.(Keep the format as it is)</li>
  <li>
    Now set up the cronjob at 12:00 am <br>
    &emsp;- Open Terminal<br>
    &emsp;- crontab -e<br>
    &emsp;- Add this line<br>
    &emsp;&emsp;0 0 * * * sh /path/to/the/directory/sendBirthdayWish.sh
  </li>
</ul>  
 
