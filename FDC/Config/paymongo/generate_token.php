<?php
session_start();
require '../../vendor/autoload.php';

use Firebase\JWT\JWT;

date_default_timezone_set('Asia/Manila');

$roomName  = $_GET['room']  ?? '';
$userName  = $_GET['name']  ?? '';
$userEmail = $_SESSION['email'] ?? '';

if (!$roomName || !$userName) {
    http_response_code(400);
    echo json_encode(["error" => "Missing room or name"]);
    exit;
}

$appID   = "vpaas-magic-cookie-8c5653176c1d40dc941beeae8dbeabb4";
$keyID   = $appID . "/7c3d18";
$privateKey = file_get_contents(__DIR__ . '/../jaas_private_key.pem');

if (!$privateKey) {
    http_response_code(500);
    echo json_encode(["error" => "Private key missing"]);
    exit;
}

$now = time();
$isModerator = ($userName === 'Doctor'); // doctor gets moderator rights

$header = [
    "alg" => "RS256",
    "kid" => $keyID,
    "typ" => "JWT"
];

$payload = [
    "aud" => "jitsi",
    "iss" => "chat",
    "sub" => $appID,         // ✅ must be appID
    "room" => $roomName,     // ✅ just raw room name (ex: HealthNetRoom12)
    "exp" => $now + 3600,
    "nbf" => $now,
    "context" => [
        "user" => [
            "name" => $userName,
            "email" => $userEmail,
            "moderator" => $isModerator
        ],
        "features" => [
            "recording"     => $isModerator,
            "livestreaming" => $isModerator,
            "transcription" => $isModerator,
            "outbound-call" => $isModerator
        ]
    ]
];

$jwt = JWT::encode($payload, $privateKey, 'RS256', null, $header);

header('Content-Type: application/json');
echo json_encode(["jwt" => $jwt]);
