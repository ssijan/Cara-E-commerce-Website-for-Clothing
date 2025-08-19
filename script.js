// ✅ NAVBAR TOGGLE
const bar = document.getElementById('bar');
const nav = document.getElementById('navbar');
const close = document.getElementById('close');

if (bar) {
  bar.addEventListener('click', () => nav.classList.add('active'));
}
if (close) {
  close.addEventListener('click', () => nav.classList.remove('active'));
}

// CART FUNCTIONALITY

document.addEventListener("DOMContentLoaded", () => {
  let discountPercent = 0;

  function updateCart() {
    let grandSubtotal = 0;

    // Calculate subtotal for each item
    document.querySelectorAll('#cart tbody tr').forEach(row => {
      const priceCell = row.querySelector('.price');
      const qtyInput = row.querySelector('.cart-qty');
      const subTotalCell = row.querySelector('.sub-total');

      if (priceCell && qtyInput && subTotalCell) {
        const price = parseFloat(priceCell.textContent.replace('$', '')) || 0;
        const qty = parseInt(qtyInput.value) || 1;
        const rowSubtotal = price * qty;
        subTotalCell.textContent = `$${rowSubtotal.toFixed(2)}`;
        grandSubtotal += rowSubtotal;
      }
    });

    let discountAmount = grandSubtotal * (discountPercent / 100);

    // Update Subtotal in summary
    const summarySubtotal = document.querySelector('#cart-total .sub-total');
    if (summarySubtotal) summarySubtotal.textContent = `$${grandSubtotal.toFixed(2)}`;

    // Update Discount row
    const discountLabel = document.querySelector('#cart-total table tr:nth-child(2) td:first-child');
    const summaryDiscount = document.querySelector('#cart-total .discount');
    if (summaryDiscount && discountLabel) {
      discountLabel.textContent = discountPercent > 0 ? `Discount (${discountPercent}%)` : 'Discount';
      summaryDiscount.textContent = `$${discountAmount.toFixed(2)}`;
    }

    // Update Total
    const shipping = 5;
    const summaryTotal = document.querySelector('#cart-total .grand-total');
    if (summaryTotal) {
      const totalPrice = grandSubtotal - discountAmount + shipping;
      summaryTotal.textContent = `$${totalPrice.toFixed(2)}`;
    }
  }

  // AJAX coupon form handler
  const couponForm = document.getElementById('coupon-form');
  if (couponForm) {
    couponForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const couponCode = document.getElementById('coupon').value.trim();
      fetch('apply_coupon.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'coupon=' + encodeURIComponent(couponCode)
      })
      .then(response => response.json())
      .then(data => {
        discountPercent = data.discount_percent || 0;
        updateCart();
        if (discountPercent > 0) {
          showNotification(`🎉 ${discountPercent}% Discount Applied!`);
        } else {
          showNotification(data.error ? data.error : "❌ Invalid Coupon Code");
        }
        // Update discount message in DOM
        const discountMsg = document.querySelector('.apply p.coupon-msg');
        if (discountMsg) {
          discountMsg.textContent = data.error ? data.error : `Coupon applied: ${discountPercent}% off!`;
          discountMsg.style.color = discountPercent > 0 ? 'green' : 'red';
        }
      });
    });
  }

  // Quantity input change listener
  document.querySelectorAll('.cart-qty').forEach(input => {
    input.addEventListener('input', updateCart);
  });

  // Update button handler
  document.querySelectorAll('.update-qty-btn').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      const cartId = this.getAttribute('data-cartid');
      const qtyInput = document.querySelector('.cart-qty[data-cartid="' + cartId + '"]');
      const quantity = qtyInput ? qtyInput.value : 1;
      // Submit quantity update via form
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = 'cart.php';
      form.innerHTML = `<input type="hidden" name="update_qty" value="1"><input type="hidden" name="cart_id" value="${cartId}"><input type="hidden" name="quantity" value="${quantity}">`;
      document.body.appendChild(form);
      form.submit();
    });
  });

  // Remove button
  document.querySelectorAll('.remove a').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      const row = this.closest('tr');
      row.classList.add('fade-out');
      row.addEventListener('transitionend', () => {
        row.remove();
        updateCart();
        showNotification("Item removed from cart!");
      }, { once: true });
    });
  });

  // Run on page load
  updateCart();
});



// ✅ Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    document.querySelector(this.getAttribute('href')).scrollIntoView({
      behavior: 'smooth'
    });
  });
});


// ✅ Smart notification function
function showNotification(message) {
  let notif = document.createElement('div');
  notif.className = 'cart-notification';
  notif.textContent = message;
  document.body.appendChild(notif);

  setTimeout(() => notif.classList.add('show'), 50);
  setTimeout(() => {
    notif.classList.remove('show');
    setTimeout(() => notif.remove(), 300);
  }, 3000);
}

// Override window.alert to use custom notification system
window.alert = function(msg) {
  showNotification(msg);
};

