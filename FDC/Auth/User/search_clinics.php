<?php
session_name("patient_session");
session_start();
include_once "../../Config/conn.config.php";



try {
    $query = isset($_GET['q']) ? trim($_GET['q']) : '';

    if (!empty($query)) {
        $stmt = $conn->prepare("
            SELECT DISTINCT ca.clinic_id, ca.clinic_name, ca.clinic_address, cc.profile_picture, ca.owner_name
            FROM clinic_account ca
            LEFT JOIN clinic_credentials cc ON ca.clinic_id = cc.clinic_id
            LEFT JOIN clinic_offers co ON ca.clinic_id = co.clinic_id
            WHERE (ca.clinic_name LIKE :query OR ca.clinic_address LIKE :query OR ca.owner_name LIKE :query OR co.service LIKE :query)
            AND ca.status = 'approved'
        ");
        $stmt->execute(['query' => "%$query%"]);
    } else {
        $stmt = $conn->prepare("
            SELECT DISTINCT ca.clinic_id, ca.clinic_name, ca.clinic_address, cc.profile_picture, ca.owner_name
            FROM clinic_account ca
            LEFT JOIN clinic_credentials cc ON ca.clinic_id = cc.clinic_id
            LEFT JOIN clinic_offers co ON ca.clinic_id = co.clinic_id
            WHERE ca.status = 'approved'
        ");
        $stmt->execute();
    }


    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        foreach ($results as $clinic) {
            $profile_picture = !empty($clinic['profile_picture'])
                ? "../uploads/" . htmlspecialchars($clinic['profile_picture'])
                : "../uploads/user.png";
?>
            <div class="col clinic-card">
                <div class="card h-100 text-center">
                    <img src="<?php echo $profile_picture; ?>" class="card-img-top" alt="Clinic Image">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold flex-grow-1 d-flex align-items-center justify-content-center">
                            <?= htmlspecialchars($clinic['clinic_name']); ?>
                        </h5>
                        <p class="card-text"><?= htmlspecialchars($clinic['clinic_address']); ?></p>
                        <a href="clinic_page.patient.php?id=<?= $clinic['clinic_id']; ?>" class="btn btn-primary mt-auto">
                            See Clinic
                        </a>
                    </div>
                </div>
            </div>
<?php
        }
    } else {
        echo "<div class='col-12 text-center'><p class='text-muted'>No clinics found.</p></div>";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>