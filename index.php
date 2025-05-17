<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MediLink - Inspired by Prescripto</title>
  <link rel="stylesheet" href="css/style.css">
  <script defer src="js/main.js"></script>
  
  <?php include 'header.php'; ?>
  
  <style>
    .doctor-card {
      display: inline-block;
      vertical-align: top;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(67,160,71,0.08);
      margin: 1rem;
      padding: 1.2rem 1.5rem;
      width: 220px;
      text-align: center;
    }
    .doctor-card img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 50%;
      margin-bottom: 0.7rem;
      border: 2px solid #43a047;
      background: #f1f8e9;
    }
    .doctor-card h3 {
      margin: 0.5rem 0 0.2rem 0;
      color: #2e7d32;
      font-size: 1.1rem;
    }
    .doctor-card p {
      color: #555;
      font-size: 0.98rem;
      margin-bottom: 0.7rem;
    }
    .doctor-card button {
      background: #43a047;
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 0.5rem 1.2rem;
      font-size: 1em;
      cursor: pointer;
      transition: background 0.2s;
    }
    .doctor-card button:hover {
      background: #2e7d32;
    }
    .categories-section {
      float: left;
      width: 220px;
      margin-right: 2rem;
    }
    .main-content-wrapper {
      margin-left: 240px;
    }
    #doctors-section {
      margin-bottom: 2rem;
    }
    @media (max-width: 900px) {
      .categories-section {
        float: none;
        width: 100%;
        margin: 0 0 1.5rem 0;
      }
      .main-content-wrapper {
        margin-left: 0;
      }
    }
    .products-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 1.5rem;
    }
    .product-item {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(44,62,80,0.08);
      padding: 1rem;
      width: 180px;
      text-align: center;
    }
    .product-item img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 0.5rem;
      background: #f1f8e9;
    }
    .product-item h3 {
      margin: 0.5rem 0 0.2rem 0;
      color: #2e7d32;
      font-size: 1rem;
    }
    .product-item p {
      color: #555;
      font-size: 0.95rem;
      margin-bottom: 0.7rem;
    }
    .product-item button {
      background: #43a047;
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 0.4rem 1.1rem;
      font-size: 1em;
      cursor: pointer;
      transition: background 0.2s;
    }
    .product-item button:hover {
      background: #2e7d32;
    }
    /* Category active state and hover */
    #category-list li {
      cursor: pointer;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      margin-bottom: 0.3rem;
      transition: background 0.2s, color 0.2s;
      font-weight: 500;
      color: #333;
      border: 2px solid transparent;
      display: block;
    }
    #category-list li.active,
    #category-list li:hover {
      background: #e8f5e9;
      color: #2e7d32;
      border-color: #43a047;
    }
    /* Book Appointment Modal UI */
    #appointment-modal .modal-content {
      background: linear-gradient(135deg, #e8f5e9 0%, #fff 100%);
      padding: 2.5rem 2.5rem 2rem 2.5rem;
      border-radius: 20px;
      max-width: 420px;
      width: 95vw;
      box-shadow: 0 8px 32px rgba(44,62,80,0.18);
      text-align: left;
      position: relative;
      animation: modalPop 0.3s cubic-bezier(.68,-0.55,.27,1.55);
    }
    @keyframes modalPop {
      0% { transform: scale(0.8); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }
    #appointment-modal h3 {
      color: #2e7d32;
      margin-bottom: 1.2rem;
      text-align: center;
      font-size: 1.5rem;
      letter-spacing: 1px;
    }
    #appointment-modal label {
      display: block;
      margin-bottom: 0.3rem;
      color: #388e3c;
      font-weight: 500;
      font-size: 1rem;
    }
    #appointment-modal input {
      width: 100%;
      padding: 0.55rem 0.8rem;
      border: 1.5px solid #b2dfdb;
      border-radius: 6px;
      margin-bottom: 1rem;
      font-size: 1rem;
      background: #f9fbe7;
      transition: border 0.2s;
    }
    #appointment-modal input:focus {
      border-color: #43a047;
      outline: none;
      background: #fff;
    }
    #appointment-modal .modal-actions {
      text-align: right;
      margin-top: 1.2rem;
    }
    #appointment-modal button[type="submit"] {
      padding: 0.6rem 1.5rem;
      background: linear-gradient(90deg, #43a047 60%, #66bb6a 100%);
      color: #fff;
      border: none;
      border-radius: 6px;
      font-size: 1em;
      cursor: pointer;
      margin-right: 1rem;
      font-weight: 600;
      box-shadow: 0 2px 8px rgba(67,160,71,0.08);
      transition: background 0.2s;
    }
    #appointment-modal button[type="submit"]:hover {
      background: linear-gradient(90deg, #388e3c 60%, #43a047 100%);
    }
    #appointment-modal button[type="button"] {
      padding: 0.6rem 1.5rem;
      background: #e53935;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-size: 1em;
      cursor: pointer;
      font-weight: 600;
      transition: background 0.2s;
    }
    #appointment-modal button[type="button"]:hover {
      background: #b71c1c;
    }
    /* Top Flex Layout for Categories | Slideshow | Book Appointment */
    .top-flex-row {
      display: flex;
      align-items: flex-start;
      justify-content: center;
      gap: 2rem;
      max-width: 1400px;
      margin: 2rem auto 0 auto;
    }
    @media (max-width: 1200px) {
      .top-flex-row {
        flex-direction: column;
        align-items: stretch;
        gap: 1.5rem;
      }
      .categories-section,
      #book-appointment-panel {
        max-width: 100%;
        margin: 0 auto;
      }
      .slideshow-section {
        max-width: 100%;
        margin: 0 auto;
      }
    }
  </style>
