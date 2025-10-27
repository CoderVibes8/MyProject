<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>FAMILY Diagnostic Center</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="Assets/img/2.png" rel="icon">
    <link href="Assets/img/2.png" rel="apple-touch-icon">
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Questrial:wght@400&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="Assets/landingvendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="Assets/landingvendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="Assets/landingvendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="Assets/landingvendor/swiper/swiper-bundle.min.css" rel="stylesheet">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">




    <!-- Flaticons -->
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-brands/css/uicons-brands.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-thin-straight/css/uicons-thin-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-thin-chubby/css/uicons-thin-chubby.css'>




    <!-- Main CSS File -->
    <link href="assets/css/familycontentssss.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/contact.css">
    <link rel="stylesheet" href="Assets/css/landingmediaquerys.css">
    <link rel="stylesheet" href="assets/css/footer.css">
</head>


<body class="index-page">

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

            <a href="index.php" class="logo d-flex align-items-center">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <!-- <img src="assets/img/logo.webp" alt=""> -->
                <h1 class="sitename" style="color: #5c99EE;;">FDC</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#hero" class="active">Home</a></li>

                    <li class="dropdown extended-dropdown-2"><a href="#"><span>Sign In</span>
                            <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                        <ul>
                            <li>
                                <a data-bs-toggle="modal" data-bs-target="#login_patient" style="cursor: pointer;">
                                    <div class="menu-item-content">
                                        <div class="menu-icon">
                                            <i class="fi fi-tc-user"></i>
                                        </div>
                                        <div class="menu-text">
                                            <span class="menu-title">User Portal</span>
                                            <span class="menu-description">Sign in to book an appointment</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a data-bs-toggle="modal" data-bs-target="#login_clinic" style="cursor: pointer;">
                                    <div class="menu-item-content">
                                        <div class="menu-icon">
                                            <i class="fi fi-ts-user-md"></i>
                                        </div>
                                        <div class="menu-text">
                                            <span class="menu-title">Personnel Area</span>
                                            <span class="menu-description">Authorized clinic staff only</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a data-bs-toggle="modal" data-bs-target="#login_admin" style="cursor: pointer;">
                                    <div class="menu-item-content">
                                        <div class="menu-icon">
                                            <i class="fi fi-ts-admin-alt"></i>
                                        </div>
                                        <div class="menu-text">
                                            <span class="menu-title">Admin Area</span>
                                            <span class="menu-description">For system administrators only</span>
                                        </div>
                                    </div>

                                </a>
                            </li>


                            <!-- <li>
                                <a href="#">
                                    <div class="menu-item-content">
                                        <div class="menu-icon">
                                            <i class="bi bi-shield-lock"></i>
                                        </div>
                                        <div class="menu-text">
                                            <span class="menu-title">Security Center</span>
                                            <span class="menu-description">Manage privacy settings</span>
                                        </div>
                                    </div>
                                </a>
                            </li> -->
                        </ul>
                    </li>

                    <li><a href="#services">Services</a></li>
                    <li><a href="#tabs">About</a></li>


                    <li><a href="#contact">Contact</a></li>

                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

        </div>
    </header>


    <!-- Modal for Login Patient -->
    <div class="modal fade" id="login_patient" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onfocus="this.style.outline='none'; this.style.boxShadow='none';"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <!-- Centered Logo -->
                    <div class="text-center mb-3">
                        <img src="assets/img/2.png" alt="Logo" class="img-fluid" style="width: 60px;">
                    </div>

                    <!-- Login Form -->
                    <form action="Auth/User/login.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="pw" placeholder="Enter your password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <!-- Modal Footer Inside Form -->
                        <div class="d-flex flex-column align-items-center">
                            <button type="submit" class="btn btn-primary w-100 mb-2" name="patient-login">Login</button>
                            <a class="switch-modal" data-bs-toggle="modal" data-bs-target="#register_patient" style="cursor: pointer;">Not yet registered? Register</a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal for Register Patient -->
    <div class="modal fade" id="register_patient" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg" style="overflow: hidden;">
            <div class="modal-content p-4">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Register as Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onfocus="this.style.outline='none'; this.style.boxShadow='none';"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <!-- Centered Logo -->
                    <div class="text-center">
                        <img src="assets/img/familylogo.jpg" alt="Logo" class="img-fluid mb-3" style="width: 60px;">
                    </div>

                    <form action="Auth/User/Register.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">First Name</label>
                                    <input type="text" class="form-control" name="first_name" placeholder="Enter first name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Date of Birth</label>
                                    <input type="date" class="form-control" name="dob" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Gender</label>
                                    <select name="gender" class="form-control" required>
                                        <option selected disabled>Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Contact Number</label>
                                    <input type="tel" class="form-control" name="contact_number" placeholder="Enter contact number" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="password" placeholder="Enter password" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button" style="cursor:pointer;">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" placeholder="Enter last name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Home Address (Optional)</label>
                                    <input type="text" class="form-control" name="address" placeholder="Enter address">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="Enter email" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="cpw" placeholder="Confirm password" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button" style="cursor:pointer;">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Register Button & Switch -->
                        <div class="modal-footer border-0 d-flex flex-column">
                            <button type="submit" class="btn btn-primary w-100 mb-2" name="register-patient">Register</button>
                            <a style="cursor: pointer;" class="switch-modal" data-bs-target="#login_patient">Already have an account? Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for Login Doctor -->
    <div class="modal fade" id="login_clinic" tabindex="-1" aria-labelledby="loginClinicLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginClinicLabel">Login as Doctor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onfocus="this.style.outline='none'; this.style.boxShadow='none';"></button>
                </div>

                <div class="modal-body">
                    <div class="text-center">
                        <img src="assets/img/2.png" alt="Logo" class="img-fluid" style="width: 60px;">
                    </div>

                    <form action="Auth/Personnel/loginDoctor.auth.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="rememberDoctor">
                            <label class="form-check-label" for="rememberDoctor">Remember me</label>
                        </div>

                        <div class="modal-footer border-0 d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary w-100" name="login_doctor">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for Login Admin -->
    <div class="modal fade" id="login_admin" tabindex="-1" aria-labelledby="loginAdminLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginAdminLabel">Login as Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onfocus="this.style.outline='none'; this.style.boxShadow='none';"></button>
                </div>

                <div class="modal-body">
                    <div class="text-center">
                        <img src="assets/img/2.png" alt="Logo" class="img-fluid" style="width: 60px;">
                    </div>

                    <form action="auth/Admin/loginAdmin.auth.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="pw" placeholder="Enter your password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="rememberAdmin">
                            <label class="form-check-label" for="rememberAdmin">Remember me</label>
                        </div>

                        <div class="modal-footer border-0 d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary w-100" name="admin-login">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section">
            <div class="container">
                <div class="row align-items-center">

                    <!-- Hero Image -->
                    <div class="col-lg-6 order-1 order-lg-2">
                        <div class="hero-image text-center text-lg-end">
                            <img src="Assets/img/2.png" class="img-fluid floating" alt="" style="width: 350px;">
                        </div>
                    </div>

                    <!-- Hero Content -->
                    <div class="col-lg-6 order-2 order-lg-1">
                        <div class="hero-content text-center text-lg-start">
                            <h1>FDC: Family Diagnostic Center</h1>
                            <p>Streamlining healthcare access through our online appointment booking system.</p>
                            <div class="hero-actions justify-content-center justify-content-lg-start">
                                <a data-bs-toggle="modal" data-bs-target="#login_patient" class="btn-primary scrollto" style="cursor: pointer;">Book Now</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>


        <!-- Services & Features Section -->
        <section class="services-section" id="services">
            <!-- Information Features -->
            <div class="info-features">
                <div class="info-features-header">
                    <h3>Everything You Need for FAMILY</h3>
                    <p>Streamline tools that works for patients and personnels</p>
                </div>
                <div class="features-grid">
                    <div class="feature-item">
                        <div class="feature-icon">🔒</div>
                        <h4>HIPAA Compliant</h4>
                        <p>Full compliance with healthcare privacy regulations</p>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">⚡</div>
                        <h4>Real-time Updates</h4>
                        <p>Instant synchronization across all devices</p>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">📱</div>
                        <h4>Mobile Access</h4>
                        <p>Access patient information anywhere, anytime</p>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🔄</div>
                        <h4>Easy Integration</h4>
                        <p>Seamlessly connects with existing systems</p>
                    </div>
                </div>
            </div>

            <div class="services-container">
                <div class="services-header">
                    <h2>Our Clinic Services</h2>
                    <p>Comprehensive healthcare information management services</p>
                </div>

                <div class="services-grid">
                    <!-- Single Wide Appointment Information System Card -->
                    <div class="service-card-wide">
                        <div class="service-badge-wide">Featured Service</div>
                        <div class="service-header-wide">
                            <div class="service-icon-wide">📅</div>
                            <h3>Appointment Information System</h3>
                            <p class="service-description-wide">Comprehensive medical appointment scheduling with specialized diagnostic services</p>
                        </div>
                        <h3 class="mb-3" style="text-align: center; font-size:20px; font-weight:bold;">Features</h3>
                        <div class="service-features-wide">
                            <div class="card-feature">
                                <div class="feature">Online Appointment Booking</div>
                            </div>
                            <div class="card-feature">
                                <div class="feature">Schedule Management</div>
                            </div>
                            <div class="card-feature">
                                <div class="feature">Appointment Status Tracking</div>
                            </div>
                            <div class="card-feature">
                                <div class="feature">Automated Reminders</div>
                            </div>
                            <div class="card-feature">
                                <div class="feature">Specialized Medical Services</div>
                            </div>
                        </div>


                        <h3 class="mb-3" style="text-align: center; font-size:20px; font-weight:bold;">Services</h3>
                        <div class="service-buttons-grid">
                            <button class="medical-service-btn offers-btn" data-service="ultrasound">
                                <span><i class="fi fi-rr-waveform-path" style="color:#1E88E5;"></i></span>
                                Ultrasound
                            </button>
                            <button class="medical-service-btn offers-btn" data-service="2decho">
                                <span><i class="fi fi-rr-waveform" style="color:#00897B;"></i></span>
                                2D Echo
                            </button>
                            <button class="medical-service-btn offers-btn" data-service="vascular">
                                <span><i class="fi fi-rr-skeleton" style="color:#43A047;"></i></span>
                                Vascular Studies
                            </button>
                            <button class="medical-service-btn offers-btn" data-service="dna">
                                <span><i class="fi fi-rr-dna" style="color:#8E24AA;"></i></span>
                                DNA
                            </button>
                            <button class="medical-service-btn offers-btn" data-service="ecg">
                                <span><i class="fi fi-rr-heart-rate" style="color:#F4511E;"></i></span>
                                ECG
                            </button>
                            <button class="medical-service-btn offers-btn" data-service="cl">
                                <span><i class="fi fi-rr-microscope" style="color:#3949AB;"></i></span>
                                Clinical Laboratory
                            </button>
                            <button class="medical-service-btn offers-btn" data-service="xray">
                                <span><i class="fi fi-rr-x-ray" style="color:#FDD835;"></i></span>
                                X-ray
                            </button>
                        </div>
                    </div>
                </div>


            </div>
        </section>


        <!-- Medical Service Modals -->
        <!-- Ultrasound Modal -->
        <div class="service-modal modal" id="ultrasoundModal">
            <div class="service-modal-content">
                <button class="service-modal-close" id="closeUltrasound"><i class="fi fi-sr-cross-small"></i></button>
                <h2> Ultrasound Services</h2>
                <div style="margin-bottom: 1.5rem;">
                    <h3 style="color: #2b6cb0; margin-bottom: 1rem;">Available Ultrasound Examinations</h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Abdominal Ultrasound</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Pelvic Ultrasound</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Obstetric Ultrasound</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Thyroid Ultrasound</li>
                        <li style="padding: 8px 0;">• Musculoskeletal Ultrasound</li>
                    </ul>
                </div>
                <p style="color: #718096; margin-bottom: 1.5rem;">High-resolution imaging for accurate diagnosis and monitoring. Our state-of-the-art equipment provides detailed visualization of internal structures.</p>

            </div>
        </div>

        <!-- 2D Echo Modal -->
        <div class="service-modal" id="echoModal">
            <div class="service-modal-content">
                <button class="service-modal-close" id="closeEcho"><i class="fi fi-sr-cross-small"></i></button>
                <h2>2D Echocardiography</h2>
                <div style="margin-bottom: 1.5rem;">
                    <h3 style="color: #2b6cb0; margin-bottom: 1rem;">Cardiac Assessment Services</h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Complete Heart Function Analysis</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Valve Function Assessment</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Chamber Size Evaluation</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Wall Motion Analysis</li>
                        <li style="padding: 8px 0;">• Doppler Flow Studies</li>
                    </ul>
                </div>
                <p style="color: #718096; margin-bottom: 1.5rem;">Comprehensive cardiac imaging to evaluate heart structure and function. Non-invasive procedure with immediate results.</p>

            </div>
        </div>

        <!-- Vascular Studies Modal -->
        <div class="service-modal" id="vascularModal">
            <div class="service-modal-content">
                <button class="service-modal-close" id="closeVascular"><i class="fi fi-sr-cross-small"></i></button>
                <h2>Vascular Studies</h2>
                <div style="margin-bottom: 1.5rem;">
                    <h3 style="color: #2b6cb0; margin-bottom: 1rem;">Vascular Diagnostic Services</h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Carotid Artery Duplex</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Lower Extremity Arterial Studies</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Venous Duplex Scanning</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Renal Artery Assessment</li>
                        <li style="padding: 8px 0;">• ABI (Ankle-Brachial Index)</li>
                    </ul>
                </div>
                <p style="color: #718096; margin-bottom: 1.5rem;">Advanced vascular imaging to assess blood flow and detect arterial or venous conditions. Early detection saves lives.</p>

            </div>
        </div>

        <!-- DNA Testing Modal -->
        <div class="service-modal" id="dnaModal">
            <div class="service-modal-content">
                <button class="service-modal-close" id="closeDNA"><i class="fi fi-sr-cross-small"></i></button>
                <h2>DNA Testing Services</h2>
                <div style="margin-bottom: 1.5rem;">
                    <h3 style="color: #2b6cb0; margin-bottom: 1rem;">Genetic Testing Options</h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Paternity Testing</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Genetic Health Screening</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Pharmacogenomics Testing</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Ancestry Analysis</li>
                        <li style="padding: 8px 0;">• Carrier Screening</li>
                    </ul>
                </div>
                <p style="color: #718096; margin-bottom: 1.5rem;">Comprehensive genetic analysis for health insights and family planning. Secure, confidential, and accurate results.</p>
            </div>
        </div>

        <!-- ECG Modal -->
        <div class="service-modal" id="ecgModal">
            <div class="service-modal-content">
                <button class="service-modal-close" id="closeECG"><i class="fi fi-sr-cross-small"></i></button>
                <h2>Electrocardiogram (ECG) Services</h2>
                <div style="margin-bottom: 1.5rem;">
                    <h3 style="color: #2b6cb0; margin-bottom: 1rem;">ECG Options</h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Resting ECG</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Stress Test ECG</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Holter Monitoring</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Cardiac Event Monitoring</li>
                    </ul>
                </div>
                <p style="color: #718096; margin-bottom: 1.5rem;">
                    Accurate ECG testing to monitor heart activity, detect arrhythmias, and provide essential insights for cardiovascular health.
                </p>
            </div>
        </div>

        <!-- Clinical Laboratory Modal -->
        <div class="service-modal" id="clModal">
            <div class="service-modal-content">
                <button class="service-modal-close" id="closeCL"><i class="fi fi-sr-cross-small"></i></button>
                <h2>Clinical Laboratory Services</h2>
                <div style="margin-bottom: 1.5rem;">
                    <h3 style="color: #2b6cb0; margin-bottom: 1rem;">Laboratory Tests</h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Complete Blood Count (CBC)</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Blood Chemistry Panel</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Urinalysis</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Microbiology Tests</li>
                        <li style="padding: 8px 0;">• Hormone & Enzyme Assays</li>
                    </ul>
                </div>
                <p style="color: #718096; margin-bottom: 1.5rem;">
                    Comprehensive laboratory testing for accurate diagnosis and monitoring of your health conditions, delivered with precision and confidentiality.
                </p>
            </div>
        </div>

        <!-- X-ray Modal -->
        <div class="service-modal" id="xrayModal">
            <div class="service-modal-content">
                <button class="service-modal-close" id="closeXRAY"><i class="fi fi-sr-cross-small"></i></button>
                <h2>X-ray Imaging Services</h2>
                <div style="margin-bottom: 1.5rem;">
                    <h3 style="color: #2b6cb0; margin-bottom: 1rem;">Imaging Options</h3>
                    <ul style="list-style: none; padding: 0;">
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Chest X-ray</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Bone & Joint Imaging</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Abdominal X-ray</li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #e2e8f0;">• Dental X-ray</li>
                        <li style="padding: 8px 0;">• Fluoroscopy & Special X-ray Studies</li>
                    </ul>
                </div>
                <p style="color: #718096; margin-bottom: 1.5rem;">
                    High-quality X-ray imaging for accurate diagnosis and treatment planning. Fast, safe, and precise results for all patients.
                </p>
            </div>
        </div>


        <!-- Tabs Section -->
        <section id="tabs" class="tabs section">

            <div class="container">

                <div class="frame">
                    <!-- Tab labels -->
                    <div class="tabs">
                        <div class="labels" role="tablist" aria-label="Section tabs">
                            <label class="pill" for="tab-about" role="tab" aria-controls="panel-about" aria-selected="true">
                                About Us
                            </label>
                            <label class="pill" for="tab-mv" role="tab" aria-controls="panel-mv" aria-selected="true">
                                Mision & Vision
                            </label>
                            <label class="pill" for="tab-faqs" role="tab" aria-controls="panel-faqs" aria-selected="false">
                                FAQs
                            </label>
                            <label class="pill" for="tab-policy" role="tab" aria-controls="panel-policy" aria-selected="false">
                                Policy & Privacy
                            </label>
                            <label class="pill" for="tab-demo" role="tab" aria-controls="panel-demo" aria-selected="false">
                                Demo
                            </label>
                        </div>
                    </div>

                    <!-- Panels -->
                    <div class="panels">
                        <!-- About -->
                        <article id="panel-about" class="panel card" role="tabpanel" aria-labelledby="tab-about">
                            <div class="grid">
                                <div class="illus" aria-hidden="true">
                                    <img src="assets/img/aboutus.avif" width="500px">
                                </div>
                                <div>

                                    <h3 class="title">About Family Diagnostic Center</h3>
                                    <p class="desc">A single proprietor company which started its operation March 16, 2005. managed and owned by Mr. Erwin Go Young located in J & B III Bldg. Quezon Street, Iloilo City.



                                        It is a company focusing on providing clinical laboratory and Diagnostic Imaging service to healthcare community in Iloilo.



                                        Family Diagnostic Center provides diagnostic testing which includes: General Radiology, Ultrasound, 2D Echo and Vascular Studies services that patients and doctors need to make better healthcare decisions.



                                        Family Diagnostic Center Clinical Laboratories comprehensive testing services includes: Clinical Chemistry, Hematology, Microscopy, Bacteriology, Serology, Thyroid Function Test and Tumor Marker.



                                        Clinical laboratory testing is an essential element in the delivery of health care services. Physicians use laboratory tests to assist in the detection, diagnosis, evaluation, monitoring and treatment of diseases and other medical conditions. </p>

                                </div>
                            </div>
                        </article>

                        <!-- Mission and Vision -->
                        <article id="panel-faqs" class="panel card" role="tabpanel" aria-labelledby="tab-faqs">
                            <div class="grid">
                                <div class="illus" aria-hidden="true">
                                    <!-- SVG Illustration: FAQ cards -->
                                    <img src="Assets/img/mv.jpg" width="500px" height="300px">
                                </div>
                                <div>
                                    <h3 class="title">FDC Mission & Vision</h3>
                                    <p class="desc">
                                        <strong>Mission: </strong> Aims to provide high quality, cost effective. responsive and accurate diagnostic results, meeting the individual needs of both patient and referring physicians.</p>
                                    <p class="desc">
                                        <strong>Vision: </strong> 
                                        To enhance the health status and quality of life through proper diagnosis.</p>
                                    
                                </div>
                            </div>
                        </article>

                        <!-- FAQs -->
                        <article id="panel-faqs" class="panel card" role="tabpanel" aria-labelledby="tab-faqs">
                            <div class="grid">
                                <div class="illus" aria-hidden="true">
                                    <!-- SVG Illustration: FAQ cards -->
                                    <img src="Assets/img/faqs.jpg" width="500px" height="300px">
                                </div>
                                <div>
                                    <span class="eyebrow">Frequently Asked Questions</span>
                                    <h3 class="title">Answers at a glance</h3>
                                    <p class="desc"><strong>How do I book?</strong> Use our online scheduler to pick a date and time — you’ll get instant confirmation via email.</p>
                                    <p class="desc"><strong>Is my data secure?</strong> Yes. Encryption in transit and at rest with strict role‑based access controls.</p>
                                    <p class="desc"><strong>Can I reschedule?</strong> Absolutely. Reschedule from your confirmation email or patient portal in seconds.</p>
                                </div>
                            </div>
                        </article>

                        <!-- Policy & Privacy -->
                        <article id="panel-policy" class="panel card" role="tabpanel" aria-labelledby="tab-policy">
                            <div class="grid">
                                <div class="illus" aria-hidden="true">
                                    <img src="assets/img/policy&privacy1.png">
                                </div>
                                <div>
                                    <h3 class="title">Policy & Privacy</h3>
                                    <p class="desc">
                                        At Family Diagnostic Center, we take the privacy and security of patient information seriously. Since our establishment in 2005, we have been committed to providing trusted and reliable diagnostic services while ensuring that all health data remains confidential and protected.
                                    </p>
                                    <ul class="list">
                                        <li>Data Privacy – Patient records are handled in compliance with the Data Privacy Act of 2012 (RA 10173).</li>
                                        <li>Secure Booking System – Encrypted SSL/TLS connections safeguard your personal and medical data.</li>
                                        <li>Access Control – Only authorized personnel have access to sensitive patient information.</li>
                                    </ul>

                                    <!-- View More Button -->
                                    <button class="mt-3" data-bs-toggle="modal" data-bs-target="#policyModal" style="background: linear-gradient(135deg, #4f8cff, #8b5cf6); border:none; padding:8px 15px; color:white; border-radius:30px;">
                                        View More
                                    </button>
                                </div>
                            </div>
                        </article>

                        <!-- Policy Modal -->
                        <div class="modal fade" id="policyModal" tabindex="-1" aria-labelledby="policyModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content p-4">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="policyModalLabel">Full Policy & Privacy</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>At Family Diagnostic Center, we take the privacy and security of patient information seriously. Since our establishment in 2005, we have been committed to providing trusted and reliable diagnostic services while ensuring that all health data remains confidential and protected.</p>

                                        <ul>
                                            <li><b>Data Privacy –</b> All patient records and booking details are handled in compliance with the Data Privacy Act of 2012 (RA 10173). Information is collected only for medical and administrative purposes and will never be sold or misused.</li>
                                            <li><b>Secure Booking System –</b> Our appointment booking platform uses encrypted connections (SSL/TLS) to safeguard your personal and medical data during transmission.</li>
                                            <li><b>Access Control –</b> Only authorized personnel have access to sensitive patient information. Role-based permissions ensure that doctors, staff, and patients see only the data relevant to them.</li>
                                            <li><b>Confidentiality –</b> Test results and reports are released only to patients and/or their attending physicians.</li>
                                            <li><b>Data Retention & Backup –</b> Records are stored securely with regular system backups to prevent data loss.</li>
                                            <li><b>Continuous Improvement –</b> Security protocols and policies are regularly reviewed to keep up with the latest standards in healthcare data protection.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Demo -->
                        <article id="panel-demo" class="panel card" role="tabpanel" aria-labelledby="tab-demo">
                            <div class="grid">
                                <div class="illus" aria-hidden="true">
                                    <!-- SVG Illustration: Laptop/Preview -->
                                    <img src="assets/img/demo/demo.png" width="600px" height="350px">
                                </div>
                                <div>
                                    <h3 class="title">Explore the demo</h3>
                                    <p class="desc">Walk through booking using sample demo. </p>
                                    <ul class="list">
                                        <li>Sign In.</li>
                                        <li>Login your account. if not yet login register.</li>
                                        <li>Book an Appointment.</li>
                                        <li>Fill up the preferred form.</li>
                                        <li>Check your appointed schedule. </li>
                                        <li>Visit FDC</li>
                                        <li>Check Records</li>
                                        <li>Download your File</li>
                                    </ul>

                                    <!-- Explore Demo Button -->
                                    <button class="mt-3" data-bs-toggle="modal" data-bs-target="#demoModal" style="background: linear-gradient(135deg, #4f8cff, #8b5cf6); border:none; padding:8px 15px; color:white; border-radius:30px;">
                                        Explore Demo
                                    </button>
                                </div>
                            </div>
                        </article>

                        <!-- Demo Modal -->
                        <div class="modal fade" id="demoModal" tabindex="-1" aria-labelledby="demoModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content p-4">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="demoModalLabel">Demo Walkthrough</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body text-center">
                                        <!-- Slide Content -->
                                        <h4 id="demoSlideTitle"></h4>
                                        <img id="demoSlideImage" src="" alt="Demo Step"
                                            class="img-fluid mt-3 rounded shadow" style="max-height: 350px;">

                                        <!-- Controls -->
                                        <div class="d-flex justify-content-between mt-4">
                                            <button class="btn btn-outline-primary" id="demoPrev">Previous</button>
                                            <button class="btn btn-outline-primary" id="demoNext">Next</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </section><!-- /Tabs Section -->


        <!-- CTA Section -->
        <section class="cta" id="contact">
            <div class="cta-content">
                <h2>Get in Touch with <span>FAMILY</span></h2>
                <p>We’d love to hear from you! Reach out for support, partnerships, or updates through our contact channels.</p>

                <!-- Contact Row -->
                <div class="contact-row">
                    <div class="contact-card">
                        <i class="fi fi-rr-envelope"></i>
                        <h3>Email Us</h3>
                        <span>diagnosticfamily@gmail.com</span>
                        <span>supportdiagnosticfamily@gmail.com</span>
                    </div>

                    <div class="contact-card">
                        <i class="fi fi-rr-phone-call"></i>
                        <h3>Call Us</h3>
                        <span>
                            <i class="fi fi-rr-phone-rotary" style="color: gray; font-size:15px"></i>&nbsp; 0999-900-8896 - 0927-926-3193</span>
                        <span><i class="fi fi-rr-phone-call" style="color: gray; font-size:15px"></i>&nbsp; (033)508-6838 - 338-3778</span>
                    </div>

                    <div class="contact-card">
                        <i class="fi fi-ts-land-layer-location"></i>
                        <h3>Visit Us</h3>
                        <span>J & B III Building, Quezon St, Iloilo City Proper, Iloilo City, 5000 Iloilo</span>
                        <span>Mon-Sat, 6:00am-4:30pm</span>
                    </div>
                </div>
            </div>

            <!-- Social Section -->
            <div class="social-section">
                <h3>Follow Family Diagnostic Center</h3>
                <div class="social-links">
                    <a href="https://web.facebook.com/FamilyDiagnosticCenter" class="social-link"><i class="fi fi-brands-facebook mt-2"></i> Facebook</a>
                    <!-- <a href="#" class="social-link"><i class="fi fi-brands-twitter mt-2"></i> Twitter</a>
                    <a href="#" class="social-link"><i class="fi fi-brands-linkedin mt-2"></i> LinkedIn</a>
                    <a href="#" class="social-link"><i class="fi fi-brands-instagram mt-2"></i> Instagram</a>
                    <a href="#" class="social-link"><i class="fi fi-brands-youtube mt-2"></i> YouTube</a> -->
                </div>
            </div>
        </section>




    </main>

    <footer id="footer" class="footer position-relative light-background">

        <div class="container">
            <div class="row gy-5">

                <div class="col-lg-4">
                    <div class="footer-content">
                        <a href="index.php" class="logo d-flex align-items-center mb-4">
                            <span class="sitename" style="color: #5c99EE;">FDC</span>
                        </a>
                        <p class="mb-4">Streamlining healthcare access through our online appointment booking system.</p>


                    </div>
                </div>

                <div class="col-lg-2 col-6">
                    <div class="footer-links">
                        <h4>Clinic Services</h4>
                        <ul>
                            <li><a href="#"> Ultra Sound </a></li>
                            <li><a href="#"> 2D Echo </a></li>
                            <li><a href="#"> Vascular Studies </a></li>
                            <li><a href="#"> DNA </a></li>

                        </ul>
                    </div>
                </div>

                <div class="col-lg-2 col-6">
                    <div class="footer-links">
                        <h4>Abouts</h4>
                        <ul>
                            <li><a href="#tabs">About Us</a></li>
                            <li><a href="#tabs">FAQs</a></li>
                            <li><a href="#tabs">Privay & Security</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="footer-links">
                        <h4>Contact Us</h4>
                        <ul>
                            <li><a href="#">Email: diagnosticfamily@gmail.com</a></li>
                            <li>Contact Number: (033)508-6838 - 338-3778 <br> 0999-900-8896 - 0927-926-3193</li>

                        </ul>
                    </div>
                </div>

            </div>
        </div>
        <hr>
        <div class="copyright">
            &copy; <script>
                document.write(new Date().getFullYear());
            </script> FAMILY. All rights reserved.
        </div>

    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Modal for Logins -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Handle switching between modals
            document.querySelectorAll(".switch-modal").forEach(link => {
                link.addEventListener("click", function(event) {
                    event.preventDefault();
                    let targetModal = this.getAttribute("data-bs-target");

                    $(".modal").modal("hide"); // Hide current modal

                    $(".modal").on("hidden.bs.modal", function() {
                        setTimeout(() => {
                            $(targetModal).modal("show"); // Show target modal after delay
                        }, 300); // matches Bootstrap fade duration
                        $(".modal").off("hidden.bs.modal");
                    });
                });
            });

            // Password toggle visibility
            document.querySelectorAll(".toggle-password").forEach(button => {
                button.addEventListener("click", function() {
                    let input = this.closest(".input-group").querySelector("input");
                    input.type = input.type === "password" ? "text" : "password";
                    this.firstElementChild.classList.toggle("fa-eye-slash");
                });
            });
        });

        // ========= Custom Modal Open Functions =========
        function openPatientModal() {
            var patientModal = new bootstrap.Modal(document.getElementById('login_patient'));
            patientModal.show();
        }

        function openDoctorModal() {
            var doctorModal = new bootstrap.Modal(document.getElementById('login_clinic'));
            doctorModal.show();
        }

        function openAdminModal() {
            var adminModal = new bootstrap.Modal(document.getElementById('login_admin'));
            adminModal.show();
        }
    </script>



    <!-- Modal for Clinic Services -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Map button data-service attribute to modal IDs
            const serviceModals = {
                ultrasound: "ultrasoundModal",
                "2decho": "echoModal",
                vascular: "vascularModal",
                dna: "dnaModal",
                ecg: "ecgModal",
                cl: "clModal",
                xray: "xrayModal"
            };

            // Open modal on button click
            document.querySelectorAll(".medical-service-btn").forEach(button => {
                button.addEventListener("click", () => {
                    const service = button.getAttribute("data-service");
                    const modalId = serviceModals[service];
                    if (modalId) {
                        const modal = document.getElementById(modalId);
                        modal.style.display = "flex";
                        setTimeout(() => modal.classList.add("show"), 10); // fade-in
                    }
                });
            });

            // Function to close modal with fade-out
            function closeModal(modal) {
                modal.classList.remove("show");
                setTimeout(() => {
                    modal.style.display = "none";
                }, 300); // matches CSS transition
            }

            // Close modal on X button click
            document.querySelectorAll(".service-modal-close").forEach(closeBtn => {
                closeBtn.addEventListener("click", () => {
                    const modal = closeBtn.closest(".service-modal");
                    closeModal(modal);
                });
            });

            // Close modal when clicking outside modal-content
            window.addEventListener("click", (e) => {
                document.querySelectorAll(".service-modal").forEach(modal => {
                    if (e.target === modal) {
                        closeModal(modal);
                    }
                });
            });
        });
    </script>

    <!-- Script for tabs -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const tabs = document.querySelectorAll(".labels label");
            const panels = document.querySelectorAll(".panels .panel");

            tabs.forEach((tab, index) => {
                tab.addEventListener("click", () => {
                    // Reset all
                    tabs.forEach(t => {
                        t.setAttribute("aria-selected", "false");
                        t.classList.remove("active");
                    });
                    panels.forEach(p => p.classList.remove("active"));

                    // Activate current
                    tab.setAttribute("aria-selected", "true");
                    tab.classList.add("active");
                    panels[index].classList.add("active");
                });
            });

            // Default active
            tabs[0].classList.add("active");
            panels[0].classList.add("active");
        });
    </script>

    <!-- Demo Script -->
    <script>
        // Demo Slides (title + image for each step)
        const demoSlides = [{
                title: "Step 1: Click the login button",
                img: "Assets/img/demo/demo.png"
            },
            {
                title: "Step 2: Login your account",
                img: "Assets/img/demo/login.png"
            },
            {
                title: "Register",
                img: "Assets/img/demo/register.png"
            },
            {
                title: "Step 3: Book an Appointment",
                img: "Assets/img/demo/bookapt.png"
            },
            {
                title: "Step 4: Fill out the Form",
                img: "Assets/img/demo/booked.png"
            },
            {
                title: "Step 5: Check your Schedule",
                img: "Assets/img/demo/waitforsched.png"
            },
            {
                title: "Step 6: Visit FDC",
                img: "Assets/img/demo/visit.avif"
            },
            {
                title: "Step 7: Check Records",
                img: "Assets/img/demo/checkrecords.png"
            },
            {
                title: "Step 8: Download your File",
                img: "Assets/img/demo/pdf.png"
            }


        ];

        let demoCurrent = 0;

        // Elements
        const demoTitle = document.getElementById("demoSlideTitle");
        const demoImage = document.getElementById("demoSlideImage");
        const demoPrev = document.getElementById("demoPrev");
        const demoNext = document.getElementById("demoNext");

        // Function to update slide
        function showDemoSlide(index) {
            if (index < 0) index = demoSlides.length - 1;
            if (index >= demoSlides.length) index = 0;
            demoCurrent = index;
            demoTitle.textContent = demoSlides[index].title;
            demoImage.src = demoSlides[index].img;
        }

        // Event listeners
        demoPrev.addEventListener("click", () => showDemoSlide(demoCurrent - 1));
        demoNext.addEventListener("click", () => showDemoSlide(demoCurrent + 1));

        // Load first slide when modal opens
        document.getElementById("demoModal").addEventListener("shown.bs.modal", () => {
            showDemoSlide(0);
        });
    </script>

    <script>
        Swal.fire({
            icon: '<?php echo $alert['icon']; ?>',
            title: '<?php echo $alert['title']; ?>',
            text: '<?php echo $alert['text']; ?>',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.location.href = '<?php echo $alert['redirect']; ?>';
        });
    </script>

    <!-- jQuery (Required for Bootstrap Modals to Work) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Vendor JS Files -->
    <script src="Assets/landingvendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="Assets/landingvendor/php-email-form/validate.js"></script>
    <script src="Assets/landingvendor/glightbox/js/glightbox.min.js"></script>
    <script src="Assets/landingvendor/swiper/swiper-bundle.min.js"></script>
    <script src="Assets/landingvendor/purecounter/purecounter_vanilla.js"></script>
    <script src="Assets/landingvendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="Assets/landingvendor/isotope-layout/isotope.pkgd.min.js"></script>

    <!-- Main JS File -->
    <script src="Assets/landingjs/main.js"></script>

    <!-- SweetAlert2 (must come before SweetAlert include) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            <?php if ($patient_message): ?>
                Swal.fire({
                    title: <?php echo json_encode($patient_message['title']); ?>,
                    text: <?php echo json_encode($patient_message['message']); ?>,
                    icon: <?php echo json_encode($patient_message['type']); ?>,
                    showConfirmButton: true,
                    timer: 3000
                });
            <?php endif; ?>

            <?php if ($doctor_message): ?>
                Swal.fire({
                    title: <?php echo json_encode($doctor_message['title']); ?>,
                    text: <?php echo json_encode($doctor_message['message']); ?>,
                    icon: <?php echo json_encode($doctor_message['type']); ?>,
                    showConfirmButton: true,
                    timer: 3000
                });
            <?php endif; ?>
        });
    </script>

    <?php include_once 'Includes/SweetAlert.php'; ?>

</body>

</html>