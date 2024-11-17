To include the formatted code snippets and sections into your `README.md` file, you'll need to use proper Markdown syntax. Below is the cleaned-up, fully formatted version you can directly copy and paste into your `README.md` file:

```markdown
# 📦 Merchsystem  

Welcome to **Merchsystem** — an e-commerce platform designed to centralize all gaming products, accessories, and plush items in one convenient place. Merchsystem replicates the full functionality of a real-world e-commerce platform, offering users a seamless shopping experience.  

---

## 🛠️ Features  

- **User Authentication**: Secure login and registration system with reCAPTCHA.  
- **Email Functionality**: Integrated with PHPMailer to send OTPs and transactional emails.  
- **Payment Gateway**: Enables smooth and secure payments.  

---

## 📚 Installation Guide  

### Install Dependencies  
Use Composer to install the required PHP packages, including PHPMailer.  
```bash  
composer require phpmailer/phpmailer  
```  

### Configure Environment  
1. Set up your database in XAMPP.  
2. Update the database credentials in the project configuration file (e.g., `config.php`).  

### Run the Application  
1. Start XAMPP.  
2. Ensure Apache and MySQL are running.  
3. Open the project in your browser: `http://localhost/merchsystem`.  

---

## 📧 Email Functionality with PHPMailer  

This project utilizes **PHPMailer** for email functionalities such as sending OTPs and transactional messages.  

### Install PHPMailer  
Run the following Composer command to include PHPMailer in your project:  
```bash  
composer require phpmailer/phpmailer  
```  

### Example Code  
Here’s an example of how to use PHPMailer in Merchsystem:  
```php  
use PHPMailer\PHPMailer\PHPMailer;  
use PHPMailer\PHPMailer\Exception;  

require 'vendor/autoload.php';  

$mail = new PHPMailer(true);  

try {  
    $mail->isSMTP();  
    $mail->Host       = 'smtp.example.com';  
    $mail->SMTPAuth   = true;  
    $mail->Username   = 'your-email@example.com';  
    $mail->Password   = 'your-password';  
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  
    $mail->Port       = 587;  

    $mail->setFrom('your-email@example.com', 'Merchsystem');  
    $mail->addAddress('recipient@example.com');  

    $mail->isHTML(true);  
    $mail->Subject = 'Welcome to Merchsystem!';  
    $mail->Body    = 'Your OTP is <b>123456</b>';  

    $mail->send();  
    echo 'Message has been sent';  
} catch (Exception $e) {  
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";  
}  
```  

---

## 👨‍💻 Contributing  

Contributions are welcome! Please fork the repository and submit a pull request with your changes.  

---

## 📄 License  

This project is licensed under the [MIT License](LICENSE).  

---

## 🌟 Acknowledgments  

Special thanks to the developers and contributors of open-source libraries, especially PHPMailer, for making this project possible!  
```

Simply paste this into your `README.md` file, and it will render properly on GitHub with all the formatting and code blocks intact! Let me know if you need further adjustments.
