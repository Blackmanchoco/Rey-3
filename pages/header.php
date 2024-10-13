<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Website</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/header.css">
</head>
<body>
    <header>
    <div class="container">
        <div class="logo">
            <a href="index.php">
                <img src="../picture/logo.png" alt="Logo" class="logo-pic">
            </a>
        </div>
        <nav>
            <ul class="option">
                <li><a href="index.php">Shop All</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="services.php">Contact Us</a></li>
            </ul>
        </nav>
        <div class="fa-container">
            <form class="search-form" action="action_page.php">
                <input type="text" placeholder="Search.." name="search" class="search-input">
                <button type="submit" class="search-btn"><i class="fa fa-search"></i></button>
            </form>
        </div>
        <ul class="right-options">
            <li><a href="services.php">Sign in</a></li>
            <li>
                <a href="action_page.php">
                    <i style="font-size:24px" class="fa">&#xf290;</i>
                </a>
            </li>
        </ul>
    </div>
</header>


    

</body>
</html>
