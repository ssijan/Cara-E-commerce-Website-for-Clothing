// Function to load products from the server (PHP)
function loadProducts() {
  fetch('get_products.php')  // Fetch data from the PHP backend
    .then(response => response.json())  // Parse the JSON response
    .then(data => {
      if (data.success) {
        displayProducts(data.products);  // Call displayProducts if data is valid
      } else {
        console.error('Error fetching products:', data.error);
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
}

// Function to display products dynamically on the page
function displayProducts(products) {
  const productsContainer = document.querySelector('.pro-cont');  // The container where products will be displayed
  productsContainer.innerHTML = '';  // Clear any existing content

  // Loop through each product and create HTML for it
  products.forEach(product => {
    const productElement = document.createElement('div');
    productElement.classList.add('test');  // Add a class to each product element

    // Populate the product HTML with data
    productElement.innerHTML = `
      <img src="${product.image_url}" alt="${product.name}">
      <div class="des">
        <div class="product-info">
          <span>Cara</span>
          <span>Product ID: ${product.id}</span>
        </div>
        <h5>${product.name}</h5>
        <div class="star">
          <!-- Static stars for simplicity, you can adjust based on product rating -->
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
        </div>
        <h4>$${parseFloat(product.price).toFixed(2)}</h4>
      </div>
      <a href="#"><i class="fas fa-shopping-cart cart"></i></a>
    `;

    // Append the product to the container
    productsContainer.appendChild(productElement);
  });
}

// Run the loadProducts function when the page loads
document.addEventListener('DOMContentLoaded', loadProducts);
