<?php
require_once '../../Config/conn.config.php'; 

// ✅ Fetch appointment counts for given year/month
if (isset($_GET['year']) && isset($_GET['month'])) {
    $year = $_GET['year'];
    $month = $_GET['month'];

    $sql = "
        SELECT d.specialty, COUNT(a.appointment_id) AS total
        FROM appointments a
        LEFT JOIN doctor_info d ON a.doc_id = d.doctor_id
        WHERE YEAR(a.appointment_date) = :year
        AND MONTH(a.appointment_date) = :month
        GROUP BY d.specialty
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['year' => $year, 'month' => $month]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

// ✅ Fetch available years
if (isset($_GET['years'])) {
    $sql = "SELECT DISTINCT YEAR(appointment_date) AS year FROM appointments ORDER BY year DESC";
    $stmt = $conn->query($sql);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

// ✅ Fetch available months for a given year
if (isset($_GET['months']) && !empty($_GET['months'])) {
    $year = $_GET['months'];
    $sql = "SELECT DISTINCT MONTH(appointment_date) AS month FROM appointments WHERE YEAR(appointment_date) = :year ORDER BY month";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['year' => $year]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}
