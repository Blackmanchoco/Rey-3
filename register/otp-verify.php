<?php

  session_start();

  include("../database/database.php");
  include("../phpMailer/mailer.php");
  require_once("../function/function.php");

   // Retrieve the email from the session (Take from 'process-register.php')
  $email = $_SESSION['email_reg'];
  
  // If the email is not set in the session, log an error and redirect to the registration page
  if(!isset($email)){
    $_SESSION['error'] = "Something went wrong. Please try again.";
    error_log("Connection failed: " ."Unable to catch Email Address" . "\n", 3, "../var/log/app_errors.log");
    redirect("register.php");
  }

  // Check if the request method is POST
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Check if the action is to send an OTP (Get from countdown.js)
    if (isset($_POST['action']) && $_POST['action'] === 'sended') {
      
      // Generate a 6-digit OTP
      $otp = rand(100000, 999999);

      // Get the current timestamp for OTP generation time
      $otp_generated_at = date('Y-m-d H:i:s');

      $title = "Verify your new account";
      $time = "Please enter the OTP within 10 minutes.";
      // Send the OTP to the user via email
      mailer($email, $title,"OTP for Registration", "Your OTP Code is <br><br> " . $otp,$time);

      // Update OTP and timestamp in the database
      $sql = "UPDATE users SET otp = ?, otp_generated_at = ? WHERE email_address = ?";
      $stmt = $mysqli->prepare($sql);
      $stmt->bind_param("sss", $otp, $otp_generated_at, $email);
      $stmt->execute();

      redirect("otp-verify.php");
    }

    if(isset($_POST['action']) && $_POST['action'] === 'verify_otp'){
      $entered_otp = $_POST['otp'] ?? '';

      // Retrieve OTP and timestamp from the database
      $sql = "SELECT otp, otp_generated_at FROM users WHERE email_address = ?";
      $stmt = $mysqli->prepare($sql);
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $stored_otp = $row['otp'];
        $otp_generated_at = strtotime($row['otp_generated_at']);
        $current_time = time();

        // Check if OTP is valid (e.g., within 10 minutes)
        if ($current_time - $otp_generated_at > 600) {
            $_SESSION['msg'] = "OTP has expired. Please request a new one.";
        } elseif ($entered_otp == $stored_otp) {
          // OTP is correct, verify the user
          $sql = "UPDATE users SET is_verified = 1, otp = NULL, otp_generated_at = NULL WHERE email_address = ?";
          $stmt = $mysqli->prepare($sql);
          $stmt->bind_param("s", $email);
          $stmt->execute();
          unset($_SESSION['email_reg']);
          redirect("../login/login.php?msg=Your email address has been successfully registered.");
        } else {
          $_SESSION['msg'] = "Invalid OTP. Please try again.";
        }
      } else {
          $_SESSION['msg'] = "User not found.";
      }
    }
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OTP Verification</title>
  
  <link rel="stylesheet" href="../css/input-container.css">
  <link rel="stylesheet" href="../css/otp-verify.css">
  <script src="../javascript/countdown.js"></script>
</head>

<body>
  <div class="container">
    <div class="verify-container">

      <div class = "logo">
        <img src="../picture/logo.png" style="width: 80px;" alt="Company Logo">
      </div>

      <div>
        <p class="title">Please Enter the OTP to Verify your Account</p>
      </div>

      <div>
        <form action= '<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>' method= 'post'>
          <input type="hidden" name="action" value="verify_otp">
          
            <!-- OTP input field -->
          <div class="input-container">
            <input type="text" placeholder=" " name="otp" id="otp" class="input" >
            <label for="otp">Enter OTP</label>
          </div>

          <!-- Error Message block -->
          <div class="message" role="alert" id="otp-message">
            <?php
            if (!empty($_SESSION['msg'])) {
              echo htmlspecialchars($_SESSION['msg']);
              unset($_SESSION['msg']);
            }
            ?>
          </div>
        
          <!-- Verify button -->
          <div class="submitAndOTP">
            <div class="verify">
              <button type="submit" class="submit-btn">Verify</button>
            </div>
          </div>
        </form>
      </div>

      <!-- Send OTP button -->
      <div class="otp">    
          <button type="submit" id="send-email" class="otp-btn" onclick = "startCountdown('Send dTP', 'Resend OTP','otp-verify.php')">Send OTP</button>
      </div>

    </div>
  </div>
</body>
</html>