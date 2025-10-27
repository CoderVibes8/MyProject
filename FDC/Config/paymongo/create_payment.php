<?php
session_start();
require '../conn.config.php';
require_once 'vendor/autoload.php';
require_once 'paymongo_config.php';

use GuzzleHttp\Client;

header('Content-Type: application/json'); // Tell browser this is JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctorId = $_POST['doctor_id'];
    $userId = $_POST['user_id'];
    $customerName = $_POST['customer_name'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $docName = $_POST['doctor_name'];
    $docNum = $_POST['doctor_number'];
    $totalAmount = $_POST['total_amount'];
    $taxAmount = $_POST['tax_amount'];

    $amountInCentavos = $totalAmount * 100;

    $client = new Client();



    try {
        // Save consultation to DB
        $stmt = $conn->prepare("
            INSERT INTO doctor_consultation 
            (doc_id, user_id, patient_name, amount, tax, total_amount, description, doctor_name, doctor_num, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
        ");
        $stmt->execute([
            $doctorId,
            $userId,
            $customerName,
            $amount,
            $taxAmount,
            $totalAmount,
            $description,
            $docName,
            $docNum
        ]);

        $consultationId = $conn->lastInsertId();

        $_SESSION['consultation_id'] = $consultationId;

        $description = "You have an online consultation with $docName. Your consultation id : $consultationId";
        $link = "http://localhost/Capstone2/patient/upcoming_consult.patient.php?consultation_id=$consultationId";
        $insertNotif  = $conn->prepare("INSERT INTO user_notif (doc_id, user_id, description, link) VALUES (?, ?, ?, ?)");
        $insertNotif->execute([$docId, $userId, $description, $link]);

        // Create GCash Source
        $response = $client->request('POST', 'https://api.paymongo.com/v1/sources', [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode('sk_test_K27r3XkaQvQYjpMYNGS7bmcG:'), // secure this key
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode([
                'data' => [
                    'attributes' => [
                        'amount' => $amountInCentavos,
                        'redirect' => [
                            'success' => 'http://localhost/Capstone2/patient/upcoming_consult.patient.php?doctor_id=' . $doctorId . '&consultation_id=' . $consultationId,
                            'failed' => 'http://localhost/Capstone2/patient/payment_failed.php',
                        ],
                        'type' => 'gcash',
                        'currency' => 'PHP'
                    ]
                ]
            ])
        ]);

        $body = json_decode($response->getBody(), true);
        $checkoutUrl = $body['data']['attributes']['redirect']['checkout_url'];

        // Store in session if needed
        $_SESSION['checkout_url'] = $checkoutUrl;
        $_SESSION['doctor_id'] = $doctorId;
        $_SESSION['user_id'] = $userId;
        $_SESSION['customer_name'] = $customerName;
        $_SESSION['amount'] = $amount;
        $_SESSION['description'] = $description;
        $_SESSION['doctor_name'] = $docName;
        $_SESSION['doctor_number'] = $docNum;
        $_SESSION['total_amount'] = $totalAmount;
        $_SESSION['tax_amount'] = $taxAmount;

        // ✅ Return JSON (no redirect)
        echo json_encode([
            'success' => true,
            'checkout_url' => $checkoutUrl
        ]);
        exit;
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
        exit;
    }
}
