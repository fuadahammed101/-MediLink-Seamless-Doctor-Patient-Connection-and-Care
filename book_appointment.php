<?php
session_start();
include 'db_connect.php';
header('Content-Type: text/plain');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get user ID if logged in, otherwise set to NULL
        $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

        // Collect data from form/modal
        $doctor_name = trim($_POST['doctor_name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $patient_name = trim($_POST['patient_name'] ?? '');
        $patient_email = trim($_POST['patient_email'] ?? '');
        $appointment_date = trim($_POST['appointment_date'] ?? '');
        $appointment_time = trim($_POST['appointment_time'] ?? '');

        // If user is logged in, use their name/email from session if form fields are empty
        if ($user_id) {
            if (empty($patient_name)) $patient_name = $_SESSION['user_name'];
            if (empty($patient_email)) $patient_email = $_SESSION['user_email'];
        }

        // Basic validation
        if (empty($doctor_name)) {
            echo "Doctor name is required.";
            exit();
        }
        
        if (empty($category)) {
            echo "Category is required.";
            exit();
        }
        
        if (empty($patient_name)) {
            echo "Patient name is required.";
            exit();
        }
        
        if (empty($patient_email)) {
            echo "Patient email is required.";
            exit();
        }
        
        if (empty($appointment_date)) {
            echo "Appointment date is required.";
            exit();
        }
        
        if (empty($appointment_time)) {
            echo "Appointment time is required.";
            exit();
        }

        // Validate email format
        if (!filter_var($patient_email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email address.";
            exit();
        }
        
        // Validate date format (must be a valid date and in the future)
        $current_date = date('Y-m-d');
        if (strtotime($appointment_date) === false || $appointment_date < $current_date) {
            echo "Please select a valid date in the future.";
            exit();
        }

        // Insert appointment into database
        $sql = "INSERT INTO appointments (user_id, doctor_name, category, patient_name, patient_email, appointment_date, appointment_time) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // If user_id is null, use 'i' type and pass null (MySQLi will handle it as NULL)
        $stmt->bind_param(
            "issssss",
            $user_id,
            $doctor_name,
            $category,
            $patient_name,
            $patient_email,
            $appointment_date,
            $appointment_time
        );

        if ($stmt->execute()) {
            echo "success";
        } else {
            error_log("Error booking appointment: " . $stmt->error);
            echo "Database error: " . $stmt->error;
        }

        $stmt->close();
    } catch (Exception $e) {
        error_log("Exception in book_appointment.php: " . $e->getMessage());
        echo "An error occurred. Please try again later.";
    }
    
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>