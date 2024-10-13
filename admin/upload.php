<?php
session_start();
include("../database/database.php");
require_once("../function/function.php");

$error = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit'])) {
        // Sanitize and validate inputs
        $stock = null;
        $selection = false;
        $sizeM = $sizeX = $sizeXL = null;
        $isEmpty = false;

        $productName = filter_input(INPUT_POST, 'product-name', FILTER_SANITIZE_STRING);
        $productPrice = filter_input(INPUT_POST, 'product-price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $description = filter_input(INPUT_POST, 'product-description', FILTER_SANITIZE_STRING);
        $categories = filter_input(INPUT_POST, 'dropdown', FILTER_SANITIZE_STRING);


        if(empty($productName)){
          $isEmpty = true;
          $error[] = 'Warning: Please Enter Product Name.';   
        }
        
        if(empty($productPrice)){
          $isEmpty = true;
          $error[] = 'Warning: Please Enter Product Price.';   
        }

        if(empty($description)){
          $isEmpty = true;
          $error[] = 'Warning: Please Enter Product Description.'; 
        }
        
        if(empty($categories)){
          $isEmpty = true;
          $error[] = 'Warning: Please Select Product Categories.'; 
        }

        // Check category and handle stock or size based on category
        if ($categories !== 'Apparel') {
            $stock = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT);
            
            if(empty($stock)){
              $isEmpty = true;
              $error[] = 'Warning: Please Enter Stock Quantity.';
            }

        } else {
            $sizeM = filter_input(INPUT_POST, 'size-m', FILTER_SANITIZE_NUMBER_INT);
            $sizeX = filter_input(INPUT_POST, 'size-x', FILTER_SANITIZE_NUMBER_INT);
            $sizeXL = filter_input(INPUT_POST, 'size-xl', FILTER_SANITIZE_NUMBER_INT);
            
            if(empty($sizeM)){
              $isEmpty = true;
              $error[] = 'Warning: Please Enter Stock Quantity (Size M).';
            }
            
            if(empty($sizeX)){
              $isEmpty = true;
              $error[] = 'Warning: Please Enter Stock Quantity (Size X).';
            }
            
            if(empty($sizeXL)){
              $isEmpty = true;
              $error[] = 'Warning: Please Enter Stock Quantity (Size XL).';
            }
        }

        if($isEmpty){
          $_SESSION['message'] = $error;
          foreach($error as $error_message){
            error_log("Empty Input: " . $error_message . "\n", 3, "../var/log/app_errors.log");
          }
          redirect("upload.php");
          exit();
        }

        // Process multiple image uploads
        $totalFiles = count($_FILES['image']['name']);
        $filesArray = array();
        for ($i = 0; $i < $totalFiles; $i++) {
            if ($_FILES['image']['error'][$i] === 0) {
                $imageName = $_FILES['image']['name'][$i];
                $tmpName = $_FILES['image']['tmp_name'][$i];

                $imageExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
                $newImageName = uniqid() . '.' . $imageExtension;

                $uploadPath = "../upload/" . $newImageName;
                if (move_uploaded_file($tmpName, $uploadPath)) {
                    $filesArray[] = $newImageName;
                } else {
                    $_SESSION['message'] = ["Error uploading one or more image files."];
                    error_log("Error uploading one or more image files.\n", 3, "../var/log/app_errors.log");
                    break;
                }
            } else {
                $_SESSION['message'] = ["No image file uploaded or an error occurred."];
                error_log("No image file uploaded or an error occurred.\n", 3, "../var/log/app_errors.log");
                break;
            }
        }

        if (!empty($filesArray)) {
            // Convert files array to a comma-separated string to store in the database
            $images = implode(",", $filesArray);

            // Insert product details into the database
            $sql = "INSERT INTO products 
                    (productName, categories, price, product_Image, stock, size_M, size_X, size_XL, product_description)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            try {
                $stmt = $mysqli->prepare($sql);
                if (!$stmt) {
                    throw new Exception("Prepare failed: " . $mysqli->error);
                }

                // Bind parameters
                $stmt->bind_param("ssssiiiis", $productName, $categories, $productPrice, $images, $stock, $sizeM, $sizeX, $sizeXL, $description);

                // Execute
                if ($stmt->execute()) {
                    $_SESSION['message'] = ["Product and images uploaded successfully!"];
                    redirect("upload.php");
                    exit();
                } else {
                    throw new Exception("Error adding product: " . $stmt->error);
                }
            } catch (Exception $e) {
                $_SESSION['message'] = [$e->getMessage()];
                error_log($e->getMessage() . "\n", 3, "../var/log/app_errors.log");
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../css/admin-upload.css">
  
</head>
<body>
  <div class="container">
    <div>
      <div class="title">
        <h2>Upload</h2>
      </div>
      
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
        <div>
          <div>
            <label>Product Name</label><br>
            <input type="text" class="item-details" name="product-name">
          </div>

          <div>
            <label>Price</label><br>
            <input type="text" class="item-details" name="product-price">
          </div>

          <div>
          <label for="dropdown" id ="categories">Product Categories</label><br>
          <select id="dropdown" name="dropdown" class="categories">
              <option value="" disabled selected>Select categories</option>
              <option value="Apparel">Apparel</option>
              <option value="Plush">Plush</option>
              <option value="Accessory">Accessory</option>
          </select>
      </div>  

          <div class="hidden" style="display: none;">
            <div>
              <label for="numberInputM">Stock Size-M</label><br>
              <input type="number" id="numberInputM" name="size-m" min="0" class="item-details">
            </div>

            <div>
              <label for="numberInputX">Stock Size-X</label><br>
              <input type="number" id="numberInputX" name="size-x" min="0" class="item-details">
            </div>

            <div>
              <label for="numberInputXL">Stock Size-XL</label><br>
              <input type="number" id="numberInputXL" name="size-xl" min="0" class="item-details">
            </div>
          </div>

          <div class="stock" style="display: none;">
            <div>
              <label for="numberInput">Stock</label><br>
              <input type="number" id="numberInput" name="stock" min="0" class="item-details">
            </div>
          </div>


          <div>
            <label>Description</label><br>
            <textarea class="description" name="product-description" rows="4" cols="50"></textarea>
          </div>

          <div class="image-container">
            <label for="input-file" id="drop-area">
              <input type="file" accept="image/*" id="input-file" name="image[]" hidden required multiple>
              <div id="img-view">
                <img src="../picture/upload.png" alt="Upload Icon" class="upload-pic">
                <p>Drag and Drop or click here<br>to upload images</p>
                <span>Upload multiple images</span>
              </div>
            </label>
          </div>

          <div>
            <p>Note: First Image Will be the Product Cover Photo</p>
          </div>

          <div>
            <?php
              if(!empty($_SESSION['message'])){
                foreach($_SESSION['message'] as $message){
                  echo "<p>{$message}</p>";
                }
                $_SESSION['message'] = '';
              }
            ?>
          </div>

          <div class = "submit-container">
            <input type="submit" name="submit" class="submit-btn" value="Submit">
          </div>

        </div>
      </form>
    </div>
  </div>
  <script src="../javascript/upload-image.js"></script>
  <script src="../javascript/selectproduct.js"></script>            
  
</body>
</html>