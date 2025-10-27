<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>HealthNet</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- <link rel="manifest" href="site.webmanifest"> -->
    <link rel="shortcut icon" type="image/x-icon" href="Assets/img/favicon.png">
    <!-- Place favicon.ico in the root directory -->

    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- flaticon -->

    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-straight/css/uicons-regular-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-rounded/css/uicons-thin-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-straight/css/uicons-thin-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-rounded/css/uicons-thin-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-straight/css/uicons-thin-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-rounded/css/uicons-thin-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-thin-straight/css/uicons-thin-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-brands/css/uicons-brands.css'>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CSS here -->
    <link rel="stylesheet" href="Assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="Assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="Assets/css/magnific-popup.css">
    <link rel="stylesheet" href="Assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="Assets/css/themify-icons.css">
    <link rel="stylesheet" href="Assets/css/nice-select.css">
    <link rel="stylesheet" href="Assets/css/flaticon.css">
    <link rel="stylesheet" href="Assets/css/gijgo.css">
    <link rel="stylesheet" href="Assets/css/animate.css">
    <link rel="stylesheet" href="Assets/css/slicknav.css">

    <!-- <link rel="stylesheet" href="css/responsive.css"> -->

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Template Main CSS File -->
    <link rel="stylesheet" href="Assets/css/landingstyle.css">

</head>


