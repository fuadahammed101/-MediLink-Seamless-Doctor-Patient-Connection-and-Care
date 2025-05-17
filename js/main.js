let slideIndex = 0;
showSlides();

function showSlides() {
  let i;
  let slides = document.getElementsByClassName("slideshow-slide");
  if (!slides.length) return;
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  slideIndex++;
  if (slideIndex > slides.length) { slideIndex = 1 }
  slides[slideIndex - 1].style.display = "block";
  setTimeout(showSlides, 4000); // Change image every 4 seconds
}

if (document.querySelector('.prev')) {
  document.querySelector('.prev').addEventListener('click', function () {
    plusSlides(-1);
  });
}
if (document.querySelector('.next')) {
  document.querySelector('.next').addEventListener('click', function () {
    plusSlides(1);
  });
}

function plusSlides(n) {
  slideIndex += n - 1;
  let slides = document.getElementsByClassName("slideshow-slide");
  if (!slides.length) return;
  if (slideIndex < 0) {
    slideIndex = slides.length - 1;
  }
  showSlides();
}

function showQuantityModal(productName, callback) {
  // Remove existing modal if any
  const oldModal = document.getElementById('quantity-modal');
  if (oldModal) oldModal.remove();

  const modal = document.createElement('div');
  modal.id = 'quantity-modal';
  modal.style.position = 'fixed';
  modal.style.top = '0';
  modal.style.left = '0';
  modal.style.width = '100vw';
  modal.style.height = '100vh';
  modal.style.background = 'rgba(0,0,0,0.35)';
  modal.style.display = 'flex';
  modal.style.alignItems = 'center';
  modal.style.justifyContent = 'center';
  modal.style.zIndex = '9999';

  modal.innerHTML = `
    <div style="background:#fff;padding:2rem 2.5rem;border-radius:16px;max-width:350px;width:90%;box-shadow:0 8px 32px rgba(44,62,80,0.18);text-align:center;">
      <div style="font-size:1.1rem;color:#2e7d32;margin-bottom:1.2rem;">How many <b>${productName}</b> do you want to buy?</div>
      <input type="number" id="quantity-input" min="1" value="1" style="width:80px;padding:0.5rem;font-size:1.1rem;margin-bottom:1.2rem;border-radius:5px;border:1px solid #ccc;">
      <br>
      <button id="ok-quantity-btn" style="padding:0.5rem 1.2rem;background:#43a047;color:#fff;border:none;border-radius:6px;font-size:1em;cursor:pointer;margin-right:1rem;">OK</button>
      <button id="cancel-quantity-btn" style="padding:0.5rem 1.2rem;background:#e53935;color:#fff;border:none;border-radius:6px;font-size:1em;cursor:pointer;">Cancel</button>
    </div>
  `;
  document.body.appendChild(modal);

  document.getElementById('ok-quantity-btn').onclick = function () {
    const val = parseInt(document.getElementById('quantity-input').value, 10);
    if (isNaN(val) || val < 1) {
      document.getElementById('quantity-input').style.border = '1px solid #e53935';
      document.getElementById('quantity-input').focus();
      return;
    }
    modal.remove();
    callback(val);
  };
  document.getElementById('cancel-quantity-btn').onclick = function () {
    modal.remove();
  };
}

function showModal(message) {
  // Remove existing modal if any
  const oldModal = document.getElementById('cart-modal');
  if (oldModal) oldModal.remove();

  const modal = document.createElement('div');
  modal.id = 'cart-modal';
  modal.style.position = 'fixed';
  modal.style.top = '0';
  modal.style.left = '0';
  modal.style.width = '100vw';
  modal.style.height = '100vh';
  modal.style.background = 'rgba(0,0,0,0.35)';
  modal.style.display = 'flex';
  modal.style.alignItems = 'center';
  modal.style.justifyContent = 'center';
  modal.style.zIndex = '9999';

  modal.innerHTML = `
    <div style="background:#fff;padding:2rem 2.5rem;border-radius:16px;max-width:350px;width:90%;box-shadow:0 8px 32px rgba(44,62,80,0.18);text-align:center;">
      <div style="font-size:2.5rem;color:#43a047;margin-bottom:0.7rem;">&#10003;</div>
      <div style="font-size:1.1rem;color:#2e7d32;margin-bottom:1.2rem;">${message}</div>
      <button id="close-cart-modal-btn" style="padding:0.6rem 1.5rem;background:#43a047;color:#fff;border:none;border-radius:6px;font-size:1em;cursor:pointer;">OK</button>
    </div>
  `;
  document.body.appendChild(modal);

  document.getElementById('close-cart-modal-btn').onclick = function () {
    modal.remove();
  };
}

