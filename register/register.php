<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>

  <link rel="stylesheet" href="../css/register.css">
  <link rel="stylesheet" href="../css/input-container.css">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script src="../javascript/validation.js" defer></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
  
</head>
<body>
  <div class = "container">
    <div class="register-container">
      <h1>Register</h1>
      <form action="process-register.php" method="post">
        <!-- Username input field -->
        <div class="input-container">
          <input type="text" placeholder=" " name="username" id="username" class="input">
          <label for="username">Username</label>        
        </div>

        <!-- Email input field -->
        <div class="input-container">
          <input type="email" placeholder=" " name="email_address" id="email_address" class="input">
          <label for="email_address">Email</label>
        </div>

        <!-- Password input field -->
        <div class="input-container">
          <input type="password" placeholder=" " name="password" id="password" class="input">
          <label for="password">Password</label>
        </div>

        <!-- Password requirement block -->
        <div class="content" id="password-requirements" style="display: none;">
          <ul class="requirement-list">
            <li>
              <i class="fa-solid fa-circle"></i>
              <span>At least 8 characters length</span>
            </li>
            <li>
              <i class="fa-solid fa-circle"></i>
              <span>At least 1 number (0...9)</span>
            </li>
            <li>
              <i class="fa-solid fa-circle"></i>
              <span>At least 1 lowercase letter (a...z)</span>
            </li>
            <li>
              <i class="fa-solid fa-circle"></i>
              <span>At least 1 special symbol (!...$)</span>
            </li>
            <li>
              <i class="fa-solid fa-circle"></i>
              <span>At least 1 uppercase letter (A...Z)</span>
            </li>
        </ul>
        </div>

        <!-- Confirm Password input field -->
        <div class="input-container">
          <input type="password" placeholder=" " name="password_confirmation" id="password_confirmation" class="input">
          <label for="password_confirmation">Confirm Password</label>
        </div>

        <!-- Password confirmation block -->
        <div class="content-2" id="password-requirements-2" style="display: none;">
          <ul class="requirement-list-2">
            <li>
              <i class="fa-solid fa-circle"></i>
              <span id="error">Passwords do not match</span>
            </li>
          </ul>
        </div>
       
        <!-- Display Error message if any -->
        <div class = "error-message">
          <?php
            if (!empty($_SESSION['error'])) {
              echo "<p>{$_SESSION['error']}</p>";
              unset($_SESSION['error']); // Clear the error after displaying it
            }
          ?>
        </div>

        <!-- reCAPTCHA -->
        <div class = "recaptcha"> 
          <div class="g-recaptcha" data-sitekey="6LfAl0UqAAAAAJG3c7wwQKGFkU7eCUhWvkMGnHOL"
            data-callback="enableSubmitbtn">
          </div>
        </div>

        <!-- Submit button -->
        <div>
          <button type="submit" class="submit-btn" id="submit_btn" disabled="disabled">Register</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>