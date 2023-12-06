It's v1 application for subscribe on OLX announcements and get message on your mail if price changed.

If use this application you need:
  1. Copy this git repo in your IDE.
  2. Create terminal and write **php -S localhost:(your port)**
  3. Create another terminal and write **"start-process php -ArgumentList "run_demon.php" -NoNewWindow"** for start deamon (**if you use Windows**).
  4. Open folder **"service"** and change in file **MailSender.php** your @params in **sendEmail** function for (make you sender account for send messages).

  **Example:**
      * **$mail->Username = 'your@gmail.com';**
      * **$mail->Password = 'your app password';**

  **Take note.** Where get AppPassword you can see this info in Google.

It's all, enjoy for your use. =)