document.addEventListener('DOMContentLoaded', function () {
  // BUY BUTTON LOGIC
  const buyButtons = document.querySelectorAll('.product-item button');
  buyButtons.forEach(function (button) {
    button.addEventListener('click', function () {
      const productDiv = this.closest('.product-item');
      const productName = productDiv.querySelector('h3').textContent;
      let priceText = productDiv.querySelector('p') ? productDiv.querySelector('p').textContent : '';
      let priceMatch = priceText.match(/BDT\s*([\d.]+)/i);
      let productPrice = priceMatch ? parseFloat(priceMatch[1]) : 0;
      const productImg = productDiv.querySelector('img') ? productDiv.querySelector('img').getAttribute('src') : '';

      showQuantityModal(productName, function (quantity) {
        // Check if user is logged in
        var userIsLoggedIn = typeof window.userIsLoggedIn !== "undefined" ? window.userIsLoggedIn : false;

        if (userIsLoggedIn) {
          // Send to server if logged in
          const item = {
            name: productName,
            quantity: quantity,
            price: productPrice,
            img: productImg
          };

          fetch('cart_api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'add', item: item })
          })
            .then(res => res.json())
            .then(data => {
              if (data.success) {
                updateCartCount();
                showModal(`${productName} (x${quantity}) added to cart!`);
              } else {
                showModal('Failed to add item to cart: ' + (data.error || 'Unknown error'));
              }
            })
            .catch(error => {
              console.error('Error:', error);
              showModal('Network error. Could not add to cart.');
            });
        } else {
          // Store in localStorage if not logged in
          let cart = JSON.parse(localStorage.getItem('cart')) || [];
          const now = Date.now();
          const existing = cart.find(item => item.name === productName);
          if (existing) {
            existing.quantity += quantity;
            existing.time = now;
            existing.price = productPrice;
            existing.img = productImg;
          } else {
            cart.push({ name: productName, quantity: quantity, price: productPrice, time: now, img: productImg });
          }
          localStorage.setItem('cart', JSON.stringify(cart));
          // Update cart count
          updateCartCount();
          showModal(`${productName} (x${quantity}) added to cart!`);
        }
      });
    });
  });

  // BUY SELECTED BUTTON LOGIC
  if (document.getElementById('buy-selected-btn')) {
    document.getElementById('buy-selected-btn').addEventListener('click', function () {
      let cart = JSON.parse(localStorage.getItem('cart')) || [];
      const selected = Array.from(document.querySelectorAll('.select-item:checked'))
        .map(cb => cart[parseInt(cb.getAttribute('data-idx'), 10)]);
      if (selected.length === 0) {
        showModal('Please select at least one item to buy.');
        return;
      }
      const totalAmount = selected.reduce((sum, item) => sum + (item.price * item.quantity), 0);
      showPurchaseSummary(selected, totalAmount);
    });
  }

  // Appointment form submission handler
  if (document.getElementById('appointmentForm')) {
    document.getElementById('appointmentForm').addEventListener('submit', function (e) {
      e.preventDefault();

      // Get form data
      const formData = new FormData(this);

      // Add any missing required fields
      if (!formData.has('doctor_name')) {
        formData.append('doctor_name', 'Not Specified');
      }

      if (!formData.has('category')) {
        formData.append('category', 'General');
      }

      // Submit to the server
      fetch('book_appointment.php', {
        method: 'POST',
        body: formData
      })
        .then(response => response.text())
        .then(data => {
          if (data.trim() === 'success') {
            showModal('Appointment booked successfully!');
            this.reset();
          } else {
            showModal('Error: ' + data);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showModal('Network error. Please try again later.');
        });
    });
  }
  updateCartCount();
});

