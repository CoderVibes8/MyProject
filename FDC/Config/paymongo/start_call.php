<?php
session_start();
require '../../config/conn.config.php';

$appID = "vpaas-magic-cookie-8c5653176c1d40dc941beeae8dbeabb4";
$kid       = "vpaas-magic-cookie-8c5653176c1d40dc941beeae8dbeabb4/7c3d18";
$appSecret = file_get_contents(__DIR__ . '/../jaas_private_key.pem');

$consultationId = $_SESSION['consultation_id'] ?? 0;
if ($consultationId > 0) {
    $stmt = $conn->prepare("UPDATE doctor_consultation SET doctor_joined = 1, oncall = 1 WHERE id = ?");
    $stmt->execute([$consultationId]);
    $_SESSION['consultation_id'] = $consultationId;
}

//Retrieving Doc_id
try{
    $doc = $conn->prepare("SELECT doc_id FROM doctor_consultation WHERE id = ?");
    $doc->execute([$consultationId]);
    $docId = $doc->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Error starting consultation: " . $e->getMessage());
}

$docId = $docId['doc_id'] ?? 0;

try{
    $get_doc = $conn->prepare("SELECT firstname, lastname, email FROM doctor_personal_info WHERE doc_id = ?");
    $get_doc->execute([$docId]);
    $doctor = $get_doc->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Error starting consultation: " . $e->getMessage());
}

$name = $doctor['firstname'] . ' ' . $doctor['lastname'];
$email = $doctor['email'];

$room = "HealthNetRoom" . $consultationId;


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
            "name" => "Dr. " . $name,
            "email" => $email,
            "moderator" => true
        ]
    ]
];

$jwt = JWT::encode(
    $payload,
    $appSecret,
    'RS256',
    $kid
);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Start Consultation - HealthNet</title>
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
            fetch('../../auth/doctor_auth/end_call.php?consultation_id=<?= $consultationId ?>')
                .then(() => {
                    window.location.href = '../../doctor/dashboard.doctor.php';
                })
                .catch(() => {
                    window.location.href = '../../doctor/dashboard.doctor.php';
                });
        });
    </script>
</body>

</html>