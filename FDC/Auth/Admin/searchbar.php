<?php
include_once "../Config/conn.config.php";

$search = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
    $search = trim($_POST["search"]);
}

$sql = "SELECT * FROM clinic_account WHERE clinic_name LIKE ? OR email LIKE ? OR contact_number LIKE ? OR clinic_address LIKE ? OR owner_name LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$search%";
$stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
$clinics = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
