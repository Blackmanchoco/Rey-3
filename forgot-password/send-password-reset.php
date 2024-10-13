<?php
session_start();
include("../database/database.php");
include("../phpMailer/mailer.php");
require_once("../function/function.php");

// Check if 'email_address' is set from the form submission
if (isset($_POST['email_address'])) {
  // If email address is not empty, sanitize and store it in session
    if (!empty($_POST['email_address'])) {
        $_SESSION['email_res'] = filter_input(INPUT_POST, 'email_address', FILTER_SANITIZE_EMAIL);
    } else {
       // If email is empty, set an error message and redirect back to the forgot-password page
      $_SESSION["message"] = "Email is Empty. Please Try again";
      redirect("forgot-password.php");
    }
}

// Handle the POST request when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the action is 'sended', meaning the form to reset password was submitted
    if (isset($_POST['action']) && $_POST['action'] === 'sended') {
        $email = $_SESSION['email_res']; // Get email from session
        $title = "Reset-password"; 
        $time = "Please reset your password within 10 minutes.";

        try {
          // Prepare the SQL statement
          $sql = "SELECT * FROM users WHERE email_address = ?";
          $stmt = $mysqli->prepare($sql);
          
          if (!$stmt) {
            throw new Exception("Statement preparation failed: " . $mysqli->error);
          }
          
          $stmt->bind_param("s", $email);
          $stmt->execute();
          $result = $stmt->get_result();
          
          // If the email exists in the database
          if ($result->num_rows > 0) {
            // Generate a random token for resetting the password
            $token = bin2hex(random_bytes(16));

             // Hash the token for security
            $token_hash = hash("sha256", $token);

             // Set an expiry time for the token (10 minutes from the current time)
            $expiry = date("Y-m-d H:i:s", time() + 60 * 10);

            // Update the user record with the new reset token and expiry time
            $sql = "UPDATE users   
                    SET reset_token_hash = ?, reset_token_expires_at = ?
                    WHERE email_address = ?";
            $stmt = $mysqli->prepare($sql);

            $stmt->bind_param("sss", $token_hash, $expiry, $email);
            $stmt->execute();
            
             // Send the reset email to the user with the token link
            mailer($email, $title, "Password Reset", 
            "To Reset Password <br><br>Click <a href='http://localhost/merchsystem/forgot-password/reset-password.php?token=$token'>here</a> 
            to reset your password.", $time);

            redirect("../forgot-password/send-password-reset.php");

          } else {
             // If no email is found, log an error and display a message to the user
            error_log("Unable to obtain email address: $email"."\n", 3, "../var/log/app_debug.log");
            $_SESSION["message"] = "Something when wrong. Please try again.";
            redirect("forgot-password.php");
          }
        } catch (Exception $e) {
            // Catch any exceptions that occur and log them, then redirect with an error message
            error_log("Error: " . $e->getMessage()."\n", 3, "../var/log/app_debug.log");
            $_SESSION["message"] = "An error occurred. Please try again later.";
            redirect("forgot-password.php");
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Check Your Mailbox</title>

  <link rel="stylesheet" href="../css/input-container.css">
  <link rel="stylesheet" href="../css/forgot-password.css">
  <script src="../javascript/countdown.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

</head>
<body>
   <!-- Main container for the page content -->
  <div class = "container">
    <div class = "comfirmation-container">
      <!-- information container -->
      <div>
        <!-- Display logo -->
        <div class = "logo">
          <img src="../picture/logo.png" style="width: 80px;" alt="Company Logo">
        </div>
        
        <div class = "message-container">
          <p class = "header">Check your inbox, Please!<p>
          <p class = "message">We have sent a password reset email to your account. If the email address provided is correct, you should receive it shortly</p>
        </div>

         <!-- Button to go back to the login page -->
        <div>
          <button class="button" id="back-to-login-page" onclick="window.location.href='../login/login.php';">Sure</button>
        </div>
      </div>

       <!-- Resend button in case the user did not receive the email -->
      <div>
        <p class = 'resend'> Didn't get e-mail? 
          <button type="submit" id="send-email" class="otp-btn" 
          onclick = "startCountdown('Send it again ', 'Resend Email','send-password-reset.php')">
          Send it again
        </button>
        </p> 
      </div>
    </div>
  </div>
</body>
</html>