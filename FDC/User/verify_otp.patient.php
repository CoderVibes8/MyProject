<?php
session_name('patient_session');
session_start();
include_once "../Config/conn.config.php";


if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<?php include_once "../Includes/Head.php"; ?>

<style>
    /* 🎨 OTP Verification Card */
    .section.dashboard {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
    }

    .card {
        max-width: 420px;
        margin: auto;
        border-radius: 16px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        padding: 25px;
    }

    /* 🧩 OTP Inputs */
    .otp-input {
        width: 45px;
        height: 55px;
        text-align: center;
        font-size: 22px;
        font-weight: 600;
        color: #012970;
        border: 2px solid #d0d4dc;
        border-radius: 8px;
        outline: none;
        transition: all 0.2s ease-in-out;
    }

    .otp-input:focus {
        border-color: #4154f1;
        box-shadow: 0 0 8px rgba(65, 84, 241, 0.2);
    }

    /* 🔁 Resend Link */
    .card-text a {
        color: #4154f1;
        text-decoration: none;
        font-weight: 500;
    }

    .card-text a:hover {
        text-decoration: underline;
    }

    /* 🧍‍♂️ Buttons */
    .btn {
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background: #4154f1;
        border: none;
    }

    .btn-primary:hover {
        background: #2e3fc7;
    }

    .btn-secondary {
        background: #ccc;
        border: none;
        color: #333;
    }

    .btn-secondary:hover {
        background: #b5b5b5;
    }

    /* ⚠️ Message */
    .text-danger {
        font-size: 14px;
        font-weight: 500;
    }

    /* 📱 Responsive Design */
    @media (max-width: 480px) {
        .card {
            width: 90%;
            padding: 20px;
        }

        .otp-input {
            width: 40px;
            height: 50px;
            font-size: 18px;
        }

        .btn {
            font-size: 13px;
            padding: 7px 15px;
        }
    }

    @media (min-width: 481px) and (max-width: 768px) {
        .card {
            width: 80%;
        }

        .otp-input {
            width: 42px;
        }
    }

    @media (min-width: 769px) and (max-width: 1020px) {
        .card {
            width: 60%;
        }
    }
</style>

<body>

    <section class="section dashboard">
        <div class="card border-0">
            <div class="card-body text-center">
                <h5 class="card-title fw-bold mb-3" id="otpModalLabel" style="color: #012970;">Verification Code</h5>
                <p class="card-text text-muted">Enter the OTP sent to your email.</p>

                <!-- OTP Form -->
                <form action="../Auth/User/verify_otp.php" method="POST">
                    <div class="d-flex justify-content-center gap-2 mb-2">
                        <input type="text" name="otp1" class="otp-input" maxlength="1" required>
                        <input type="text" name="otp2" class="otp-input" maxlength="1" required>
                        <input type="text" name="otp3" class="otp-input" maxlength="1" required>
                        <input type="text" name="otp4" class="otp-input" maxlength="1" required>
                        <input type="text" name="otp5" class="otp-input" maxlength="1" required>
                        <input type="text" name="otp6" class="otp-input" maxlength="1" required>
                    </div>
                    <span> <a href="../Auth/User/resend_otp.php">Resend OTP</a> </span>

                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <a href="dashboard.patient.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Confirm</button>
                    </div>
                </form>

                <!-- Display Messages -->
                <?php
                if (isset($_SESSION['otp_message'])) {
                    echo '<div class="mt-2 text-danger">' . $_SESSION['otp_message'] . '</div>';
                    unset($_SESSION['otp_message']); // Clear message after displaying
                }
                ?>
            </div>
        </div>
    </section>




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

<script>
document.addEventListener("DOMContentLoaded", function () {
  const inputs = document.querySelectorAll(".otp-input");

  inputs.forEach((input, index) => {
    input.addEventListener("input", (e) => {
      const value = e.target.value;
      if (value.length === 1 && index < inputs.length - 1) {
        inputs[index + 1].focus();
      }
    });

    input.addEventListener("keydown", (e) => {
      if (e.key === "Backspace" && !e.target.value && index > 0) {
        inputs[index - 1].focus();
      }
    });

    input.addEventListener("paste", (e) => {
      e.preventDefault();
      const paste = e.clipboardData.getData("text").split("");
      paste.forEach((char, i) => {
        if (inputs[index + i]) inputs[index + i].value = char;
      });
      if (inputs[index + paste.length - 1]) {
        inputs[index + paste.length - 1].focus();
      }
    });
  });

  // Focus on first input on page load
  inputs[0].focus();
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