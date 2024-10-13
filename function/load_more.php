<?php

try {
    include("../database/database.php");
    require_once("../function/function.php");

    // Ensure that the $mysqli connection is set
    if (!isset($mysqli)) {
        throw new Exception("Database connection object not found.");
    }

    // Set the number of items to load at a time
    $limit = 8;

    // Get the offset and categories from POST request
    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
    $categories = isset($_POST['categories']) ? $_POST['categories'] : null;

    // Prepare the SQL query
    if ($categories != null) {
        $sql = "SELECT productName, price, product_Image FROM products WHERE categories = '$categories' LIMIT $limit OFFSET $offset";
    } else {
        $sql = "SELECT productName, price, product_Image FROM products LIMIT $limit OFFSET $offset";
    }

    // Execute the SQL query
    $result = $mysqli->query($sql);

    // Check for SQL execution errors
    if (!$result) {
        throw new Exception("SQL query failed: " . $mysqli->error);
    }

    // Check if rows were returned
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $productName = $row['productName'];
            $price = $row['price'];
            $productImages = $row['product_Image'];
            $imageArray = explode(",", $productImages);
            $firstImage = $imageArray[0];

            echo "<div class='product-item'>";
            echo "<img src='../upload/$firstImage' alt='$productName' width='100'>";
            echo "<h2>$productName</h2>";
            echo "<p>Price: $$price</p>";
            echo "</div>";
        }
    }else{
      echo "";
    }

} catch (Exception $e) {
    error_log("Error: " . $e->getMessage() . "\n", 3, "../var/log/app_debug.log");
    header("Location: ../errorpage/error.html"); // Redirect to the error page
    exit();
}
