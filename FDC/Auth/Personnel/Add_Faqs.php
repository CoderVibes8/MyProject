<?php
require_once '../../Config/conn.config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question = trim($_POST['question'] ?? '');
    $answer = trim($_POST['answer'] ?? '');

    if (!empty($question) && !empty($answer)) {
        try {
            $stmt = $conn->prepare("INSERT INTO faqs (question, answer, created_at) VALUES (:question, :answer, NOW())");
            $stmt->execute([
                ':question' => $question,
                ':answer' => $answer
            ]);

            $_SESSION["message"] = [
                "title" => "Added!",
                "message" => "FAQ added successfully.",
                "type" => "success"
            ];
            header("Location: ../../Personnel/dashboard.personnel.php?success=1");
            exit;
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        }
    } else {
        echo "Please fill in both fields.";
    }
}
