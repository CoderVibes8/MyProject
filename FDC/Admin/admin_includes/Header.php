<header id="header" class="header fixed-top d-flex align-items-center">

  <!-- Logo Section -->
  <!-- <div class="sidebar-header d-flex align-items-center mb-4 mt-4">
    <a href="dashboard.admin.php" class="d-flex align-items-center" style="text-decoration: none; color:black; text-shadow: 1px 1px 1px #c0e6ffff;">
      <div class="brand-mark"><img src="../assets/img/icons/1.png" style="width: 44px; height:44px; border-radius:12px;"></div>
      <div class="brand-text">
        <h1>HealthNET Clinic</h1>
      </div>
    </a>
  </div> -->

  <!-- Hamburger Button (only visible on mobile) -->
  <button class="hamburger" id="sidebarToggle">
    <span></span>
    <span></span>
    <span></span>
  </button>

  <nav class="header-nav ms-auto d-flex align-items-center">

    <ul class="d-flex align-items-center m-0 p-0 list-unstyled">
      <li class="nav-item dropdown pe-3">

        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <img src="<?php echo '../uploads/' . htmlspecialchars($adminAcc['profile']); ?>" alt="Profile" class="rounded-circle">
          <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo htmlspecialchars($full_name); ?></span>
        </a><!-- End Profile Iamge Icon -->

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6><?php echo htmlspecialchars($full_name); ?></h6>
            <span><?php echo htmlspecialchars($adminAcc['role']); ?></span>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="admin_profile.admin.php">
              <i class="bi bi-box-arrow-right"></i>
              <span>My Profile</span>
            </a>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="../Auth/Admin/SignOutAdmin.auth.php">
              <i class="bi bi-box-arrow-right"></i>
              <span>Sign Out</span>
            </a>
          </li>

        </ul><!-- End Profile Dropdown Items -->
      </li><!-- End Profile Nav -->

    </ul>
  </nav><!-- End Icons Navigation -->

</header>