</head>
<body>
     <!-- Top Flex Row: Categories | Slideshow | Book Appointment -->
  <div class="top-flex-row">
    <!-- Left: Categories -->
    <section class="categories-section" style="background:#fff;border-radius:16px;box-shadow:0 2px 12px rgba(44,62,80,0.10);padding:1.5rem 1rem;">
      <h2 style="color:#2e7d32;text-align:center;margin-bottom:1rem;">Categories</h2>
      <ul id="category-list" style="list-style:none;padding:0;margin:0;">
        <li>Cardiology</li>
        <li>Dermatology</li>
        <li>Neurology</li>
        <li>Pediatrics</li>
        <li>Orthopedics</li>
        <li>General Surgery</li>
        <li>Gynecology</li>
        <li>ENT</li>
        <li>Psychiatry</li>
        <li>Endocrinology</li>
      </ul>
    </section>

    <!-- Middle: Slideshow -->
    <section class="slideshow-section" style="flex:1 1 700px;max-width:700px;">
      <div class="slideshow-container" style="width:100%;border-radius:18px;box-shadow:0 2px 12px rgba(44,62,80,0.10);overflow:hidden;">
        <div class="slideshow-slide fade">
          <img src="images/d.jpg" alt="Slide 1" style="width:100%;height:auto;object-fit:cover;">
        </div>
        <div class="slideshow-slide fade">
          <img src="images/d3.jpg" alt="Slide 2" style="width:100%;height:auto;object-fit:cover;">
        </div>
        <div class="slideshow-slide fade">
          <img src="images/d4.jpg" alt="Slide 3" style="width:100%;height:auto;object-fit:cover;">
        </div>
        <a class="prev">&#10094;</a>
        <a class="next">&#10095;</a>
      </div>
      <div style="text-align:center;margin-top:10px;">
        <span class="slideshow-dot"></span>
        <span class="slideshow-dot"></span>
        <span class="slideshow-dot"></span>
      </div>
    </section>

    <!-- Right: Book Appointment  -->
    <section id="book-appointment-panel" style="flex:0 0 360px;max-width:700px;background:linear-gradient(135deg,#e8f5e9 60%,#fff 100%);border-radius:18px;box-shadow:0 2px 12px rgba(44,62,80,0.10);padding:2.2rem 1.5rem;display:flex;flex-direction:column;align-items:center;">
      <h2 style="color:#2e7d32;text-align:center;margin-bottom:1.2rem;font-size:1.5rem;letter-spacing:1px;">Book Appointment</h2>
      <form id="quickAppointmentForm" autocomplete="off" style="width:100%;display:flex;flex-direction:column;gap:1rem;">
        <div style="display:flex;align-items:center;gap:0.7rem;">
          <span style="font-size:1.3rem;color:#43a047;">&#128100;</span>
          <input type="text" id="quickPatientName" required placeholder="Your Name" style="flex:1;padding:0.7rem 1rem;border-radius:8px;border:1.5px solid #bdbdbd;background:#f9fbe7;font-size:1rem;">
        </div>
        <div style="display:flex;align-items:center;gap:0.7rem;">
          <span style="font-size:1.3rem;color:#43a047;">&#9993;</span>
          <input type="email" id="quickPatientEmail" required placeholder="Your Email" style="flex:1;padding:0.7rem 1rem;border-radius:8px;border:1.5px solid #bdbdbd;background:#f9fbe7;font-size:1rem;">
        </div>
        <div style="display:flex;align-items:center;gap:0.7rem;">
          <span style="font-size:1.3rem;color:#43a047;">&#128137;</span>
          <select id="quickDepartment" required style="flex:1;padding:0.7rem 1rem;border-radius:8px;border:1.5px solid #bdbdbd;background:#f9fbe7;font-size:1rem;">
            <option value="">Department</option>
            <option>Cardiology</option>
            <option>Dermatology</option>
            <option>Neurology</option>
            <option>Pediatrics</option>
            <option>Orthopedics</option>
            <option>General Surgery</option>
            <option>Gynecology</option>
            <option>ENT</option>
            <option>Psychiatry</option>
            <option>Endocrinology</option>
          </select>
        </div>
        <div style="display:flex;align-items:center;gap:0.7rem;">
          <span style="font-size:1.3rem;color:#43a047;">&#128197;</span>
          <input type="date" id="quickAppointmentDate" required style="flex:1;padding:0.7rem 1rem;border-radius:8px;border:1.5px solid #bdbdbd;background:#f9fbe7;font-size:1rem;">
        </div>
        <div style="display:flex;align-items:center;gap:0.7rem;">
          <span style="font-size:1.3rem;color:#43a047;">&#128337;</span>
          <input type="time" id="quickAppointmentTime" required style="flex:1;padding:0.7rem 1rem;border-radius:8px;border:1.5px solid #bdbdbd;background:#f9fbe7;font-size:1rem;">
        </div>
        <button type="submit" style="margin-top:0.7rem;width:100%;background:linear-gradient(90deg,#43a047 60%,#66bb6a 100%);color:#fff;padding:0.9rem 0;font-size:1.1rem;border:none;border-radius:8px;font-weight:700;box-shadow:0 2px 8px rgba(67,160,71,0.08);letter-spacing:0.5px;transition:background 0.2s;">Book Now</button>
        <div id="quickAppMsg" style="display:none;margin-top:1rem;text-align:center;font-size:1rem;"></div>
      </form>
    </section>
  </div>

  <div class="main-content-wrapper">
    <!-- Doctors Section (always above main content, filtered by category) -->
    <section id="doctors-section" class="doctors-container" style="display:none;">
      <h2>Available Doctors</h2>
      <div id="doctors-list"></div>
    </section>

    
      <!-- Available Medicines Section: Always visible under doctors -->
    <section id="available-medicines-section" style="margin-top:2rem;">
      <h2>Available Medicines</h2>
      <div class="products-grid">
        <div class="product-item">
          <img src="images/paracetamol.jpg" alt="Paracetamol" />
          <h3>Paracetamol</h3>
          <p>Price: BDT 5.00</p>
          <button>Buy</button>
        </div>
        <div class="product-item">
          <img src="images/Ibuprofen.jpg" alt="Ibuprofen" />
          <h3>Ibuprofen</h3>
          <p>Price: BDT 7.00</p>
          <button>Buy</button>
        </div>
        <div class="product-item">
          <img src="images/Amoxicillin.png" alt="Amoxicillin" />
          <h3>Amoxicillin</h3>
          <p>Price: BDT 12.00</p>
          <button>Buy</button>
        </div>
        <div class="product-item">
          <img src="images/Metformin.jpeg" alt="Metformin" />
          <h3>Metformin</h3>
          <p>Price: BDT 15.00</p>
          <button>Buy</button>
        </div>
        <div class="product-item">
          <img src="images/Lisinopril.jpeg" alt="Lisinopril" />
          <h3>Lisinopril</h3>
          <p>Price: BDT 10.00</p>
          <button>Buy</button>
        </div>
        <div class="product-item">
          <img src="images/Amlodipine.jpeg" alt="Amlodipine" />
          <h3>Amlodipine</h3>
          <p>Price: BDT 8.00</p>
          <button>Buy</button>
        </div>
        <div class="product-item">
          <img src="images/Omeprazole.jpg" alt="Omeprazole" />
          <h3>Omeprazole</h3>
          <p>Price: BDT 9.00</p>
          <button>Buy</button>
        </div>
        <div class="product-item">
          <img src="images/Simvastatin.png" alt="Simvastatin" />
          <h3>Simvastatin</h3>
          <p>Price: BDT 11.00</p>
          <button>Buy</button>
        </div>
        <div class="product-item">
          <img src="images/Azithromycin.jpg" alt="Azithromycin" />
          <h3>Azithromycin</h3>
          <p>Price: BDT 14.00</p>
          <button>Buy</button>
        </div>
        <div class="product-item">
          <img src="images/Hydrochlorothiazide.jpg" alt="Hydrochlorothiazide" />
          <h3>Hydrochlorothiazide</h3>
          <p>Price: BDT 6.00</p>
          <button>Buy</button>
        </div>
        <div class="product-item">
          <img src="images/Albuterol.jpg" alt="Albuterol" />
          <h3>Albuterol</h3>
          <p>Price: BDT 13.00</p>
          <button>Buy</button>
        </div>
        <div class="product-item">
          <img src="images/Gabapentin.jpg" alt="Gabapentin" />
          <h3>Gabapentin</h3>
          <p>Price: BDT 16.00</p>
          <button>Buy</button>
        </div>
        <div class="product-item">
          <img src="images/Sertraline.jpg" alt="Sertraline" />
          <h3>Sertraline</h3>
          <p>Price: BDT 18.00</p>
          <button>Buy</button>
        </div>
        <div class="product-item">
          <img src="images/Furosemide.jpg" alt="Furosemide" />
          <h3>Furosemide</h3>
          <p>Price: BDT 7.50</p>
          <button>Buy</button>
        </div>
        <div class="product-item">
          <img src="images/Prednisone.jpg" alt="Prednisone" />
          <h3>Prednisone</h3>
          <p>Price: BDT 9.50</p>
          <button>Buy</button>
        </div>
        <div class="product-item">
          <img src="images/Warfarin.png" alt="Warfarin" />
          <h3>Warfarin</h3>
          <p>Price: BDT 20.00</p>
          <button>Buy</button>
        </div>
        <div class="product-item">
          <img src="images/Citalopram.jpg" alt="Citalopram" />
          <h3>Citalopram</h3>
          <p>Price: BDT 17.00</p>
          <button>Buy</button>
        </div>
        <div class="product-item">
          <img src="images/Clopidogrel.jpeg" alt="Clopidogrel" />
          <h3>Clopidogrel</h3>
          <p>Price: BDT 19.00</p>
          <button>Buy</button>
        </div>
        <div class="product-item">
          <img src="images/Montelukast.jpg" alt="Montelukast" />
          <h3>Montelukast</h3>
          <p>Price: BDT 8.50</p>
          <button>Buy</button>
        </div>
        <div class="product-item">
          <img src="images/Fluoxetine.jpg" alt="Fluoxetine" />
          <h3>Fluoxetine</h3>
          <p>Price: BDT 15.50</p>
          <button>Buy</button>
        </div>
      </div>
    </section>
      </div>
    </section>
  </div>

  <footer>
    &copy; 2025 MediLink. All rights reserved.
  </footer>

  <script>
  // Book Appointment Panel (right side) logic
  document.getElementById('quickAppointmentForm').onsubmit = function(e) {
    e.preventDefault();
    const msg = document.getElementById('quickAppMsg');
    msg.style.display = 'none';
    
    const name = document.getElementById('quickPatientName').value.trim();
    const email = document.getElementById('quickPatientEmail').value.trim();
    const dept = document.getElementById('quickDepartment').value;
    const date = document.getElementById('quickAppointmentDate').value;
    const time = document.getElementById('quickAppointmentTime').value;
    
    if (!name || !email || !dept || !date || !time) {
      msg.textContent = "Please fill in all fields.";
      msg.style.color = "#e53935";
      msg.style.display = "block";
      return;
    }
    
    // Create form data to send to server
    const formData = new FormData();
    formData.append('doctor_name', 'Not Specified'); // Default doctor name
    formData.append('category', dept);
    formData.append('patient_name', name);
    formData.append('patient_email', email);
    formData.append('appointment_date', date);
    formData.append('appointment_time', time);
    
    // Send the appointment data to server
    fetch('book_appointment.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(data => {
      if (data.trim() === 'success') {
        msg.textContent = "Appointment booked successfully!";
        msg.style.color = "#2e7d32";
        msg.style.display = "block";
        this.reset();
      } else {
        msg.textContent = data || "Error booking appointment. Please try again.";
        msg.style.color = "#e53935";
        msg.style.display = "block";
      }
    })
    .catch(error => {
      console.error('Error:', error);
      msg.textContent = "Network error. Please try again later.";
      msg.style.color = "#e53935";
      msg.style.display = "block";
    });
  };

  // Example doctor data (category must match your categories)
  const doctors = [
    { name: "Dr. Farhan Rahman", category: "Cardiology", img: "images/doctor1.jpg", info: "Cardiologist | Available: Mon-Wed" },
    { name: "Dr. Aminul Islam", category: "Dermatology", img: "images/doctor2.jpg", info: "Dermatologist | Available: Thu-Sat" },
    { name: "Dr. Rakib Hossain", category: "Neurology", img: "images/doctor3.jpg", info: "Neurologist | Available: Mon-Fri" },
    { name: "Dr. Saad Ahmed", category: "Pediatrics", img: "images/doctor4.jpg", info: "Pediatrician | Available: Tue-Thu" },
    { name: "Dr. Tanvir Ahmed", category: "Orthopedics", img: "images/doctor6.png", info: "Orthopedic | Available: Mon-Wed" },
    { name: "Dr. Nazmul Hossain", category: "General Surgery", img: "images/Nazmul.png", info: "General Surgeon | Available: Thu-Sat" },
    { name: "Dr. Rumana Sultana", category: "Gynecology", img: "images/Rumana.png", info: "Gynecologist | Available: Mon-Fri" },
    { name: "Dr. Shahriar Hossain", category: "ENT", img: "images/Shahriar.png", info: "ENT Specialist | Available: Tue-Thu" },
    { name: "Dr. Mamun Ahmed", category: "Psychiatry", img: "images/Mamun.png ", info: "Psychiatrist | Available: Mon-Wed" },
    { name: "Dr. Fahmida Khatun", category: "Endocrinology", img: "images/Fahmida .png", info: "Endocrinologist | Available: Thu-Sat" }
  ];

  // Mark active category and show doctors
  document.querySelectorAll('#category-list li').forEach(function(li) {
    li.addEventListener('click', function() {
      // Mark active
      document.querySelectorAll('#category-list li').forEach(li2 => li2.classList.remove('active'));
      this.classList.add('active');
      // Show doctors
      const selectedCategory = this.textContent.trim();
      document.getElementById('doctors-section').style.display = '';
      // Filter and render doctors
      const filtered = doctors.filter(doc => doc.category === selectedCategory);
      const doctorsList = document.getElementById('doctors-list');
      doctorsList.innerHTML = filtered.length ? filtered.map(doc => `
        <div class="doctor-card">
          <img src="${doc.img}" alt="${doc.name}" />
          <h3>${doc.name}</h3>
          <p>${doc.info}</p>
          <button class="book-appointment-btn" 
            data-doctor="${doc.name}" 
            data-category="${doc.category}">Book Appointment</button>
        </div>
      `).join('') : `<div>No doctors found for this category.</div>`;
      // Scroll to doctors section
      document.getElementById('doctors-section').scrollIntoView({behavior: "smooth"});
    });
  });

  // Appointment modal for doctor
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('book-appointment-btn')) {
      const doctor = e.target.getAttribute('data-doctor');
      const category = e.target.getAttribute('data-category');
      showAppointmentModal(doctor, category);
    }
  });

  function showAppointmentModal(doctor, category) {
    // Remove existing modal if any
    const oldModal = document.getElementById('appointment-modal');
    if (oldModal) oldModal.remove();

    const modal = document.createElement('div');
    modal.id = 'appointment-modal';
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
      <div class="modal-content">
        <h3>Book Appointment</h3>
        <form id="doctorAppointmentForm" autocomplete="off">
          <label>Doctor</label>
          <input type="text" value="${doctor}" readonly>
          <label>Category</label>
          <input type="text" value="${category}" readonly>
          <label>Your Name</label>
          <input type="text" id="patientName" required placeholder="Enter your name">
          <label>Your Email</label>
          <input type="email" id="patientEmail" required placeholder="Enter your email">
          <label>Date</label>
          <input type="date" id="appointmentDate" required>
          <label>Time</label>
          <input type="time" id="appointmentTime" required>
          <div class="modal-actions">
            <button type="submit">Book</button>
            <button type="button" id="close-appointment-modal-btn">Cancel</button>
          </div>
        </form>
      </div>
    `;
    document.body.appendChild(modal);

    document.getElementById('close-appointment-modal-btn').onclick = function() {
      modal.remove();
    };

    document.getElementById('doctorAppointmentForm').onsubmit = function(e) {
      e.preventDefault();
      
      const doctor = document.querySelector('#doctorAppointmentForm input:nth-of-type(1)').value;
      const category = document.querySelector('#doctorAppointmentForm input:nth-of-type(2)').value;
      const name = document.getElementById('patientName').value.trim();
      const email = document.getElementById('patientEmail').value.trim();
      const date = document.getElementById('appointmentDate').value;
      const time = document.getElementById('appointmentTime').value;
      
      if (!name || !email || !date || !time) {
        alert('Please fill in all fields');
        return;
      }
      
      // Create form data to send to server
      const formData = new FormData();
      formData.append('doctor_name', doctor);
      formData.append('category', category);
      formData.append('patient_name', name);
      formData.append('patient_email', email);
      formData.append('appointment_date', date);
      formData.append('appointment_time', time);
      
      // Send the appointment data to server
      fetch('book_appointment.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.text())
      .then(data => {
        modal.remove();
        if (data.trim() === 'success') {
          showModal(`Appointment booked with ${doctor}!`);
        } else {
          showModal(`Error: ${data || "Could not book appointment"}`);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        modal.remove();
        showModal('Network error. Please try again later.');
      });
    };
  }

  // Simple modal for booking confirmation
  function showModal(message) {
    const oldModal = document.getElementById('doctor-modal');
    if (oldModal) oldModal.remove();
    const modal = document.createElement('div');
    modal.id = 'doctor-modal';
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
        <button id="close-doctor-modal-btn" style="padding:0.6rem 1.5rem;background:#43a047;color:#fff;border:none;border-radius:6px;font-size:1em;cursor:pointer;">OK</button>
      </div>
    `;
    document.body.appendChild(modal);
    document.getElementById('close-doctor-modal-btn').onclick = function() {
      modal.remove();
    };
  }
  </script>
</body>
</html>