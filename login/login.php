<?php

  include("../database/database.php");
  session_start();

  $error_message = ""; // Initialize error message as an empty string

  // Check if the form was submitted using the POST method
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Check if the reCAPTCHA response is set and not empty
    if (isset($_POST["g-recaptcha-response"]) && !empty($_POST["g-recaptcha-response"])) {
        
      // Secret key for reCAPTCHA
      $secretKey = "6LfAl0UqAAAAAAN-xWgbIAtqSRJ00VZ1Bq6_Ik55";

        // Make a request to Google's reCAPTCHA API to verify the response
      $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . 
                                          $secretKey . '&response=' . $_POST["g-recaptcha-response"]);
      
      // Decode the JSON response from the reCAPTCHA API
      $response = json_decode($verifyResponse);
        
        // Check if the reCAPTCHA verification was successful
      if ($response->success) {

        // Prepare an SQL statement to select the user based on email or username
        $sql = "SELECT * FROM users WHERE (email_address = ? OR username = ?) AND is_verified = 1";
        
        $emailOrUsername = filter_input(INPUT_POST, 'email_or_username', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS); 
        

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $emailOrUsername , $emailOrUsername );

        // Execute the prepared statement
        $stmt->execute();
        
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        // Check if a user was found
        if ($user) {

          $password_hash = $user["password_hash"];
          
          if (password_verify($password, $password_hash)) {
            session_regenerate_id(true); 
            
            $_SESSION["user_id"] = $user["id"]; // Store the user's ID in the session
            
            redirect("../pages/index.php");  // Redirect the user to the homepage
          } else {
            $error_message = "Your username or password may be incorrect.";
          }
      }else{
        $error_message = "Your username or password may be incorrect.";
      }

    }else{
      $error_message = "reCAPTCHA verification failed. Please try again.";
    }

  }else{
    $error_message = "Please complete the reCAPTCHA.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>

  <link rel="stylesheet" href="../css/login.css">
  <link rel="stylesheet" href="../css/input-container.css">
  <script src="../javascript/scripts.js"></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body>
  <div class="container">
    <div class = "login_container">
      <div class = "message">
        <h1 class = "login-message">Login</h1>
      </div>

      <div>
        <!-- Login form -->
        <form  action ='<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>' method="post">
          <!-- Email or Username input field -->
          <div class="input-container">
            <input type="text" placeholder=" " name="email_or_username" id="email_or_username" class="input">
            <label for="email_or_username">Email or Username</label>
          </div>

          <!-- Password input field -->
          <div class="input-container">
            <input type="password" placeholder=" " name="password" id="password" class="input">
            <label for="password">Password</label>
          </div>

          <!-- Display the error message (if any) -->
          <div class = "message">
            <?php 
            if (!empty($error_message))
              echo "<P>$error_message</p>";
            ?>
          </div>

          <!-- Display the error message (if any) -->
          <div class = "message">
            <?php 
            if (isset($_GET['msg'])) {
              $message = urldecode($_GET['msg']);
              echo $message;
            }

            if(!empty($_SESSION['error'])){
              echo "<p>".$_SESSION['error']."</p>";
              unset($_SESSION['error']);
            }
            ?>
          </div>

          <!-- Recaptcha -->
          <div class = "recaptcha"> 
            <div class="g-recaptcha" data-sitekey="6LfAl0UqAAAAAJG3c7wwQKGFkU7eCUhWvkMGnHOL"
              data-callback="enableSubmitbtn">
            </div>
          </div>

          <!-- Login button -->
          <div class = "submit">
            <button type="submit" class="submit-btn" id="submit_btn" disabled="disabled">Login</button>
          </div>

        </form>
      </div>  
      
      <!-- login register container -->
      <div class = "forgotAndSignup-container">
        <div class = "forgot-container">
          <a href="../forgot-password/forgot-password.php" class="forgot_password">Forgotten your password?</a>
        </div>
        <div>
          <a  href="../register/register.php" class="register">Create an account</a>
        </div>
      </div>
    </div>
  </div>
  
</body>
</html>