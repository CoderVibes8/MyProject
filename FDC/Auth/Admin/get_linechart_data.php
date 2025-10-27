<?php
require_once '../../Config/conn.config.php';

// ✅ Fetch appointment counts per month per specialty for a given year
if (isset($_GET['year'])) {
    $year = $_GET['year'];

    $sql = "
        SELECT d.specialty, MONTH(a.appointment_date) AS month, COUNT(a.appointment_id) AS total
        FROM appointments a
        LEFT JOIN doctor_info d ON a.doc_id = d.doctor_id
        WHERE YEAR(a.appointment_date) = :year
        GROUP BY d.specialty, MONTH(a.appointment_date)
        ORDER BY d.specialty, month
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['year' => $year]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

// ✅ Fetch available years
if (isset($_GET['years'])) {
    $sql = "SELECT DISTINCT YEAR(appointment_date) AS year 
            FROM appointments 
            ORDER BY year DESC";
    $stmt = $conn->query($sql);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}