<body>

    <header>
        <div class="header-area ">
            <div id="sticky-header" class="main-header-area">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-xl-3 col-lg-3">
                            <div class="logo-img">
                                <a href="#">
                                    <img src="Assets/img/HNLogo.png" style="width: 113px;" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-9 col-lg-9">
                            <div class="menu_wrap d-none d-lg-block">
                                <div class="menu_wrap_inner d-flex align-items-center justify-content-end">
                                    <button type="button" class="btn btn-secondary m-3" data-toggle="modal" data-target="#demoModal">
                                        Demo
                                    </button>
                                    <div class="book_room">
                                        <div class="book_btn" style="margin-right: 20px;">
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">User Log In
                                                    <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" data-toggle="modal" data-target="#login_admin">Login as Admin</a></li>
                                                    <li><a class="dropdown-item" data-toggle="modal" data-target="#login_clinic">Login as Doctor</a></li>
                                                    <li><a class="dropdown-item" data-toggle="modal" data-target="#login_patient">Login as Patient</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header-end -->

    <!-- Modal for Demo-->
    <div class="modal fade" id="demoModal" tabindex="-1" role="dialog" aria-labelledby="demoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <!-- Close Button -->
                <div class="modal-header">
                    <h5 class="modal-title" id="demoModalLabel">Website Demo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal Body with Image -->
                <div class="modal-body text-center">
                    <h3>Login As Patient</h3>
                    <img src="./Assets/img/manual/guide1.png"
                        alt="Demo Guide" class="img-fluid rounded">
                </div>
                <div class="modal-body text-center">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Register</h3>
                            <img src="Assets/img/manual/guide3.png" alt="Demo Guide" class="img-fluid rounded mb-3">
                        </div>
                        <div class="col-md-6">
                            <h3>Login</h3>
                            <img src="Assets/img/manual/guide4.png" alt="Demo Guide" class="img-fluid rounded mb-3">
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <!-- Modal for Login Admin -->
    <div class="modal fade" id="login_admin" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title">Login as Admin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Centered Logo -->
                    <div class="text-center">
                        <img src="Assets/img/HNLogo.png" alt="Logo" class="img-fluid mb-2" style="width: 100px;">
                    </div>

                    <form action="auth/Admin/loginAdmin.auth.php" method="POST">
                        <div class="form-group">
                            <label class="font-weight-bold">Email</label>
                            <input type="email" class="form-control" name="email" autofocus placeholder="Enter your email" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="pw" placeholder="Enter your password" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input">
                            <label class="form-check-label">Remember me</label>
                        </div>
                        <div class="modal-footer border-0 d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary btn-block" name="admin-login">Login</button>
                            <!-- <a href="#" class="switch-modal" data-target="#register_patient">Not yet registered? Register</a> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Login Doctor -->
    <div class="modal fade" id="login_clinic" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title">Login as Doctor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Centered Logo -->
                    <div class="text-center">
                        <img src="Assets/img/HNLogo.png" alt="Logo" class="img-fluid mb-2" style="width: 100px;">
                    </div>

                    <form action="auth/Personnel/loginDoctor.auth.php" method="POST">
                        <div class="form-group">
                            <label class="font-weight-bold">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input">
                            <label class="form-check-label">Remember me</label>
                        </div>
                        <div class="modal-footer border-0 d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary btn-block" name="login_doctor">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for Login Patient -->
    <div class="modal fade" id="login_patient" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title">Login as Patient</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Centered Logo -->
                    <div class="text-center">
                        <img src="Assets/img/HNLogo.png" alt="Logo" class="img-fluid mb-2" style="width: 100px;">
                    </div>

                    <form action="Auth/User/login.php" method="POST">
                        <div class="form-group">
                            <label class="font-weight-bold">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="pw" placeholder="Enter your password" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input">
                            <label class="form-check-label">Remember me</label>
                        </div>
                        <div class="modal-footer border-0 d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary btn-block" name="patient-login">Login</button>
                            <a href="#" class="switch-modal" data-target="#register_patient">Not yet registered? Register</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Register Patient-->
    <div class="modal fade" id="register_patient" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content p-4" style="max-height: 110vh; overflow-y: auto;">
                <div class="modal-header">
                    <h5 class="modal-title">Register as Patient</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Centered Logo -->
                    <div class="text-center">
                        <img src="Assets/img/HNLogo.png" alt="Logo" class="img-fluid mb-3" style="width: 100px;">
                    </div>

                    <form action="Auth/register.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">First Name</label>
                                    <input type="text" class="form-control" name="first_name" placeholder="Enter first name" required>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Date of Birth</label>
                                    <input type="date" class="form-control" name="dob" required>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Gender</label>
                                    <select name="gender" class="form-control" required>
                                        <option selected disabled>Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold pt-3">Contact Number</label>
                                    <input type="tel" class="form-control" name="contact_number" placeholder="Enter contact number" required>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">National ID(Required)</label>
                                    <input type="file" class="form-control" name="national_id" required>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="password" placeholder="Enter password" required>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" placeholder="Enter last name" required>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Home Address(Optional)</label>
                                    <input type="text" class="form-control" name="address" placeholder="Enter address">
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="Enter email" required>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">PhilHealth ID(Optional)</label>
                                    <input type="file" class="form-control" name="philhealth_id">
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="cpw" placeholder="Confirm password" required>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Register Button -->
                        <div class="modal-footer border-0 d-flex flex-column">
                            <button type="submit" class="btn btn-primary btn-block" name="register-patient">Register</button>
                            <a href="#" class="switch-modal" data-target="#login_patient">Already have an account? Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>







    <!-- slider_area_start -->
    <div class="slider_area mt-5">
        <div class="slider_active owl-carousel">
            <div class="single_slider  d-flex align-items-center slider_bg_1 overlay mt-5">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="slider_text">
                                <h3> <span>Health Net:</span> <br>
                                    "Enhancing Community Engagement in Online Medical Appointment System
                                    and Telemedicine Services"</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- slider_area_end -->

    <!-- Features -->
    <div class="body-content">
        <section class="features">
            <div class="features-content">
                <div class="section-head">
                    <h2>Everything You Need for Modern Healthcare</h2>
                    <p>Streamlined tools that work for patients, doctors, and administrators.</p>
                </div>
                <div class="grid">
                    <div class="card">
                        <div class="card-icon icon-blue">📱</div>
                        <h3>Video Consultations</h3>
                        <p>High-quality, secure video calls that connect patients with their doctors from anywhere, anytime.</p>
                    </div>
                    <div class="card">
                        <div class="card-icon icon-green">📅</div>
                        <h3>Smart Scheduling</h3>
                        <p>Easy appointment booking with automated reminders and calendar sync for both patients and providers.</p>
                    </div>
                    <div class="card">
                        <div class="card-icon icon-amber">📊</div>
                        <h3>Health Records</h3>
                        <p>Secure, centralized health records that give you complete visibility into patient history and progress.</p>
                    </div>
                    <div class="card">
                        <div class="card-icon icon-blue">💬</div>
                        <h3>Secure Messaging</h3>
                        <p>HIPAA-compliant messaging system for quick questions, follow-ups, and care coordination.</p>
                    </div>
                    <div class="card">
                        <div class="card-icon icon-green">🔒</div>
                        <h3>Privacy First</h3>
                        <p>Bank-level encryption and compliance with healthcare privacy standards to protect sensitive data.</p>
                    </div>
                    <div class="card">
                        <div class="card-icon icon-amber">⚡</div>
                        <h3>Fast & Reliable</h3>
                        <p>Built for speed with 99.9% uptime, so your healthcare never has to wait.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="cta">
            <div class="cta-content">
                <h2>Get in Touch with HealthNet </h2>
                <p>Connect with us through multiple channels.Follow our updates and reach out for support, partnership, or more information about healthcare</p>

                <div class="row" style="display:flex; gap:50px; flex:wrap; justify-content:center;">
                    <div class="card-contacts">
                        <div class="email-us">
                            <h3>Email Us</h3>
                            <i class="fi fi-rr-envelope"></i>
                            <span>@healthnetclinic.com</span>
                            <span>support@healthnetclinic.com</span>
                        </div>
                    </div>

                    <div class="card-actions">
                        <div class="email-us">
                            <h3>Call Us</h3>
                            <i class="fi fi-rr-phone-call"></i>
                            <span>+123 456 789</span>
                            <span>Mon-Sat, 8am-4pm</span>
                        </div>
                    </div>

                    <div class="card-actions">
                        <div class="email-us">
                            <h3>Visit Us</h3>
                            <i class="fi fi-ts-land-layer-location"></i>
                            <span>J & B lll Bldg.</span>
                            <span>Quezon Street, Iloilo City</span>
                        </div>
                    </div>
                </div>
            </div>


            <div class="social-section">
                <h3>Follow HealthNet</h3>
                <div class="social-links">
                    <a href="#" class="social-link facebook">
                        <div class="social-icon">📘</div>
                        <span>Facebook</span>
                    </a>
                    <a href="#" class="social-link twitter">
                        <div class="social-icon">🐦</div>
                        <span>Twitter</span>
                    </a>
                    <a href="#" class="social-link linkedin">
                        <div class="social-icon">💼</div>
                        <span>LinkedIn</span>
                    </a>
                    <a href="#" class="social-link instagram">
                        <div class="social-icon">📷</div>
                        <span>Instagram</span>
                    </a>
                    <a href="#" class="social-link youtube">
                        <div class="social-icon">📺</div>
                        <span>YouTube</span>
                    </a>
                </div>
            </div>
        </section>
    </div>


    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; <script>
                document.write(new Date().getFullYear());
            </script> HealthNET. All rights reserved.
        </div>

    </footer><!-- End Footer -->




    <!-- Font Awesome for Eye Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".switch-modal").forEach(link => {
                link.addEventListener("click", function(event) {
                    event.preventDefault();
                    let targetModal = this.getAttribute("data-target");

                    $(".modal").modal("hide"); 

                    $(".modal").on("hidden.bs.modal", function() {
                        $(targetModal).modal("show"); 
                        $(".modal").off("hidden.bs.modal"); 
                    });
                });
            });

            $(".modal").on("hidden.bs.modal", function() {
                $("body").removeClass("modal-open");
                $(".modal-backdrop").remove();
            });

            document.querySelectorAll(".toggle-password").forEach(button => {
                button.addEventListener("click", function() {
                    let input = this.closest(".input-group").querySelector("input");
                    input.type = input.type === "password" ? "text" : "password";
                    this.firstElementChild.classList.toggle("fa-eye-slash");
                });
            });
        });

        $(document).ready(function() {
            $(".dropdown-toggle").on("click", function(e) {
                var $dropdown = $(this).next(".dropdown-menu");

                if ($dropdown.hasClass("show")) {
                    $dropdown.removeClass("show"); // Close if already open
                } else {
                    $(".dropdown-menu").removeClass("show"); // Close other open dropdowns
                    $dropdown.addClass("show"); // Open this one
                }

                return false; // Prevents default behavior
            });

            // Close dropdown when clicking outside
            $(document).on("click", function(e) {
                if (!$(e.target).closest(".dropdown").length) {
                    $(".dropdown-menu").removeClass("show");
                }
            });
        });
    </script>
    <!-- Include jQuery (Required for Bootstrap Modals to Work) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!-- Bootstrap JS (Ensure it's included) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>






    <!-- JS here -->
    <script src="Assets/js/vendor/modernizr-3.5.0.min.js"></script>
    <script src="Assets/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="Assets/js/popper.min.js"></script>
    <script src="Assets/js/bootstrap.min.js"></script>
    <script src="Assets/js/owl.carousel.min.js"></script>
    <script src="Assets/js/isotope.pkgd.min.js"></script>
    <script src="Assets/js/ajax-form.js"></script>
    <script src="Assets/js/waypoints.min.js"></script>
    <script src="Assets/js/jquery.counterup.min.js"></script>
    <script src="Assets/js/imagesloaded.pkgd.min.js"></script>
    <script src="Assets/js/scrollIt.js"></script>
    <script src="Assets/js/jquery.scrollUp.min.js"></script>
    <script src="Assets/js/wow.min.js"></script>
    <script src="Assets/js/nice-select.min.js"></script>
    <script src="Assets/js/jquery.slicknav.min.js"></script>
    <script src="Assets/js/jquery.magnific-popup.min.js"></script>
    <script src="Assets/js/plugins.js"></script>
    <script src="Assets/js/gijgo.min.js"></script>

    <!--contact js-->
    <script src="Assets/js/contact.js"></script>
    <script src="Assets/js/jquery.ajaxchimp.min.js"></script>
    <script src="Assets/js/jquery.form.js"></script>
    <script src="Assets/js/jquery.validate.min.js"></script>
    <script src="Assets/js/mail-script.js"></script>


    <script src="Assets/js/main.js"></script>
    <script>
        document.querySelector(".slider_bg_1").style.filter = "none";

        $('.datepicker').datepicker({
            iconsLibrary: 'fontawesome',
            icons: {
                rightIcon: '<span class="fa fa-calendar"></span>'
            }
        });

        $('.timepicker').timepicker({
            iconsLibrary: 'fontawesome',
            icons: {
                rightIcon: '<span class="fa fa-clock-o"></span>'
            }
        });
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });
    </script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php
    if (isset($_SESSION['message']) && !empty($_SESSION['message'])):
    ?>

        <script>
            const messageData = <?php echo json_encode($_SESSION["message"]); ?>;
            Swal.fire({
                title: messageData.title,
                text: messageData.message,
                icon: messageData.type,
                showconfirmButton: true,
                timer: 5000
            });
        </script>
    <?php
        unset($_SESSION['message']);
    endif;
    ?>

</body>

</html>