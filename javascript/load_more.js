document.addEventListener("DOMContentLoaded",function(){

  let offset = 0; // Number of products loaded
  const limit = 8; // Number of products to load at a time

  const loadMoreBtn = document.getElementById("load-more-btn");
  const productContainer = document.getElementById("product-container");
  let categories = null;

  // Set a timeout to fix the issue with the button appearing first
  setTimeout(() => {
    loadMoreBtn.style.display = "inline";
  }, 100);

  // Function to load product
  function loadProducts(){
    const xhr = new XMLHttpRequest(); // To Perform an AJAX request.

    xhr.open("POST","../function/load_more.php",true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function(){
      //Check if the request has been completed
      if (xhr.readyState === 4 && xhr.status === 200){ 
        const response = xhr.responseText;

        if(response.trim() === ""){  // Check if empty
          loadMoreBtn.style.display = "none"; // No more product, and hidden the button
        }else{
          productContainer.innerHTML += response; // Append the newly loaded product to the itemsContainer
          offset += limit; // Update the offset variable
        }

      }
    };
    
    // Prepare the data to send
    let params = "offset=" + offset + "&limit=" + limit;
    if (categories) {
        params += "&categories=" + encodeURIComponent(categories);
    }

    xhr.send(params);

  }

  // Load initial items
  loadProducts();

  // Event listener for the Load More button
  loadMoreBtn.addEventListener("click", loadProducts);

});