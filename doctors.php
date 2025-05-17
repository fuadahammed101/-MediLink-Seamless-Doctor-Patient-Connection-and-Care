<!DOCTYPE html>
<html lang="en">
  
<head>
  <meta charset="UTF-8">
  <title>MediLink - Doctors</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    /* Modal Styles */
    .modal-bg {
      position: fixed;
      top: 0; left: 0; width: 100vw; height: 100vh;
      background: rgba(44,62,80,0.18);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }
    .modal {
      background: #fff;
      padding: 2.2rem 2.5rem;
      border-radius: 18px;
      box-shadow: 0 8px 32px rgba(44,62,80,0.18);
      text-align: left;
      max-width: 370px;
      width: 95vw;
      animation: popin 0.25s;
      position: relative;
    }
    @keyframes popin {
      0% { transform: scale(0.85); opacity: 0;}
      100% { transform: scale(1); opacity: 1;}
    }
    .modal h3 {
      color: #2e7d32;
      margin-bottom: 1.1rem;
      text-align: center;
      font-size: 1.4rem;
      letter-spacing: 1px;
    }
    .modal label {
      color: #388e3c;
      font-weight: 600;
      margin-bottom: 0.2rem;
      display: block;
      font-size: 1rem;
    }
    .modal input, .modal select {
      width: 100%;
      padding: 0.7rem 1rem;
      border-radius: 8px;
      border: 1.5px solid #bdbdbd;
      background: #f9fbe7;
      font-size: 1rem;
      margin-bottom: 1rem;
      transition: border 0.2s;
    }
    .modal input:focus, .modal select:focus {
      border-color: #43a047;
      outline: none;
      background: #fff;
    }
    .modal .modal-actions {
      display: flex;
      justify-content: flex-end;
      gap: 0.7rem;
      margin-top: 0.5rem;
    }
    .modal button[type="submit"] {
      background: linear-gradient(90deg, #43a047 60%, #66bb6a 100%);
      color: #fff;
      border: none;
      border-radius: 7px;
      padding: 0.7rem 1.5rem;
      font-size: 1.08rem;
      font-weight: 600;
      letter-spacing: 0.5px;
      cursor: pointer;
      box-shadow: 0 2px 8px rgba(67,160,71,0.08);
      transition: background 0.2s;
    }
    .modal button[type="submit"]:hover {
      background: linear-gradient(90deg, #388e3c 60%, #43a047 100%);
    }
    .modal button[type="button"] {
      background: #e53935;
      color: #fff;
      border: none;
      border-radius: 7px;
      padding: 0.7rem 1.5rem;
      font-size: 1.08rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s;
    }
    .modal button[type="button"]:hover {
      background: #b71c1c;
    }
    .modal .success-msg {
      color: #2e7d32;
      text-align: center;
      margin-top: 1rem;
      font-weight: 600;
      font-size: 1.1rem;
    }
    .modal .close-x {
      position: absolute;
      top: 12px;
      right: 18px;
      font-size: 1.3rem;
      color: #757575;
      cursor: pointer;
      font-weight: bold;
      background: none;
      border: none;
    }
  </style>
</head>
<body>

  <header>
    <?php include 'header.php'; ?>
  </header>
  
  <div class="doctors-container">
    <h2>Available Doctors</h2>
    <div class="doctor-card" data-doctor="Dr. Farhan Rahman" data-category="Cardiology">
      <img src="images/doctor1.jpg" alt="Dr. Farhan Rahman" />
      <h3>Dr. Farhan Rahman</h3>
      <p>Cardiologist | Available: Mon-Wed</p>
      <button class="book-btn">Book Appointment</button>
    </div>
    <div class="doctor-card" data-doctor="Dr. Aminul Islam" data-category="Dermatology">
      <img src="images/doctor2.jpg" alt="Dr. Aminul Islam" />
      <h3>Dr. Aminul Islam</h3>
      <p>Dermatologist | Available: Thu-Sat</p>
      <button class="book-btn">Book Appointment</button>
    </div>
    <div class="doctor-card" data-doctor="Dr. Rakib Hossain" data-category="Neurology">
      <img src="images/doctor3.jpg" alt="Dr. Rakib Hossain" />
      <h3>Dr. Rakib Hossain</h3>
      <p>Neurologist | Available: Mon-Fri</p>
      <button class="book-btn">Book Appointment</button>
    </div>
    <div class="doctor-card" data-doctor="Dr. Saad Ahmed" data-category="Pediatrics">
      <img src="images/doctor4.jpg" alt="Dr. Saad Ahmed" />
      <h3>Dr. Saad Ahmed</h3>
      <p>Pediatrician | Available: Tue-Thu</p>
      <button class="book-btn">Book Appointment</button>
    </div>
    <div class="doctor-card" data-doctor="Dr. Tanvir Ahmed" data-category="Orthopedics">
      <img src="images/doctor6.png" alt="Dr. Tanvir Ahmed" />
      <h3>Dr. Tanvir Ahmed</h3>
      <p>Orthopedic | Available: Mon-Wed</p>
      <button class="book-btn">Book Appointment</button>
    </div>
    <div class="doctor-card" data-doctor="Dr. Nazmul Hossain" data-category="General Surgery">
      <img src="images/Nazmul.png" alt="Dr. Nazmul Hossain" />
      <h3>Dr. Nazmul Hossain</h3>
      <p>General Surgeon | Available: Thu-Sat</p>
      <button class="book-btn">Book Appointment</button>
    </div>
    <div class="doctor-card" data-doctor="Dr. Rumana Sultana" data-category="Gynecology">
      <img src="images/Rumana.png" alt="Dr. Rumana Sultana" />
      <h3>Dr. Rumana Sultana</h3>
      <p>Gynecologist | Available: Mon-Fri</p>
      <button class="book-btn">Book Appointment</button>
    </div>
    <div class="doctor-card" data-doctor="Dr. Shahriar Hossain" data-category="ENT">
      <img src="images/Shahriar.png" alt="Dr. Shahriar Hossain" />
      <h3>Dr. Shahriar Hossain</h3>
      <p>ENT Specialist | Available: Tue-Thu</p>
      <button class="book-btn">Book Appointment</button>
    </div>
    <div class="doctor-card" data-doctor="Dr. Mamun Ahmed" data-category="Psychiatry">
      <img src="images/Mamun.png" alt="Dr. Mamun Ahmed" />
      <h3>Dr. Mamun Ahmed</h3>
      <p>Psychiatrist | Available: Mon-Wed</p>
      <button class="book-btn">Book Appointment</button>
    </div>
    <div class="doctor-card" data-doctor="Dr. Fahmida Khatun" data-category="Endocrinology">
      <img src="images/Fahmida .png" alt="Dr. Fahmida Khatun" />
      <h3>Dr. Fahmida Khatun</h3>
      <p>Endocrinologist | Available: Thu-Sat</p>
      <button class="book-btn">Book Appointment</button>
    </div>
    <button onclick="window.history.back()" style="margin-top: 20px; padding: 10px 20px; font-size: 1em; cursor: pointer;">Go Back</button>
  </div>
  <script>
    // Modal open logic
    document.querySelectorAll('.book-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const card = this.closest('.doctor-card');
        const doctor = card.getAttribute('data-doctor');
        const category = card.getAttribute('data-category');
        showAppointmentModal(doctor, category);
      });
    });

    function showAppointmentModal(doctor, category) {
      // Remove existing modal if any
      const oldModal = document.getElementById('appointment-modal');
      if (oldModal) oldModal.remove();

      const modalBg = document.createElement('div');
      modalBg.className = 'modal-bg';
      modalBg.id = 'appointment-modal';
      modalBg.innerHTML = `
        <div class="modal">
          <button class="close-x" title="Close">&times;</button>
          <h3>Book Appointment</h3>
          <form id="modalAppointmentForm" autocomplete="off">
            <label>Doctor</label>
            <input type="text" value="${doctor}" readonly>
            <label>Category</label>
            <input type="text" value="${category}" readonly>
            <label>Your Name</label>
            <input type="text" id="modalPatientName" required placeholder="Enter your name">
            <label>Your Email</label>
            <input type="email" id="modalPatientEmail" required placeholder="Enter your email">
            <label>Date</label>
            <input type="date" id="modalAppointmentDate" required>
            <label>Time</label>
            <input type="time" id="modalAppointmentTime" required>
            <div class="modal-actions">
              <button type="submit">Book</button>
              <button type="button" id="closeModalBtn">Cancel</button>
            </div>
            <div class="success-msg" id="modalSuccessMsg" style="display:none;"></div>
          </form>
        </div>
      `;
      document.body.appendChild(modalBg);

      // Close modal handlers
      modalBg.querySelector('.close-x').onclick = closeModal;
      modalBg.querySelector('#closeModalBtn').onclick = closeModal;
      function closeModal() {
        modalBg.remove();
      }

      // Form submit handler
      modalBg.querySelector('#modalAppointmentForm').onsubmit = function(e) {
        e.preventDefault();
        
        const doctor = modalBg.querySelector('.modal input:nth-of-type(1)').value;
        const category = modalBg.querySelector('.modal input:nth-of-type(2)').value;
        const name = document.getElementById('modalPatientName').value.trim();
        const email = document.getElementById('modalPatientEmail').value.trim();
        const date = document.getElementById('modalAppointmentDate').value;
        const time = document.getElementById('modalAppointmentTime').value;
        
        if (!name || !email || !date || !time) {
          alert('Please fill in all fields');
          return;
        }
        
        // Create form data
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
          const successMsg = document.getElementById('modalSuccessMsg');
          if (data.trim() === 'success') {
            successMsg.textContent = "Appointment booked successfully!";
            successMsg.style.display = "block";
            // Reset the form
            this.reset();
            // Close modal after 1.5 seconds
            setTimeout(() => {
              modalBg.remove();
            }, 1500);
          } else {
            successMsg.textContent = data || "Error booking appointment. Please try again.";
            successMsg.style.color = "#e53935";
            successMsg.style.display = "block";
          }
        })
        .catch(error => {
          console.error('Error:', error);
          const successMsg = document.getElementById('modalSuccessMsg');
          successMsg.textContent = "Network error. Please try again later.";
          successMsg.style.color = "#e53935";
          successMsg.style.display = "block";
        });
      };
    }
  </script>
</body>
</html>