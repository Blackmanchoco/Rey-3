<?php

  session_start();

  include("../database/database.php");
  require_once("../function/password-validate.php");

  // Initialize a variable to check if the form is valid
  $isValid = true;

  // Retrieve and sanitize the user input
  $username = filter_input(INPUT_POST,'username');
  $email = filter_input(INPUT_POST, 'email_address', FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);  
  $confirmPassword = filter_input(INPUT_POST, 'password_confirmation', FILTER_SANITIZE_SPECIAL_CHARS); 
   
  // Check if the username is empty
  if(empty($username)){
    $isValid = false;  
  }

  //Check if the email is empty
  if (empty($email)) {
    $isValid = false; 
  }



  // End user input Validation 
  $isValid = passwordValidate($password, $confirmPassword);

  // If any validation fails, set an error message and redirect to the registration page
  if(!$isValid){
    $_SESSION['error'] = "Something went wrong1. Please1 try again.";
    redirect('register.php');
  }

  try {
    // Prepare an SQL query to check if the email already exists in the database
    $sql = 'SELECT * FROM users WHERE email_address = ?';
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // If the email already exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // If the email is already verified, redirect to the login page with an error message
        if ($row['is_verified'] == 1) {
          $_SESSION['error'] = "Email is already registered. Please log in.";
          redirect('../login/login.php');

        }else{
          
          $update_sql = "UPDATE users SET username = ?, password_hash = ? WHERE email_address = ?";
          $update_stmt = $mysqli->prepare($update_sql);
          $update_stmt->bind_param("sss", $username, $password_hash, $email);

          // Execute the update statement
          if ($update_stmt->execute()) {
            $_SESSION['email_reg'] = $email;
            redirect('otp-verify.php?msg=Please verify your email to complete registration.');
          }
        }
    } else {

        
        $otp = null;  
        $otp_generated_at = null;
        $sql = "INSERT INTO users (username, email_address, password_hash, otp, otp_generated_at, is_verified)
                VALUES (?, ?, ?, ?, ?, 0)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sssss", $username, $email, $password_hash, $otp, $otp_generated_at);

        if ($stmt->execute()) {
          $_SESSION['email_reg'] = $email;
          redirect('otp-verify.php?msg=Please verify your email to complete registration.');
          exit();
        }   
    }
  }catch (Exception $e) {
     // Log the error if any exception occurs and redirect to the registration page with an error message
    error_log("Registration Error: " . $e->getMessage() . "\n", 3, "../var/log/app_debug.log");
    $_SESSION['error'] = "Something went wrong2. Please try again.";
    redirect('register.php');
  }