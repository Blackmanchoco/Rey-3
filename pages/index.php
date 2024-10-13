<?php

  session_start();
  include ("header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/index.css">
  <title>Document</title>
</head>
<body>
  <div class = "container">
    <div class = "new-item">
      <div>
        <img src = "../picture/new-item.png" class = "new-item-picture">
        <div class="text-overlay">
        <h2 class = "title">Prison Realm</h2>
        <h3 class = "description">There's A  Jujutsu Kaisen x CASETiFY Collection And It Looks Amazing</h3>
        <button class = "btn">SHOP NOW</button>
      </div>
      </div>
    </div>
  </div>
 
  <div class = "container-2">   
    <div class = "this-month-pick" >
      <h2>This Month's Pick</h2>
    </div>
  </div>

  <div class = "container-3">
    <div class = "first-item">
        <div>
          <img src = "../picture/ahri.png" class = "first-item-pic">
        </div>
        <div class = "des">
          <p>Worlds 2023 T1 Ahri Fox Plush</p>
          <p>$34.99</p>
        </div>
    </div>

    <div class = "container-4">
      <div>
        <div class = "container-4-1">
          <img src = "../picture/poggles.png" class=" product">
          <div class = "des-2">
            <p>Poggles Plush</p>
            <p>$29.99</p>
          </div>
        </div>
        <div class = "container-4-2">
          <img src = "../picture/poro.png" class=" product">
          <div class = "des-2">
            <p>Poro Plush</p>
            <p>$24.99</p>
          </div>
        </div>
      </div>

      <div>
        <div class = "container-4-1">
          <img src = "../picture/wingman.png" class=" product">
          <div class = "des-2">
            <p>Wingman Plush</p>
            <p>$34.99</p>
          </div>
        </div>
        <div class = "container-4-2">
          <img src = "../picture/penguin.png" class=" product">
          <div class = "des-2">
            <p>Penguin Cushion</p>
            <p>$34.99</p>
          </div>
        </div>
      </div>


    </div>

  </div>

  <div class = "ads-1">
    <img src = "../picture/champion.png" class = "ads-1-pic">
  </div>

  <div class = "ads-2">
    <img src = "../picture/pin.png" class = "ads-2-pic">
  </div>

</body>
</html>


<?php

include ("footer.php");
?>