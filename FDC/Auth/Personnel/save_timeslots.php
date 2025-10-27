<?php
session_name("doctor_session");
session_start();
require_once "../../Config/conn.config.php";

if (!isset($_SESSION['doc_id'])) {
    header("Location: ../../index.php");
    exit();
}

$doc_id = $_SESSION['doc_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date = $_POST['slot_date'];
    $start_times = $_POST['start_time'];
    $end_times = $_POST['end_time'];

    if (count($start_times) !== count($end_times)) {

        $_SESSION['message'] = [
            "title" => "Opsss!",
            "message" => "Mismatch in number of time slots.",
            "type" => "error"
        ];

        header("Location: ../../Personnel/add_schedule.personnel.php");
        exit();
    }

    $today = date('Y-m-d');
    $maxDate = date('Y-m-d', strtotime('+2 months'));

    if ($date < $today || $date > $maxDate) {

        $_SESSION['message'] = [
            "title" => "Error!",
            "message" => "Date must be within the current date until the next 2 months only.",
            "type" => "error"
        ];

        header("Location: ../../Personnel/add_schedule.personnel.php");
        exit();
    }


    try {
        $conn->beginTransaction();

        $checkStmt = $conn->prepare("
            SELECT COUNT(*) 
            FROM doctor_timeslots 
            WHERE doc_id = :doc_id 
              AND slot_date = :slot_date 
              AND start_time = :start_time 
              AND end_time = :end_time
        ");

        $insertStmt = $conn->prepare("
            INSERT INTO doctor_timeslots (doc_id, slot_date, start_time, end_time) 
            VALUES (:doc_id, :slot_date, :start_time, :end_time)
        ");

        $skipped = 0;

        for ($i = 0; $i < count($start_times); $i++) {
            $checkStmt->execute([
                ':doc_id'     => $doc_id,
                ':slot_date'  => $date,
                ':start_time' => $start_times[$i],
                ':end_time'   => $end_times[$i]
            ]);

            $exists = $checkStmt->fetchColumn();

            if ($exists == 0) {
                $insertStmt->execute([
                    ':doc_id'     => $doc_id,
                    ':slot_date'  => $date,
                    ':start_time' => $start_times[$i],
                    ':end_time'   => $end_times[$i]
                ]);
            } else {
                $skipped++;
            }
        }

        $conn->commit();

        $_SESSION['message'] = [
            "title" => "Schedule Update",
            "message" => $skipped > 0
                ? "$skipped duplicate time slot" . ($skipped > 1 ? "s" : "") . " skipped. Remaining saved successfully!"
                : "All timeslots saved successfully!",
            "type" => $skipped > 0 ? "warning" : "success"
        ];

        header("Location: ../../Personnel/add_schedule.personnel.php");
        exit();
         
    } catch (PDOException $e) {
        $conn->rollBack();
        echo "Error saving timeslots: " . $e->getMessage();
    }
}
