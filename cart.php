<?php
session_start();
require_once 'db_connect.php';

$cart_items = [];
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $res = $conn->query("SELECT * FROM cart_items WHERE user_id=$user_id");
    while ($row = $res->fetch_assoc()) {
        $cart_items[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MediLink - Cart</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .cart-section {
      max-width: 700px;
      margin: 2rem auto;
      background: #f9fbe7;
      border-radius: 16px;
      box-shadow: 0 4px 24px rgba(44,62,80,0.10);
      padding: 2rem 1.5rem 2.5rem 1.5rem;
    }
    .cart-section h2 {
      text-align: center;
      color: #2e7d32;
      margin-bottom: 2rem;
      font-size: 2rem;
      font-weight: 700;
    }
    .cart-item {
      display: flex;
      align-items: flex-start;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(67,160,71,0.08);
      margin-bottom: 1.2rem;
      padding: 1rem 1.2rem;
      gap: 18px;
      position: relative;
      transition: box-shadow 0.2s;
    }
    .cart-item:hover {
      box-shadow: 0 4px 16px rgba(67,160,71,0.16);
    }
    .cart-item img {
      width: 70px;
      height: 70px;
      object-fit: cover;
      border-radius: 10px;
      border: 1px solid #e0e0e0;
      background: #f1f8e9;
      margin-right: 10px;
    }
    .cart-item .item-details {
      flex: 1;
      min-width: 0;
    }
    .cart-item .item-details strong {
      font-size: 1.15rem;
      color: #388e3c;
    }
    .cart-item .item-details small {
      color: #757575;
      font-size: 0.95em;
    }
    .cart-item .item-details .price {
      color: #2e7d32;
      font-weight: 600;
      font-size: 1.05em;
    }
    .cart-item .item-actions {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 0.5rem;
      min-width: 120px;
    }
    .cart-item .qty-btn, .cart-item .delete-btn {
      background: #e8f5e9;
      border: none;
      border-radius: 5px;
      padding: 0.3rem 0.7rem;
      font-size: 1.1rem;
      margin: 0 2px;
      color: #2e7d32;
      cursor: pointer;
      transition: background 0.2s, color 0.2s;
    }
    .cart-item .qty-btn:hover, .cart-item .delete-btn:hover {
      background: #c8e6c9;
      color: #1b5e20;
    }
    .cart-item .delete-btn {
      background: #ffebee;
      color: #e53935;
      margin-top: 0.3rem;
    }
    .cart-item .delete-btn:hover {
      background: #ffcdd2;
      color: #b71c1c;
    }
    .cart-item .total {
      color: #388e3c;
      font-weight: 600;
      font-size: 1em;
      margin-top: 0.3rem;
    }
    .cart-item input[type="checkbox"] {
      margin-top: 8px;
      accent-color: #43a047;
      width: 18px;
      height: 18px;
    }
    #buy-selected-btn {
      width: 100%;
      padding: 0.9rem;
      background: linear-gradient(90deg, #43a047 0%, #66bb6a 100%);
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 1.1rem;
      font-weight: 700;
      cursor: pointer;
      margin-top: 1.5rem;
      box-shadow: 0 2px 8px rgba(67,160,71,0.08);
      transition: background 0.3s;
    }
    #buy-selected-btn:hover {
      background: linear-gradient(90deg, #388e3c 0%, #43a047 100%);
    }
    #selected-total {
      text-align: right;
      font-weight: bold;
      margin-top: 1.2rem;
      color: #2e7d32;
      font-size: 1.1rem;
    }
    #cart-empty {
      color: #888;
      margin-top: 2rem;
      text-align: center;
      font-size: 1.1rem;
    }
    @media (max-width: 600px) {
      .cart-section {
        padding: 1rem 0.2rem;
      }
      .cart-item {
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
      }
      .cart-item .item-actions {
        flex-direction: row;
        justify-content: flex-start;
        align-items: center;
        gap: 0.5rem;
        min-width: 0;
      }
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>
  <main>
    <section class="cart-section">
      <h2>Your Cart</h2>
      <label><input type="checkbox" id="select-all"> Select All</label>
      <div id="cart-items"></div>
      <div id="cart-empty" style="display:none;">Your cart is empty.</div>
      <div id="selected-total"></div>
      <button id="buy-selected-btn">Buy Selected</button>
    </section>
  </main>

  <footer>
    &copy; 2025 MediLink. All rights reserved.
  </footer>

  <script>
  // Set userIsLoggedIn from PHP
  var userIsLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
  let cart = [];

  document.addEventListener('DOMContentLoaded', function () {
    const cartItemsDiv = document.getElementById('cart-items');
    const cartEmptyDiv = document.getElementById('cart-empty');
    const buySelectedBtn = document.getElementById('buy-selected-btn');
    const selectedTotalDiv = document.getElementById('selected-total');
    const selectAll = document.getElementById('select-all');

    // Load cart from DB or localStorage
    if (userIsLoggedIn) {
      fetch('cart_fetch.php')
        .then(response => response.json())
        .then(data => {
          cart = data;
          renderCart();
          updateCartCount();
        })
        .catch(error => {
          console.error('Error fetching cart data:', error);
          cartEmptyDiv.textContent = 'Error loading cart. Please try again later.';
          cartEmptyDiv.style.display = '';
        });
    } else {
      cart = JSON.parse(localStorage.getItem('cart') || '[]');
      renderCart();
      updateCartCount();
    }

    function formatTime(ts) {
      const d = new Date(ts);
      return d.toLocaleString();
    }

    function renderCart() {
      cartItemsDiv.innerHTML = '';
      if (cart && cart.length > 0) {
        cartEmptyDiv.style.display = 'none';
        cart.forEach((item, idx) => {
          const div = document.createElement('div');
          div.className = 'cart-item';
          div.innerHTML = `
            <input type="checkbox" class="select-item" data-idx="${idx}">
            <img src="${item.img || 'images/no-image.png'}" alt="${item.name}">
            <div class="item-details">
              <strong>${item.name}</strong>
              <br>
              <small>Added: ${item.time ? formatTime(item.time) : 'Unknown'}</small>
              <br>
              <span class="price">Price: BDT ${item.price ? Number(item.price).toFixed(2) : 'N/A'}</span>
            </div>
            <div class="item-actions">
              <div>
                <button class="qty-btn" data-idx="${idx}" data-action="decrease">−</button>
                <span style="margin:0 8px;">${item.quantity}</span>
                <button class="qty-btn" data-idx="${idx}" data-action="increase">+</button>
              </div>
              <button class="delete-btn" data-idx="${idx}">Delete</button>
              <span class="total">Total: BDT ${(item.price * item.quantity).toFixed(2)}</span>
            </div>
          `;
          cartItemsDiv.appendChild(div);
        });
        
        // Show the Buy Selected button only if cart has items
        buySelectedBtn.style.display = '';
        if (selectedTotalDiv) selectedTotalDiv.style.display = '';
        
      } else {
        cartEmptyDiv.style.display = '';
        // Hide the Buy Selected button if cart is empty
        buySelectedBtn.style.display = 'none';
        if (selectedTotalDiv) selectedTotalDiv.style.display = 'none';
      }
      updateSelectedTotal();
    }

    function updateSelectedTotal() {
      const selected = Array.from(document.querySelectorAll('.select-item:checked'))
        .map(cb => cart[parseInt(cb.getAttribute('data-idx'), 10)]);
      const totalAmount = selected.reduce((sum, item) => sum + (item.price * item.quantity), 0);
      if (selectedTotalDiv) {
        selectedTotalDiv.textContent = `Total Selected: BDT ${totalAmount.toFixed(2)}`;
      }
      buySelectedBtn.disabled = selected.length === 0;
    }

    cartItemsDiv.addEventListener('click', function(e) {
      if (!e.target.hasAttribute('data-idx')) return;
      const idx = parseInt(e.target.getAttribute('data-idx'), 10);
      
      if (e.target.classList.contains('delete-btn')) {
        if (userIsLoggedIn) {
          // Remove from DB via AJAX
          fetch('cart_api.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({action: 'delete', name: cart[idx].name})
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              cart.splice(idx, 1);
              renderCart();
              updateCartCount();
            } else {
              alert('Failed to delete item: ' + (data.error || 'Unknown error'));
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Network error. Could not delete item.');
          });
        } else {
          cart.splice(idx, 1);
          localStorage.setItem('cart', JSON.stringify(cart));
          renderCart();
          updateCartCount();
        }
      }
      if (e.target.classList.contains('qty-btn')) {
        const action = e.target.getAttribute('data-action');
        let newQty = cart[idx].quantity;
        if (action === 'increase') {
          newQty += 1;
        } else if (action === 'decrease' && cart[idx].quantity > 1) {
          newQty -= 1;
        }
        if (userIsLoggedIn) {
          fetch('cart_api.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({action: 'update', name: cart[idx].name, quantity: newQty})
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              cart[idx].quantity = newQty;
              renderCart();
              updateCartCount();
            } else {
              alert('Failed to update quantity: ' + (data.error || 'Unknown error'));
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Network error. Could not update quantity.');
          });
        } else {
          cart[idx].quantity = newQty;
          localStorage.setItem('cart', JSON.stringify(cart));
          renderCart();
          updateCartCount();
        }
      }
    });

    cartItemsDiv.addEventListener('change', function(e) {
      if (e.target.classList.contains('select-item')) {
        updateSelectedTotal();
      }
    });

    selectAll.addEventListener('change', function() {
      document.querySelectorAll('.select-item').forEach(cb => cb.checked = selectAll.checked);
      updateSelectedTotal();
    });

    buySelectedBtn.addEventListener('click', function() {
      if (!userIsLoggedIn) {
        // Redirect to login with message
        window.location.href = "login.php?msg=" + encodeURIComponent("Please log in to complete your purchase.");
        return;
      }
      
      const selected = Array.from(document.querySelectorAll('.select-item:checked'))
        .map(cb => cart[parseInt(cb.getAttribute('data-idx'), 10)]);
        
      if (selected.length === 0) {
        alert('Please select at least one item to buy.');
        return;
      }
      
      const totalAmount = selected.reduce((sum, item) => sum + (item.price * item.quantity), 0);
      showPurchaseSummary(selected, totalAmount);
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

      document.getElementById('close-modal-btn').onclick = function() {
        modal.remove();
      };

      document.getElementById('confirm-payment-btn').onclick = function() {
        modal.remove();
        
        // Get selected items to pass to payment page
        const selectedItemsData = JSON.stringify(selected);
        
        // Store selected items in session storage for payment page
        sessionStorage.setItem('selectedItems', selectedItemsData);
        
        // Redirect to payment page with amount and selected items
        window.location.href = "payment.php?amount=" + encodeURIComponent(totalAmount.toFixed(2)) + 
                               "&selected_items=" + encodeURIComponent(selectedItemsData);
      };
    }
  });

  function updateCartCount() {
    if (typeof userIsLoggedIn !== "undefined" && userIsLoggedIn) {
      fetch('cart_count.php')
        .then(res => res.json())
        .then(data => {
          document.getElementById('cart-count').textContent = data.count;
        });
    } else {
      let cart = JSON.parse(localStorage.getItem('cart') || '[]');
      document.getElementById('cart-count').textContent = cart.length;
    }
  }
  </script>
</body>
