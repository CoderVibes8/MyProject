<?php
session_name("doctor_session");
session_start();
require_once "../Config/conn.config.php";

$doc_id = $_SESSION['doc_id'];

try {
    $doctor = $conn->prepare("SELECT * FROM doctor_acc_creation WHERE doc_id = ? ");
    $doctor->execute([$doc_id]);
    $doctorAcc = $doctor->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once "../Includes/Personnel_Head.php"; ?>

<body style="background-color: #e8f1fa;">
    <section class="section dashboard">
        <div class="card border-0">
            <div class="card-body card-body-custom text-center">
                <h5 class="card-title fw-bold mb-3" id="otpModalLabel" style="color: #012970;">🔒 Password Change</h5>

                <form action="../auth/Personnel/update_password.doctor.php" method="POST" class="text-left">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($doctorAcc['email']); ?>" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Current Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="currentPassword" placeholder="Enter your current password">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary toggle-password" type="button" onclick="togglePassword('currentPassword')">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="newPassword" name="password" placeholder="Enter your new password" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary toggle-password" type="button" onclick="togglePassword('newPassword')">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="password-strength-meter">
                            <div class="strength-segment" id="strength-1"></div>
                            <div class="strength-segment" id="strength-2"></div>
                            <div class="strength-segment" id="strength-3"></div>
                            <div class="strength-segment" id="strength-4"></div>
                        </div>
                        <div class="password-feedback" id="password-feedback">Password strength</div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm password">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary toggle-password" type="button" onclick="togglePassword('confirmPassword')">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="password-match" id="password-match-error">Passwords do not match</div>
                    </div>

                    <div class="modal-footer border-0 d-flex justify-content-center p-0">
                        <button type="submit" class="btn btn-primary w-100" name="update_doctor_pass">Update Password</button>
                    </div>
                </form>

                <?php if (isset($_SESSION['otp_message'])): ?>
                    <div class="mt-3 text-danger">
                        <?= $_SESSION['otp_message'];
                        unset($_SESSION['otp_message']); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>



    <!-- ======= Footer ======= -->
    <?php include_once "../Includes/Footer.php"; ?>
    <!-- End Footer -->


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

    <!-- Script For Tailwind -->
    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = input.nextElementSibling;
            const icon = button.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Password strength checker
        document.getElementById('newPassword').addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            const feedback = document.getElementById('password-feedback');

            // Reset all strength indicators
            for (let i = 1; i <= 4; i++) {
                document.getElementById(`strength-${i}`).className = 'h-1 w-1/4 rounded bg-gray-200';
            }

            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/\d/)) strength++;
            if (password.match(/[^a-zA-Z\d]/)) strength++;

            // Update strength meter
            for (let i = 1; i <= strength; i++) {
                const element = document.getElementById(`strength-${i}`);
                if (strength === 1) element.className = 'h-1 w-1/4 rounded bg-red-500';
                else if (strength === 2) element.className = 'h-1 w-1/4 rounded bg-orange-500';
                else if (strength === 3) element.className = 'h-1 w-1/4 rounded bg-yellow-500';
                else if (strength === 4) element.className = 'h-1 w-1/4 rounded bg-green-500';
            }

            // Update feedback text
            if (password.length === 0) {
                feedback.textContent = 'Password strength';
                feedback.className = 'text-xs text-gray-500 mt-1';
            } else if (strength < 2) {
                feedback.textContent = 'Weak password';
                feedback.className = 'text-xs text-red-500 mt-1';
            } else if (strength < 4) {
                feedback.textContent = 'Moderate password';
                feedback.className = 'text-xs text-yellow-600 mt-1';
            } else {
                feedback.textContent = 'Strong password';
                feedback.className = 'text-xs text-green-500 mt-1';
            }
        });

        // Check if passwords match
        document.getElementById('confirmPassword').addEventListener('input', function() {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = this.value;
            const errorElement = document.getElementById('password-match-error');

            if (confirmPassword && newPassword !== confirmPassword) {
                errorElement.classList.remove('hidden');
            } else {
                errorElement.classList.add('hidden');
            }
        });

        // Form submission
        document.getElementById('passwordChangeForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const errorMessage = document.getElementById('error-message');
            const successMessage = document.getElementById('success-message');

            // Reset messages
            errorMessage.classList.add('hidden');
            successMessage.classList.add('hidden');

            // Validate form
            if (!currentPassword || !newPassword || !confirmPassword) {
                errorMessage.textContent = 'Please fill in all fields';
                errorMessage.classList.remove('hidden');
                return;
            }

            if (newPassword !== confirmPassword) {
                errorMessage.textContent = 'New password and confirmation do not match';
                errorMessage.classList.remove('hidden');
                return;
            }

            // Simulate successful password change
            successMessage.textContent = 'Password updated successfully!';
            successMessage.classList.remove('hidden');

            // Reset form
            this.reset();

            // Reset strength meter
            for (let i = 1; i <= 4; i++) {
                document.getElementById(`strength-${i}`).className = 'h-1 w-1/4 rounded bg-gray-200';
            }
            document.getElementById('password-feedback').textContent = 'Password strength';
            document.getElementById('password-feedback').className = 'text-xs text-gray-500 mt-1';
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#appointmentForm').submit(function(e) {
                e.preventDefault();
                $.post('book_appointment.php', $(this).serialize(), function(response) {
                    let res = JSON.parse(response);
                    if (res.status === 'success') {
                        $('#otpModal').show();
                    } else {
                        alert(res.message);
                    }
                });
            });

            $('#verifyOtp').click(function() {
                let otp = $('#otp').val();
                $.post('verify_otp.php', {
                    otp: otp
                }, function(response) {
                    let res = JSON.parse(response);
                    if (res.status === 'success') {
                        alert("Appointment confirmed!");
                        window.location.href = 'dashboard.php';
                    } else {
                        alert("Invalid OTP.");
                    }
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



    <?php
    include_once "../Includes/SweetAlert.php";
    ?>


</body>

</html>