// Global error handler to show errors in notification system
window.onerror = function(message, source, lineno, colno, error) {
  showNotification('Error: ' + message);
  return true; // Prevent default browser error
};


// ✅ Form visibility toggle based on operation selection

function showForm() {

  document.getElementById('add-form').style.display = 'none';
  document.getElementById('update-form').style.display = 'none';
  document.getElementById('delete-form').style.display = 'none';


  var operation = document.getElementById('operation-select').value;

  if (operation === 'add') {
    document.getElementById('add-form').style.display = 'block';
  } else if (operation === 'update') {
    document.getElementById('update-form').style.display = 'block';
  } else if (operation === 'delete') {
    document.getElementById('delete-form').style.display = 'block';
  }
}

// Handle form submission via AJAX
// ✅ Update Product Form Submission (AJAX)
var updateProductForm = document.getElementById('update-product-form');
if (updateProductForm) {
  updateProductForm.addEventListener('submit', function (e) {
    e.preventDefault();
    var productId = document.getElementById('product-id').value;
    var productName = document.getElementById('product-name').value;
    var productDescription = document.getElementById('product-description').value;
    var productPrice = document.getElementById('product-price').value;
    var productStock = document.querySelector('input[name="stock"]:checked').value;
    var productImage = document.getElementById('product-image');

    let formData = new FormData();
    formData.append('id', productId);
    formData.append('name', productName);
    formData.append('description', productDescription);
    formData.append('price', productPrice);
    formData.append('stock', productStock);
    if (productImage && productImage.files[0]) {
      formData.append('image', productImage.files[0]);
    }

    fetch('update_product.php', {
      method: 'POST',
      body: formData
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Product updated successfully!');
          updateProductForm.reset();
          showForm();
        } else {
          alert('Error updating product!');
        }
      })
      .catch(error => console.error('Error:', error));
  });
}

// ✅ Delete Product Form Submission (AJAX)
var deleteProductForm = document.getElementById('delete-product-form');
if (deleteProductForm) {
  deleteProductForm.addEventListener('submit', function (e) {
    e.preventDefault();
    var productId = document.getElementById('delete-product-id').value;
    let formData = new FormData();
    formData.append('id', productId);

    fetch('delete_product.php', {
      method: 'POST',
      body: formData
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Product deleted successfully!');
          deleteProductForm.reset();
          showForm();
        } else {
          alert('Error deleting product!');
        }
      })
      .catch(error => console.error('Error:', error));
  });
}
// ✅ Add Product Form Submission (AJAX)
var addProductForm = document.getElementById('add-product-form');
if (addProductForm) {
  addProductForm.addEventListener('submit', function (e) {
    e.preventDefault();
    console.log('Form submitted!');

    let formData = new FormData();
    var productImage = document.getElementById('product-image');
    var productName = document.getElementById('product-name');
    var productDescription = document.getElementById('product-description');
    var productPrice = document.getElementById('product-price');
    var productStock = document.querySelector('input[name="stock"]:checked');

    if (productImage && productImage.files[0]) {
      formData.append('image', productImage.files[0]);
    }
    if (productName) {
      formData.append('name', productName.value);
    }
    if (productDescription) {
      formData.append('description', productDescription.value);
    }
    if (productPrice) {
      formData.append('price', productPrice.value);
    }
    if (productStock) {
      formData.append('stock', productStock.value);
    }

    fetch('upload_image.php', {
      method: 'POST',
      body: formData
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Product added successfully!');
          // Optionally, reset the form or hide it
          addProductForm.reset();
          showForm(); // Hide form after successful submission
        } else {
          alert('Error uploading image!');
        }
      })
      .catch(error => console.error('Error:', error));
  });
}


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
  const productsContainer = document.querySelector('.pro-container');  // The container where products will be displayed
  productsContainer.innerHTML = '';  // Clear any existing content

  // Loop through each product and create HTML for it
  products.forEach(product => {
    const productElement = document.createElement('div');
    productElement.classList.add('pro');

    // Make the whole product card clickable
    productElement.style.cursor = 'pointer';
    productElement.addEventListener('click', function(e) {
      // Prevent cart icon click from triggering navigation
      if (e.target.classList.contains('cart')) return;
      window.location.href = `products.php?id=${product.id}`;
    });

    productElement.innerHTML = `
      <img src="${product.image_url}" alt="${product.name}">
      <div class="des">
        <div class="product-info">
          <span>Cara</span>
          <span>Product ID: ${product.id}</span>
        </div>
        <h5>${product.name}</h5>
        <div class="star">
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
        </div>
        <h4>$${parseFloat(product.price).toFixed(2)}</h4>
      </div>
      <a href="#" onclick="event.stopPropagation();"><i class="fas fa-shopping-cart cart"></i></a>
    `;

    productsContainer.appendChild(productElement);
  });
}

// Run the loadProducts function when the page loads
document.addEventListener('DOMContentLoaded', loadProducts);

