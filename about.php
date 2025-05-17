<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>About Us - MediLink</title>
  <link rel="stylesheet" href="css/style.css" />
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', Arial, sans-serif;
      background: linear-gradient(120deg, #e8f5e9 0%, #b2dfdb 100%);
      margin: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    main {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 1rem;
    }
    .about-container {
      max-width: 850px;
      margin: 40px auto;
      padding: 2.5rem 2rem;
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 8px 32px rgba(44,62,80,0.13);
      color: #333;
      line-height: 1.7;
      animation: fadeIn 0.7s;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px);}
      to { opacity: 1; transform: translateY(0);}
    }
    .about-container h1 {
      text-align: center;
      color: #2e7d32;
      margin-bottom: 1.3rem;
      font-size: 2.3rem;
      font-weight: 700;
      letter-spacing: 1px;
    }
    .about-container ul {
      margin-left: 1.5rem;
      list-style-type: disc;
      color: #388e3c;
      font-size: 1.07rem;
      margin-bottom: 1.2rem;
    }
    .about-container li {
      margin-bottom: 0.5em;
    }
    .about-container p {
      margin-bottom: 1.1em;
      font-size: 1.08rem;
    }
    .team-section {
      margin-top: 2.2rem;
      text-align: center;
    }
    .team-title {
      color: #388e3c;
      font-size: 1.25rem;
      font-weight: 600;
      margin-bottom: 1rem;
      letter-spacing: 0.5px;
    }
    .team-members {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 1.5rem;
    }
    .team-card {
      background: linear-gradient(135deg, #e8f5e9 60%, #b2dfdb 100%);
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(67,160,71,0.10);
      padding: 1.2rem 1.5rem;
      width: 180px;
      text-align: center;
      transition: transform 0.2s, box-shadow 0.2s;
      border: 1.5px solid #b2dfdb;
    }
    .team-card:hover {
      transform: translateY(-6px) scale(1.04);
      box-shadow: 0 6px 18px rgba(67,160,71,0.16);
      border-color: #43a047;
    }
    .team-card img {
      width: 70px;
      height: 70px;
      object-fit: cover;
      border-radius: 50%;
      margin-bottom: 0.7rem;
      border: 2.5px solid #43a047;
      background: #fff;
      box-shadow: 0 2px 8px rgba(44,62,80,0.08);
    }
    .team-card h4 {
      margin: 0.3rem 0 0.1rem 0;
      color: #2e7d32;
      font-size: 1.08rem;
      font-weight: 600;
    }
    .team-card p {
      color: #555;
      font-size: 0.97rem;
      margin-bottom: 0;
    }
    footer {
      background: #43a047;
      color: #fff;
      text-align: center;
      padding: 1.1rem 0 1rem 0;
      font-size: 1.07rem;
      border-top: 1px solid #e0e0e0;
      margin-top: 2rem;
      letter-spacing: 0.5px;
      box-shadow: 0 -2px 8px rgba(44,62,80,0.08);
    }
    @media (max-width: 900px) {
      .about-container { padding: 1.2rem 0.7rem; }
      .team-members { flex-direction: column; gap: 1rem; }
    }
  </style>
</head>
<body>
  <main>
    <section class="about-container">
      <h1>About MediLink</h1>
      <p>
        <b>MediLink</b> is a smart, all-in-one healthcare platform designed to connect patients, doctors, and pharmacists in one seamless ecosystem.
      </p>
      <p>
        Our mission is to make healthcare accessible, simple, and smart for everyone in Bangladesh and beyond.
      </p>
      <p>With features like:</p>
      <ul>
        <li>AI-powered chatbot support</li>
        <li>Doctor appointment booking</li>
        <li>Virtual drug information system (DIMS)</li>
        <li>Online pharmacy and medicine ordering</li>
      </ul>
      <p>
        MediLink is your trusted companion for managing your health from the comfort of your home.
      </p>
      <p>
        Our team consists of passionate students, tech developers, and healthcare experts who believe in the future of digital health services. We are continuously improving MediLink to introduce more intelligent and user-friendly features.
      </p>
      <div class="team-section">
        <div class="team-title">Meet Our Team</div>
        <div class="team-members">
          <div class="team-card">
            <img src="images/team1.png" alt="Team Member">
            <h4>TBA</h4>
            <p>Lead Developer</p>
          </div>
          <div class="team-card">
            <img src="images/team2.png" alt="Team Member">
            <h4>TBA</h4>
            <p>Healthcare Advisor</p>
          </div>
          <div class="team-card">
            <img src="images/team3.png" alt="Team Member">
            <h4>TBA</h4>
            <p>UI/UX Designer</p>
          </div>
          <div class="team-card">
            <img src="images/team4.png" alt="Team Member">
            <h4>TBA</h4>
            <p>Backend Engineer</p>
          </div>
        </div>
      </div>
    </section>
  </main>
  <footer>
    &copy; 2025 MediLink. All rights reserved.
  </footer>
</body>
</html>