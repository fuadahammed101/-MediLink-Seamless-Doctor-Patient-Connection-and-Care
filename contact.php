<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact Us - MediLink</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    body {
      background: linear-gradient(120deg, #e8f5e9 0%, #b2dfdb 100%);
      min-height: 100vh;
      margin: 0;
    }
    .contact-container {
      max-width: 600px;
      margin: 40px auto;
      padding: 32px 24px 28px 24px;
      font-family: 'Segoe UI', Arial, sans-serif;
      line-height: 1.6;
      color: #333;
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 8px 32px rgba(44,62,80,0.13);
      position: relative;
      overflow: hidden;
    }
    .contact-container:before {
      content: "";
      position: absolute;
      top: -60px; right: -60px;
      width: 180px; height: 180px;
      background: radial-gradient(circle, #b2dfdb 60%, transparent 100%);
      z-index: 0;
    }
    .contact-container h1 {
      text-align: center;
      color: #2e7d32;
      margin-bottom: 18px;
      font-size: 2.1rem;
      font-weight: 800;
      letter-spacing: 1px;
      position: relative;
      z-index: 1;
    }
    .contact-info {
      background: #f9fbe7;
      border-radius: 10px;
      padding: 1.2rem 1rem 1rem 1rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 2px 8px rgba(67,160,71,0.06);
      position: relative;
      z-index: 1;
    }
    .contact-info p {
      margin: 10px 0;
      font-size: 1.08em;
      color: #388e3c;
    }
    .contact-info p span {
      font-weight: bold;
      color: #2e7d32;
    }
    .contact-info p:first-child {
      color: #333;
      font-weight: 500;
    }
    h2 {
      color: #388e3c;
      margin-top: 1.5rem;
      margin-bottom: 0.7rem;
      font-size: 1.3rem;
      font-weight: 700;
      letter-spacing: 0.5px;
      text-align: center;
      position: relative;
      z-index: 1;
    }
    form label {
      display: block;
      margin-top: 15px;
      font-weight: 600;
      color: #388e3c;
      font-size: 1.05rem;
      letter-spacing: 0.2px;
    }
    form input, form textarea, form button {
      width: 100%;
      padding: 10px 12px;
      margin-top: 5px;
      border-radius: 8px;
      border: 1.5px solid #bdbdbd;
      box-sizing: border-box;
      font-size: 1em;
      background: #f9fbe7;
      transition: border 0.2s, box-shadow 0.2s;
    }
    form input:focus, form textarea:focus {
      border-color: #43a047;
      outline: none;
      background: #fff;
      box-shadow: 0 2px 8px rgba(67,160,71,0.10);
    }
    form textarea {
      min-height: 70px;
      resize: vertical;
    }
    form button {
      background: linear-gradient(90deg, #43a047 60%, #66bb6a 100%);
      color: white;
      border: none;
      margin-top: 22px;
      font-weight: 700;
      font-size: 1.08rem;
      letter-spacing: 0.5px;
      cursor: pointer;
      box-shadow: 0 2px 8px rgba(67,160,71,0.08);
      transition: background 0.2s, box-shadow 0.2s;
    }
    form button:hover {
      background: linear-gradient(90deg, #388e3c 60%, #43a047 100%);
      box-shadow: 0 4px 16px rgba(67,160,71,0.13);
    }
    .success-message {
      background: #e8f5e9;
      color: #2e7d32;
      border-radius: 7px;
      padding: 0.8rem 1rem;
      margin-top: 1.2rem;
      text-align: center;
      font-weight: 600;
      font-size: 1.08rem;
      display: none;
    }
    .contact-social {
      margin-top: 1.5rem;
      text-align: center;
      z-index: 1;
      position: relative;
    }
    .contact-social a {
      display: inline-block;
      margin: 0 10px;
      color: #43a047;
      font-size: 1.6rem;
      transition: color 0.2s;
      text-decoration: none;
    }
    .contact-social a:hover {
      color: #2e7d32;
    }
    @media (max-width: 700px) {
      .contact-container {
        padding: 16px 4vw 18px 4vw;
      }
      .contact-info {
        padding: 0.7rem 0.5rem 0.7rem 0.5rem;
      }
    }
  </style>
  <!-- Font Awesome for social icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
</head>
<body>
  <main>
    <section class="contact-container">
      <h1>Contact Us</h1>
      <div class="contact-info">
        <p>Have a question or feedback? We’d love to hear from you!</p>
        <p>📧 <span>Email:</span> support@medilink.com</p>
        <p>📞 <span>Phone:</span> +880-123-456-789</p>
        <p>📍 <span>Address:</span><br/>
          3rd Floor, HealthTech Tower<br/>
          Dhanmondi, Dhaka, Bangladesh
        </p>
        <p>Let us know how we can assist you in your healthcare journey with MediLink!</p>
      </div>

      <div class="contact-social">
        <a href="https://facebook.com/" target="_blank" title="Facebook"><i class="fab fa-facebook"></i></a>
        <a href="https://twitter.com/" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a>
        <a href="https://linkedin.com/" target="_blank" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
        <a href="https://wa.me/880123456789" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
      </div>

      <h2>Book a Call</h2>
      <form id="bookCallForm" autocomplete="off">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required />

        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" required pattern="^\+?\d{7,15}$" placeholder="+880123456789" />

        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required min="<?= date('Y-m-d') ?>" />

        <label for="time">Time:</label>
        <input type="time" id="time" name="time" required />

        <label for="note">Message (optional):</label>
        <textarea id="note" name="note" placeholder="How can we help you?"></textarea>

        <button type="submit">Book Call</button>
        <div class="success-message" id="callSuccessMsg"></div>
      </form>
    </section>
  </main>

  <footer>
    &copy; 2025 MediLink. All rights reserved.
  </footer>
  <script>
    // Book Call Form Handler
    document.getElementById('bookCallForm').onsubmit = function(e) {
      e.preventDefault();
      const name = document.getElementById('name').value.trim();
      const phone = document.getElementById('phone').value.trim();
      const date = document.getElementById('date').value;
      const time = document.getElementById('time').value;
      const msg = document.getElementById('callSuccessMsg');
      if (!name || !phone || !date || !time) {
        msg.textContent = "Please fill in all required fields.";
        msg.style.background = "#ffebee";
        msg.style.color = "#e53935";
        msg.style.display = "block";
        return;
      }
      msg.textContent = "Your call has been booked! We will contact you soon.";
      msg.style.background = "#e8f5e9";
      msg.style.color = "#2e7d32";
      msg.style.display = "block";
      this.reset();
      setTimeout(() => { msg.style.display = "none"; }, 4000);
    };
  </script>
</body>
</html>