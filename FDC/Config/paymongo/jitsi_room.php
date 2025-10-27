<?php
session_start();
require '../../config/conn.config.php';

// --- Jitsi App Info ---
$appID     = "vpaas-magic-cookie-8c5653176c1d40dc941beeae8dbeabb4"; // Your JaaS App ID
$kid       = "vpaas-magic-cookie-8c5653176c1d40dc941beeae8dbeabb4/7c3d18"; // Replace with your real KID
$privateKey = file_get_contents(__DIR__ . '/../jaas_private_key.pem'); // RS256 private key

// --- Room Name (must match doctor's room) ---
$consultationId = $_SESSION['consultation_id'] ?? 0;

//Retrieving User_id
try {
    $user = $conn->prepare("SELECT user_id FROM doctor_consultation WHERE id = ?");
    $user->execute([$consultationId]);
    $userId = $user->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Error starting consultation: " . $e->getMessage());
}

$userId = $userId['user_id'] ?? 0;

try {
    $get_user = $conn->prepare("SELECT first_name, last_name, email FROM user_patient WHERE user_id = ?");
    $get_user->execute([$userId]);
    $user = $get_user->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Error starting consultation: " . $e->getMessage());
}

$name = $user['first_name'] . ' ' . $user['last_name'];
$email = $user['email'];

$room = "HealthNetRoom" . $consultationId;

// --- Generate JWT ---
require '../../vendor/autoload.php';

use Firebase\JWT\JWT;

$payload = [
    "aud" => "jitsi",
    "iss" => "chat",
    "sub" => $appID,
    "room" => $room,
    "exp" => time() + 3600,
    "nbf" => time() - 10,
    "context" => [
        "user" => [
            "name" => $name,
            "email" => $email,
            "moderator" => false // user is NOT a moderator
        ]
    ]
];

// Add "kid" into JWT header
$jwt = JWT::encode($payload, $privateKey, 'RS256', $kid);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Join Consultation - HealthNet</title>
    <script src="https://8x8.vc/external_api.js"></script>
    <style>
        body {
            margin: 0;
            background: #f9f9f9;
            font-family: Arial, sans-serif;
        }

        #jitsi {
            height: 100vh;
        }
    </style>
</head>

<body>
    <div id="jitsi"></div>

    <script>
        const appID = "<?= $appID ?>";
        const room = "<?= $room ?>";
        const jwt = "<?= $jwt ?>";
        const name = "<?= htmlspecialchars($name) ?>";

        const options = {
            roomName: `${appID}/${room}`,
            jwt: jwt,
            width: "100%",
            height: "100%",
            parentNode: document.querySelector('#jitsi'),
            userInfo: {
                displayName: name
            }
        };

        const api = new JitsiMeetExternalAPI("8x8.vc", options);

        api.addEventListener('readyToClose', () => {
            fetch('../../auth/patient_auth/add_patient_records.php?consultation_id=<?= $consultationId ?>')
                .then(() => {
                    window.location.href = '../../patient/ratings.php?consultation_id=<?= $consultationId ?>';
                })
                .catch(() => {
                    window.location.href = '../../patient/ratings.php?consultation_id=<?= $consultationId ?>';
                });
        });
    </script>
</body>

</html>