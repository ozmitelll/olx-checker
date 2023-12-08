It's v1 application for subscribe on OLX announcements and get message on your mail if price changed.

If use this application you need:
  1. Copy this git repo in your IDE.
  2. Create termianl and write **composer install**.
  3. Write **php -S localhost:8000**
  4. Create another terminal and write **"start-process php -ArgumentList "run_demon.php" -NoNewWindow"** for start deamon (**if you use Windows**).
  5. Open folder **"service"** and change in file **MailSender.php** your @params in **sendEmail** function for (make you sender account for send messages).

  **Example:**
  * **$mail->Username = 'your@gmail.com';**
  * **$mail->Password = 'your app password';**

  **Take note.** Where get AppPassword you can see this info in Google.

It's all, enjoy for your use. =)
