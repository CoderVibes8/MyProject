<?php
require_once("../config/conn.config.php");
session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: ../index.php");
  exit();
}

$admin_id = $_SESSION['admin_id'];


try {
  $admin = $conn->prepare("SELECT * FROM admin WHERE admin_id = ? ");
  $admin->execute([$admin_id]);
  $adminAcc = $admin->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

$full_name = $adminAcc['first_name'] . ' ' . $adminAcc['last_name'];



try {
  $stmt = $conn->prepare("SELECT * FROM user_patient ");
  $stmt->execute();
  $approved = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo ("Error" . $e->getMessage());
}

if (isset($_GET['id'])) {
  $_SESSION['user_id'] = $_GET['id'];
  $user_id = $_SESSION['user_id'];
  try {
    $user = $conn->prepare("SELECT up.*, uc.profile_picture FROM user_patient up LEFT JOIN user_credentials uc ON up.user_id = uc.user_id WHERE up.user_id = ?");
    $user->execute([$user_id]);
    $uProfile = $user->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}




//total patients
try {
  $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM user_patient");
  $stmt->execute();

  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $total_patients = $result['total'];
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

//total doctors
try {
  $doctor = $conn->prepare("SELECT COUNT(*) AS total FROM doctor_acc_creation");
  $doctor->execute();
  $result = $doctor->fetch(PDO::FETCH_ASSOC);
  $total_doctors = $result['total'];
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

//overall doctor retrieval
try {
  $doctorr = $conn->prepare("SELECT dpi.*, dac.status FROM doctor_acc_creation dac LEFT JOIN doctor_personal_info dpi ON dac.doc_id = dpi.doc_id");
  $doctorr->execute();
  $overallDoctorRetrieval = $doctorr->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo 'Error: ' . $e->getMessage();
}





//TOTAL APPOINTMENTS
$stmt = $conn->query("SELECT COUNT(*) as total FROM appointments");
$totalAppointments = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Get current week and last week totals
$currentWeek = $conn->query("
    SELECT COUNT(*) as cnt 
    FROM appointments 
    WHERE YEARWEEK(appointment_date, 1) = YEARWEEK(CURDATE(), 1)
")->fetch(PDO::FETCH_ASSOC)['cnt'];

$lastWeek = $conn->query("
    SELECT COUNT(*) as cnt 
    FROM appointments 
    WHERE YEARWEEK(appointment_date, 1) = YEARWEEK(CURDATE(), 1) - 1
")->fetch(PDO::FETCH_ASSOC)['cnt'];

// Calculate trend
$trendPercent = 0;
$trendDirection = "neutral";

if ($lastWeek > 0) {
  $trendPercent = (($currentWeek - $lastWeek) / $lastWeek) * 100;
  $trendDirection = $trendPercent >= 0 ? "up" : "down";
}


//TOTAL USERS
// Get total users
$stmt = $conn->query("SELECT COUNT(*) as total FROM user_patient");
$totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Get today's new users
$today = $conn->prepare("SELECT COUNT(*) as cnt FROM user_patient WHERE DATE(created_at) = CURDATE()");
$today->execute();
$todayUsers = $today->fetch(PDO::FETCH_ASSOC)['cnt'];

// Calculate percentage
$trendPercent_user = 0;
$trendDirection_user = "neutral";

if ($totalUsers > 0) {
  $trendPercent_user = ($todayUsers / $totalUsers) * 100;
  $trendDirection_user = $trendPercent_user >= 0 ? "up" : "down";
}




//DOCTORS PERFORMANCE
$sql = "
    SELECT d.doctor_id, CONCAT('Dr. ', d.firstname, ' ', d.lastname) AS name, COUNT(a.appointment_id) as appt_count
    FROM doctor_info d
    LEFT JOIN appointments a ON d.doctor_id = a.doc_id
    GROUP BY d.doctor_id, d.firstname, d.lastname
    ORDER BY appt_count DESC
";
$stmt = $conn->query($sql);
$doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total appointments
$totalAppts = array_sum(array_column($doctors, "appt_count"));

// Assign colors (cycle through if many doctors)
$colors = ["fill-green", "fill-blue", "fill-amber", "fill-red"];



//GET ALL DOCTORS
$stmt = $conn->query("SELECT COUNT(*) as total FROM doctor_info");
$totalDoctors = $stmt->fetch(PDO::FETCH_ASSOC)['total'];




//GET TOTAL REVENUE
$stmt = $conn->query("
    SELECT COALESCE(SUM(d.price), 0) as total
    FROM appointment_schedule a
    JOIN doctor_info d ON a.doc_id = d.doctor_id
    WHERE a.stat = 'Done'
");
$totalRevenue = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// THIS WEEK'S REVENUE (done appointments only)
$currentWeekStmt = $conn->query("
    SELECT COALESCE(SUM(d.price), 0) as total
    FROM appointment_schedule a
    JOIN doctor_info d ON a.doc_id = d.doctor_id
    WHERE a.stat = 'Done'
      AND YEARWEEK(a.appointment_date, 1) = YEARWEEK(CURDATE(), 1)
");
$currentWeekRevenue = $currentWeekStmt->fetch(PDO::FETCH_ASSOC)['total'];

// LAST WEEK'S REVENUE (done appointments only)
$lastWeekStmt = $conn->query("
    SELECT COALESCE(SUM(d.price), 0) as total
    FROM appointment_schedule a
    JOIN doctor_info d ON a.doc_id = d.doctor_id
    WHERE a.stat = 'Done'
      AND YEARWEEK(a.appointment_date, 1) = YEARWEEK(CURDATE(), 1) - 1
");
$lastWeekRevenue = $lastWeekStmt->fetch(PDO::FETCH_ASSOC)['total'];

// CALCULATE TREND
$trendPercent_rev = 0;
$trendDirection_rev = "neutral";

if ($lastWeekRevenue > 0) {
  $trendPercent_rev = (($currentWeekRevenue - $lastWeekRevenue) / $lastWeekRevenue) * 100;
  $trendDirection_rev = $trendPercent_rev >= 0 ? "up" : "down";
}



//TODAY'S REVENUE
$totalStmt = $conn->query("
    SELECT COALESCE(SUM(d.price), 0) as total
    FROM appointment_schedule a
    JOIN doctor_info d ON a.doc_id = d.doctor_id
    WHERE a.stat = 'Done'
");
$totalRev = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];

// TODAY'S REVENUE (done appointments only)
$todayStmt = $conn->query("
    SELECT COALESCE(SUM(d.price), 0) as total
    FROM appointment_schedule a
    JOIN doctor_info d ON a.doc_id = d.doctor_id
    WHERE a.stat = 'Done'
      AND DATE(a.appointment_date) = CURDATE()
");
$todaysRevenue = $todayStmt->fetch(PDO::FETCH_ASSOC)['total'];

// CALCULATE TREND (today vs total)
$trendPercent_today = 0;
$trendDirection_today = "neutral";

if ($totalRevenue > 0) {
    $trendPercent_today = ($todaysRevenue / $totalRev) * 100;
    $trendDirection_today = $trendPercent_today > 0 ? "up" : "down";
}












//Doctor Info
try {
  $docInfo = $conn->prepare("SELECT * FROM doctor_info");
  $docInfo->execute();
  $dInfo = $docInfo->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo 'Error: ' . $e->getMessage();
}
