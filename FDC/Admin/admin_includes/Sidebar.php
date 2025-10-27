<style>
  /* Default Sidebar (Desktop) */
  .sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 300px;
    background: #fff;
    transition: all 0.3s ease;
    overflow-y: auto;
    z-index: 1050;
    border-right: 1px solid #ddd;
  }

  /* Style for each nav-item */
  .sidebar .nav-item {
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border-radius: 10px;
  }

  /* Hover effect */
  .sidebar .nav-item:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
    /* subtle lift */
  }

  /* Nav links inside */
  .sidebar .nav-item .nav-link {
    color: #ddd;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
  }




  /* Collapsed sidebar */
  .sidebar.collapsed {
    width: 90px;
  }

  /* Hide text inside when collapsed */
  .sidebar.collapsed .brand-text,
  .sidebar.collapsed .nav-link span {
    display: none;
  }

  /* Brand image default */
  .sidebar .brand-marks img {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    transition: all 0.3s ease;
  }

  /* Brand image shrink on collapse */
  .sidebar.collapsed .brand-marks img {
    width: 30px;
    height: 30px;
    border-radius: 8px;
  }


  /* Overlay (dark background behind sidebar) */
  .sidebar-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1500;
  }

  /* Show overlay only when sidebar is open */
  .sidebar.open~.sidebar-overlay {
    display: block;
  }

  /* Close button inside sidebar */
  .sidebar .close-btn {
    background: none;
    border: none;
    font-size: 2rem;
    color: #333;
    position: absolute;
    top: 15px;
    right: 0px !important;
    cursor: pointer;
    z-index: 2001;
  }

  /* Hide close button by default */
  .sidebar .close-btn {
    display: none;
  }

  /* Show close button only on tablets & phones (≤768px) */
  @media (max-width: 768px) {
    .sidebar .close-btn {
      display: block;
    }
  }

  /* Extra small phones (≤480px) */
  @media (max-width: 480px) {
    .sidebar .close-btn {
      display: block;
      font-size: 1.8rem;
      /* slightly smaller for tiny screens */
      top: 25px;
      right: 5px;
    }
  }



  /* ====== MOBILE VIEW ====== */
  @media (max-width: 768px) {
    .sidebar {
      width: 220px;
      /* 📌 smaller width on phones */
      transform: translateX(-100%);
    }

    .sidebar.open {
      transform: translateX(0);
    }

    /* Page content full width */
    .page-content {
      margin-left: 0 !important;
    }


  }


  /* ============================= */
  /* Extra Small Phones (≤ 480px)  */
  /* ============================= */
  @media (max-width: 480px) {

    /* Sidebar smaller and full-height */
    .sidebar {
      width: 200px;
      /* smaller width */
      transform: translateX(-100%);
    }

    .sidebar.open {
      transform: translateX(0);
    }

    /* Reduce brand text size */
    .sidebar .brand-text h1 {
      font-size: 13px;
      line-height: 1.3;
    }

    .sidebar .brand-marks img {
      width: 32px;
      height: 32px;
      border-radius: 8px;
    }

    /* Nav links smaller */
    .sidebar .nav-item .nav-link {
      font-size: 13px;
      padding: 8px 10px;
    }

    /* Bot container compact */
    .sidebar .bot-container {
      padding: 8px;
      font-size: 12px;
    }

    .sidebar .bot-container .bot-title {
      font-size: 12px;
    }

    .sidebar .bot-container .bot-subtitle {
      font-size: 11px;
    }

    .sidebar .bot-container .chatbot-icons {
      gap: 4px;
      font-size: 14px;
    }

    /* Chatbot modal full screen on very small phones */
    .chatbot-modal {
      width: 100% !important;
      height: 100% !important;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      border-radius: 0;
    }

    .chatbot-modal-header {
      font-size: 1rem;
      padding: 12px;
    }

    .chatbot-modal-body {
      font-size: 0.9rem;
      padding: 10px;
    }
  }



  /* Chatbot mobile view */
  /* 📱 Make chatbot same size as nav links */
  @media (max-width: 768px) {
    .sidebar .bot-container {
      padding: 8px 10px;
      /* same as nav link */
      font-size: 13px;
      /* same text size */
      width: 100%;
      /* flexible full width inside sidebar */
      box-sizing: border-box;
    }

    .sidebar .bot-container .bot-title,
    .sidebar .bot-container .bot-subtitle,
    .sidebar .bot-container .chatbot-icons,
    .sidebar .bot-container .chatbot-text {
      font-size: 13px;
      /* match nav link */
    }
  }

  @media (max-width: 480px) {
    .sidebar .bot-container {
      padding: 8px 10px;
      /* keep same as nav link */
      font-size: 13px;
      width: 100%;
      /* flexible full width */
      box-sizing: border-box;
    }

    .sidebar .bot-container .bot-title,
    .sidebar .bot-container .bot-subtitle,
    .sidebar .bot-container .chatbot-icons,
    .sidebar .bot-container .chatbot-text {
      font-size: 13px;
    }
  }
</style>

<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

  <!-- Close button -->
  <button class="close-btn" id="closeSidebar">&times;</button>

  <div class="sidebar-header d-flex align-items-center mb-4 mt-2">
    <div class="brand-marks"><img src="../assets/img/2.png" style="width: 44px; height:44px; border-radius:12px;"></div>
    <div class="brand-text">
      <h1 style="font-size: 15px;">FAMILY DIAGNOSTIC
        <br>
        <span>CENTER</span>
      </h1>

    </div>

  </div>

  <hr>

  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link <?php echo ($currentPage == 'dashboard.admin.php') ? 'active' : 'collapsed'; ?>" href="dashboard.admin.php">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link <?php echo ($currentPage == 'user_management.admin.php') ? 'active' : 'collapsed'; ?> " href="user_management.admin.php">
        <i class="bi bi-people"></i>
        <span>User Management</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link <?php echo ($currentPage == 'personnel_management.admin.php') ? 'active' : 'collapsed'; ?>" href="personnel_management.admin.php">
        <i class="bi bi-clipboard-pulse"></i>
        <span>Personnel Management</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" href="#">
        <i class="bi bi-person-gear"></i>
        <span>Admin Account Management</span>
      </a>
    </li>

  </ul>

</aside><!-- End Sidebar-->