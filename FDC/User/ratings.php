<?php
session_name('patient_session');
session_start();
require_once "../Config/conn.config.php";


if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$consultationId = $_GET['consultation_id'];

try {
    $rate = $conn->prepare("SELECT id, doc_id, user_id FROM doctor_consultation WHERE id = :consultation_id");
    $rate->bindParam(':consultation_id', $consultationId);
    $rate->execute();
    $doc_rate = $rate->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once "../Includes/Head.php"; ?>

<style>
    /* Review Form */
    .review-form {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .star-rating {
        font-size: 1.5rem;
        cursor: pointer;
        color: #ccc;
    }

    .star-rating .star:hover,
    .star-rating .star.active {
        color: #ffb703;
    }
</style>

<body>

    <section class="section dashboard" style="display: flex; justify-content: center; align-items: center; min-height: 90vh; background: #f9fafb;">
        <div class="review-card" style="
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        max-width: 600px;
        width: 100%;
        text-align: center;
    ">
            <!-- Consultation Info -->
            <div class="consultation-info" style="margin-bottom: 20px;">
                <h3 style="margin: 0; font-size: 1.5rem; color: #333;">
                    Consultation with Dr. <span id="doctorName">Dentist</span>
                </h3>
            </div>

            <!-- Review Form -->
            <div class="review-form">
                <!-- Rating -->
                <div class="form-group" style="margin-bottom: 15px;">
                    <label class="form-label" style="display:block; font-weight: 600; margin-bottom: 8px;">Rating</label>
                    <div class="star-rating" id="starRating" style="font-size: 2rem; color: gray; cursor: pointer;">
                        <span class="star" data-rating="1">★</span>
                        <span class="star" data-rating="2">★</span>
                        <span class="star" data-rating="3">★</span>
                        <span class="star" data-rating="4">★</span>
                        <span class="star" data-rating="5">★</span>
                    </div>
                </div>

                <!-- Review -->
                <div class="form-group" style="margin-bottom: 15px; text-align: left;">
                    <label for="reviewText" class="form-label" style="display:block; font-weight: 600; margin-bottom: 8px;">Your Review</label>
                    <textarea id="reviewText" rows="4" class="form-textarea" placeholder="Share your experience..."
                        style="width:100%; padding:10px; border:1px solid #ccc; border-radius:8px; font-size:1rem;"></textarea>
                </div>

                <!-- Actions -->
                <div class="form-actions" style="display:flex; justify-content:center; gap:10px;">
                    <button class="btn btn-secondary" onclick="clearReview()" style="padding:10px 20px; border:none; border-radius:8px; background:#e5e7eb; cursor:pointer;">
                        Clear
                    </button>
                    <button class="btn btn-primary" onclick="submitReview()" style="padding:10px 20px; border:none; border-radius:8px; background:#2563eb; color:#fff; cursor:pointer;">
                        Submit Review
                    </button>
                </div>
            </div>
        </div>
    </section>

    <script>
        let selectedRating = 0;

        // Handle star click
        document.querySelectorAll(".star").forEach(star => {
            star.addEventListener("click", function() {
                selectedRating = this.dataset.rating;
                updateStars(selectedRating);
            });
        });

        function updateStars(rating) {
            document.querySelectorAll(".star").forEach(star => {
                star.style.color = (star.dataset.rating <= rating) ? "gold" : "gray";
            });
        }

        function clearReview() {
            selectedRating = 0;
            updateStars(0);
            document.getElementById("reviewText").value = "";
        }

        function submitReview() {
            const reviewText = document.getElementById("reviewText").value.trim();
            if (selectedRating === 0) {
                alert("Please select a rating");
                return;
            }
            if (reviewText === "") {
                alert("Please write a review");
                return;
            }

            // Example values (should be passed dynamically from PHP/session)
            const consultationId = <?php echo json_encode($doc_rate['id']); ?>;
            const doctorId = <?php echo json_encode($doc_rate['doc_id']); ?>;
            const patientId = <?php echo json_encode($doc_rate['user_id']); ?>;

            fetch("../Auth/User/save_review.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        consultation_id: consultationId,
                        doc_id: doctorId,
                        patient_id: patientId,
                        rating: selectedRating,
                        review: reviewText.trim()
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Redirect to dashboard (adjust path if needed)
                        window.location.href = "recent_transaction.php";
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(err => {
                    console.error("Error:", err);
                    alert("Something went wrong. Please try again.");
                });
        }
    </script>






    <?php include_once "../Includes/Footer.php"; ?>

    <!-- Modals -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));

            // Open modal when button is clicked
            document.getElementById("openModalButton").addEventListener("click", function() {
                myModal.show();
            });

            // Close modal manually if needed
            document.getElementById("closeModalButton").addEventListener("click", function() {
                myModal.hide();
            });
        });
    </script>

    <!-- Script for Switching -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var triggerTabList = [].slice.call(document.querySelectorAll('#myTab a'));
            triggerTabList.forEach(function(triggerEl) {
                var tabTrigger = new bootstrap.Tab(triggerEl);
                triggerEl.addEventListener('click', function(event) {
                    event.preventDefault();
                    tabTrigger.show();
                });
            });
        });
    </script>



    <!-- Bootstrap JS (Ensure it's included) -->

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <!-- For Bootstrap 4 (Optional) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>

    <!-- Vendor JS Files -->
    <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/chart.js/chart.umd.js"></script>
    <script src="../assets/vendor/echarts/echarts.min.js"></script>
    <script src="../assets/vendor/quill/quill.js"></script>
    <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="../assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <?php include_once "../Includes/SweetAlert.php"; ?>

</body>
</html>