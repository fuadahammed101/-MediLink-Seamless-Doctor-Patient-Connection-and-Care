<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user']['id'];
$appointments = [];

$stmt = $conn->prepare("SELECT category, date, time FROM appointments WHERE user_id = ? ORDER BY date, time");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>MediLink - Dashboard</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background-color: #f4f7f8;
    }
    header {
      background-color: #007bff;
      color: white;
      padding: 15px 20px;
      text-align: center;
      font-size: 24px;
      font-weight: bold;
    }
    nav {
      background-color: #0056b3;
      padding: 10px 20px;
      display: flex;
      gap: 15px;
    }
    nav a {
      color: white;
      text-decoration: none;
      font-weight: 600;
      padding: 8px 12px;
      border-radius: 4px;
    }
    nav a:hover {
      background-color: #003d80;
    }
    main {
      padding: 20px;
    }
    .dashboard-welcome {
      font-size: 20px;
      margin-bottom: 20px;
    }
    .dashboard-cards {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
    }
    .card {
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      padding: 20px;
      flex: 1 1 200px;
      min-width: 200px;
      text-align: center;
    }
    .card h3 {
      margin: 0 0 10px 0;
      font-size: 18px;
      color: #007bff;
    }
    .card p {
      font-size: 14px;
      color: #555;
    }
    .appointments-section {
      margin-top: 30px;
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .appointments-section h2 {
      color: #007bff;
      margin-bottom: 15px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 10px;
      border: 1px solid #ddd;
      text-align: left;
    }
    th {
      background-color: #f0f0f0;
    }
  </style>
</head>
<body>
  <header>
    MediLink Dashboard
  </header>
    <nav>
    <a href="dashboard.php">Home</a>
    <a href="#appointments">Appointments</a>
    <a href="doctors.html">Doctors</a>
    <a href="profile.php">Profile</a>
    <a href="logout.php">Logout</a>
  </nav>
  <main>
    <div class="dashboard-welcome">
      Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?>, to your MediLink dashboard!
    </div>
    <div class="dashboard-cards">
      <div class="card">
        <h3>Upcoming Appointments</h3>
        <p>You have <?= count($appointments) ?> upcoming appointment<?= count($appointments) !== 1 ? 's' : '' ?>.</p>
      </div>
      <div class="card">
        <h3>Messages</h3>
        <p>You have 5 new messages.</p>
      </div>
      <div class="card">
        <h3>Prescriptions</h3>
        <p>2 prescriptions need renewal.</p>
      </div>
    </div>

    <section id="appointments" class="appointments-section">
      <h2>Your Appointments</h2>
      <?php if (count($appointments) > 0): ?>
        <table>
          <thead>
            <tr>
              <th>Category</th>
              <th>Date</th>
              <th>Time</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($appointments as $appt): ?>
              <tr>
                <td><?= htmlspecialchars($appt['category']) ?></td>
                <td><?= htmlspecialchars($appt['date']) ?></td>
                <td><?= htmlspecialchars($appt['time']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p>You have no upcoming appointments.</p>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>
