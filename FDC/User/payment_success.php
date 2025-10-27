<?php
session_start();
require '../Config/conn.config.php';

$consultationId = $_SESSION['consultation_id'] ?? 0;
if ($consultationId > 0) {
    $conn->query("UPDATE doctor_consultation SET status = 'paid' WHERE id = $consultationId");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Success</title>
    <script>
        function checkDoctorJoined() {
            fetch('../config/paymongo/check_doctor_joined.php?consultation_id=<?= $consultationId ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.doctor_joined === 1) {
                        document.getElementById('joinBtn').disabled = false;
                        document.getElementById('status').textContent = "Doctor has joined. You can now join the consultation.";
                    } else {
                        setTimeout(checkDoctorJoined, 3000);
                    }
                });
        }

        window.onload = checkDoctorJoined;
    </script>
</head>
<body>
    <h2>Payment Successful!</h2>
    <p id="status">Waiting for doctor to join the consultation room...</p>
    <form action="../config/paymongo/jitsi_room.php" method="GET">
        <input type="hidden" name="room" value="HealthNetRoom<?= $consultationId ?>">
        <button type="submit" id="joinBtn" disabled>Join Consultation</button>
    </form>
</body>
</html>
