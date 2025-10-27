    <header id="header" class="header fixed-top d-flex align-items-center px-3">
        <div class="container-fluid d-flex justify-content-between align-items-center">

            <!-- Logo Section -->
            <!-- <div class="sidebar-header d-flex align-items-center mb-4 mt-4">
                <a href="dashboard.patient.php" class="d-flex align-items-center" style="text-decoration: none; color:black; text-shadow: 1px 1px 1px #c0e6ffff;">
                    <div class="brand-mark"><img src="../Assets/img/familylogo.jpg" style="width: 44px; height:44px; border-radius:12px;"></div>
                    <div class="brand-text">
                        <h1>FAMILY DIAGNOSTIC CENTER</h1>
                    </div>
                </a>
            </div> -->
            <!-- Hamburger Button (only visible on mobile) -->
            <button class="hamburger" id="sidebarToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <!-- Profile & Find Nearby Clinic Section -->
            <nav class="header-nav ms-auto d-flex align-items-center">

                <ul class="d-flex align-items-center m-0 p-0 list-unstyled">
                    <li class="nav-item dropdown pe-3">
                        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                            <img src="<?php echo $profile_picture; ?>" alt="Profile Picture" class="rounded-circle" width="35">
                            <span class="d-none d-md-block dropdown-toggle ps-1"><?php echo htmlspecialchars($userProfile['first_name']) . ' ' . htmlspecialchars($userProfile['last_name']); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                            <li class="dropdown-header text-center">
                                <h6><?php echo 'Mr. ' . htmlspecialchars($userProfile['last_name']); ?></h6>
                                <span><?php echo ucfirst(htmlspecialchars($userProfile['role'])); ?></span>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="patient_profile.php">
                                    <i class="bi bi-person me-2"></i> My Profile
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="../Auth/User/logout.php">
                                    <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>

        </div>
    </header>