function showPurchaseSummary(selected, totalAmount) {
  // Remove existing modal if any
  const oldModal = document.getElementById('purchase-modal');
  if (oldModal) oldModal.remove();

  const modal = document.createElement('div');
  modal.id = 'purchase-modal';
  modal.style.position = 'fixed';
  modal.style.top = '0';
  modal.style.left = '0';
  modal.style.width = '100vw';
  modal.style.height = '100vh';
  modal.style.background = 'rgba(0,0,0,0.35)';
  modal.style.display = 'flex';
  modal.style.alignItems = 'center';
  modal.style.justifyContent = 'center';
  modal.style.zIndex = '9999';

  modal.innerHTML = `
    <div style="background:#fff;padding:2rem 2.5rem;border-radius:16px;max-width:400px;width:90%;box-shadow:0 8px 32px rgba(44,62,80,0.18);text-align:center;">
      <h3 style="color:#2e7d32;margin-bottom:1.2rem;">Purchase Summary</h3>
      <div style="text-align:left;font-size:1.05em;margin-bottom:1rem;">
        ${selected.map(item => `
          <div style="margin-bottom:0.7em;">
            <img src="${item.img || 'images/no-image.png'}" alt="${item.name}" style="width:40px;height:40px;object-fit:cover;border-radius:6px;vertical-align:middle;margin-right:10px;">
            <span>${item.name} (x${item.quantity})</span>
            <span style="float:right;">BDT ${(item.price * item.quantity).toFixed(2)}</span>
          </div>
        `).join('')}
      </div>
      <div style="font-weight:bold;color:#388e3c;font-size:1.1em;margin-bottom:1.5rem;">
        Total Amount: BDT ${totalAmount.toFixed(2)}
      </div>
      <div style="margin-bottom:1.2rem;">Are you sure you want to proceed to payment?</div>
      <button id="confirm-payment-btn" style="padding:0.6rem 1.5rem;background:#43a047;color:#fff;border:none;border-radius:6px;font-size:1em;cursor:pointer;margin-right:1rem;">Yes, Pay Now</button>
      <button id="close-modal-btn" style="padding:0.6rem 1.5rem;background:#e53935;color:#fff;border:none;border-radius:6px;font-size:1em;cursor:pointer;">Cancel</button>
    </div>
  `;
  document.body.appendChild(modal);

  document.getElementById('close-modal-btn').onclick = function () {
    modal.remove();
  };

  document.getElementById('confirm-payment-btn').onclick = function () {
    modal.remove();
    // Redirect to demo payment page with amount as URL parameter
    window.location.href = "payment.html?amount=" + encodeURIComponent(totalAmount.toFixed(2));
  };
}

document.addEventListener("DOMContentLoaded", function () {
  // Hide error message on input
  ["name", "email", "password", "confirmPassword"].forEach(id => {
    document.getElementById(id).addEventListener("input", function () {
      document.getElementById("errorMsg").style.display = "none";
    });
  });

  document.getElementById("signupForm").onsubmit = function (e) {
    e.preventDefault();

    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;
    const errorMsg = document.getElementById("errorMsg");
    errorMsg.style.display = "none";

    if (!name || !email || !password || !confirmPassword) {
      errorMsg.textContent = "Please fill all fields.";
      errorMsg.style.display = "block";
      return;
    }

    if (password.length < 6) {
      errorMsg.textContent = "Password must be at least 6 characters.";
      errorMsg.style.display = "block";
      return;
    }

    if (password !== confirmPassword) {
      errorMsg.textContent = "Passwords do not match.";
      errorMsg.style.display = "block";
      return;
    }

    // Prepare form data without confirmPassword
    const formData = new FormData();
    formData.append("name", name);
    formData.append("email", email);
    formData.append("password", password);

    fetch("signup.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.text())
      .then((msg) => {
        if (msg.trim() === "success") {
          alert("Signup successful!");
          document.getElementById("signupForm").reset();
        } else {
          errorMsg.textContent = msg;
          errorMsg.style.display = "block";
        }
      })
      .catch(() => {
        errorMsg.textContent = "Server error. Try again later.";
        errorMsg.style.display = "block";
      });
  };
});

function updateCartCount() {
  var userIsLoggedIn = typeof window.userIsLoggedIn !== "undefined" ? window.userIsLoggedIn : false;
  if (userIsLoggedIn) {
    fetch('cart_count.php')
      .then(res => res.json())
      .then(data => {
        const cartCountElement = document.getElementById('cart-count');
        if (cartCountElement) {
          cartCountElement.textContent = data.count;
        }
      })
      .catch(error => {
        console.error('Error updating cart count:', error);
      });
  } else {
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    let count = cart.reduce((sum, item) => sum + item.quantity, 0);
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
      cartCountElement.textContent = count;
    }
  }
}

document.addEventListener('DOMContentLoaded', updateCartCount);

