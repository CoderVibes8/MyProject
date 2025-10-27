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

<body>
    <div class="registration-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="mb-0"><i class="fas fa-user-md me-2"></i>Profile Set Up</h3>
                            <p class="mb-0 mt-2">Create your medical professional account</p>
                        </div>
                        <div class="card-body">
                            <form action="../Auth/Personnel/doctor_information.personnel.php" id="doctorRegistrationForm" class="needs-validation" method="POST" enctype="multipart/form-data">
                                <!-- Profile Photo -->
                                <div class="text-center mb-4">
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type="file" id="profileImageUpload" name="profile" accept="image/*" />
                                            <label for="profileImageUpload"><i class="fas fa-pencil-alt"></i></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="profileImagePreview"></div>
                                        </div>
                                    </div>
                                    <p class="form-text">Click the edit icon to upload your profile photo</p>
                                </div>

                                <!-- Personal Information -->
                                <h5 class="section-title">Personal Information</h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="firstName" class="form-label">
                                            <i class="fas fa-user me-2"></i>First Name<span class="required-asterisk">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="firstName" name="fn" required>
                                        <div class="invalid-feedback">Please enter your first name.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="lastName" class="form-label">
                                            <i class="fas fa-user me-2"></i>Last Name<span class="required-asterisk">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="lastName" name="ln" required>
                                        <div class="invalid-feedback">Please enter your last name.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope me-2"></i>Email Address<span class="required-asterisk">*</span>
                                        </label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                        <div class="invalid-feedback">Please enter a valid email address.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">
                                            <i class="fas fa-phone me-2"></i>Phone Number(Optional)<span class="required-asterisk">*</span>
                                        </label>
                                        <input type="tel" class="form-control" id="phone" name="phone" required>
                                        <div class="invalid-feedback">Please enter your phone number.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="dateOfBirth" class="form-label">
                                            <i class="fas fa-calendar-alt me-2"></i>Date of Birth
                                        </label>
                                        <input type="date" class="form-control" id="dateOfBirth" name="dob" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="gender" class="form-label">
                                            <i class="fas fa-venus-mars me-2"></i>Gender
                                        </label>
                                        <select class="form-select" id="gender" name="gender">
                                            <option value="" selected disabled>Select gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="prefer-not-to-say">Prefer not to say</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Professional Information -->
                                <!-- <h5 class="section-title">Professional Information</h5>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="licenseNumber" class="form-label">
                                            <i class="fas fa-id-card me-2"></i>Medical License Number<span class="required-asterisk">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="licenseNumber" name="licenseNumber" pattern="[A-Za-z0-9]{5,20}" required>
                                        <div class="invalid-feedback">Please enter a valid medical license number.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="licenseExpiry" class="form-label">
                                            <i class="fas fa-calendar-check me-2"></i>License Expiry Date<span class="required-asterisk">*</span>
                                        </label>
                                        <input type="date" class="form-control" id="licenseExpiry" name="licenseExpiry" required>
                                        <div class="invalid-feedback">Please enter your license expiry date.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="specialization" class="form-label">
                                            <i class="fas fa-stethoscope me-2"></i>Primary Specialization<span class="required-asterisk">*</span>
                                        </label>
                                        <select class="form-select" id="specialization" name="specialization" required>
                                            <option value="" disabled selected>Select specialization</option>
                                            <option value="cardiology">Cardiology</option>
                                            <option value="dermatology">Dermatology</option>
                                            <option value="endocrinology">Endocrinology</option>
                                            <option value="gastroenterology">Gastroenterology</option>
                                            <option value="neurology">Neurology</option>
                                            <option value="obstetrics">Obstetrics & Gynecology</option>
                                            <option value="oncology">Oncology</option>
                                            <option value="ophthalmology">Ophthalmology</option>
                                            <option value="orthopedics">Orthopedics</option>
                                            <option value="pediatrics">Pediatrics</option>
                                            <option value="psychiatry">Psychiatry</option>

                                            <h5 class="section-title">Hospital/Clinic Information</h5>
                                            <div class="row g-3 mb-4">
                                                <div class="col-md-6">
                                                    <label for="hospitalName" class="form-label">
                                                        <i class="fas fa-hospital me-2"></i>Hospital/Clinic Name
                                                    </label>
                                                    <input type="text" class="form-control" id="hospitalName">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="hospitalAddress" class="form-label">
                                                        <i class="fas fa-map-marker-alt me-2"></i>Hospital/Clinic Address
                                                    </label>
                                                    <input type="text" class="form-control" id="hospitalAddress">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="hospitalCity" class="form-label">
                                                        <i class="fas fa-city me-2"></i>City
                                                    </label>
                                                    <input type="text" class="form-control" id="hospitalCity">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="hospitalState" class="form-label">
                                                        <i class="fas fa-map me-2"></i>State/Province
                                                    </label>
                                                    <input type="text" class="form-control" id="hospitalState">
                                                </div>
                                            </div> -->

                                <!-- Account Security -->
                                <!-- <h5 class="section-title">Account Security</h5>
                                            <div class="row g-3 mb-4">
                                                <div class="col-md-6">
                                                    <label for="password" class="form-label">
                                                        <i class="fas fa-lock me-2"></i>Password<span class="required-asterisk">*</span>
                                                    </label>
                                                    <input type="password" class="form-control" id="password" name="pw" required>
                                                    <div class="password-strength-meter mt-2">
                                                        <div class="strength-segment" id="strength-1"></div>
                                                        <div class="strength-segment" id="strength-2"></div>
                                                        <div class="strength-segment" id="strength-3"></div>
                                                        <div class="strength-segment" id="strength-4"></div>
                                                    </div>
                                                    <div class="password-feedback" id="password-feedback">Password strength</div>
                                                    <div class="invalid-feedback">Please enter a password.</div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="confirmPassword" class="form-label">
                                                        <i class="fas fa-lock me-2"></i>Confirm Password<span class="required-asterisk">*</span>
                                                    </label>
                                                    <input type="password" class="form-control" id="confirmPassword" name="cpw" required>
                                                    <div class="invalid-feedback" id="password-match-feedback">Passwords do not match.</div>
                                                </div>
                                            </div> -->

                                <!-- Terms and Conditions -->
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="termsCheck" required>
                                        <label class="form-check-label" for="termsCheck">
                                            I agree to the <a href="#" class="text-primary">Terms of Service</a> and <a href="#" class="text-primary">Privacy Policy</a>
                                        </label>
                                        <div class="invalid-feedback">You must agree before submitting.</div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary px-5" name="setup_doctor_info">
                                        <i class="fas fa-user-plus me-2"></i> Save Changes
                                    </button>
                                    <a href="dashboard.doctor.php" class="btn btn-primary">
                                        Skip &nbsp;<i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





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

    <script>
        document.getElementById("profileImageUpload").addEventListener("change", function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById("profileImagePreview").style.backgroundImage = `url('${e.target.result}')`;
                }
                reader.readAsDataURL(file);
            }
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