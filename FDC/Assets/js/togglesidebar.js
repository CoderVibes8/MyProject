document.addEventListener("DOMContentLoaded", () => {
  const sidebarToggle = document.getElementById("sidebarToggle");
  const sidebar = document.getElementById("sidebar");
  const closeSidebarBtn = document.getElementById("closeSidebar");
  const overlay = document.getElementById("sidebarOverlay");

  function closeSidebar() {
    sidebar.classList.remove("open");
    sidebarToggle.classList.remove("active");
  }

  // Open / toggle sidebar
  sidebarToggle.addEventListener("click", () => {
    sidebar.classList.toggle("open");
    sidebarToggle.classList.toggle("active");
  });

  // Close on X button
  closeSidebarBtn.addEventListener("click", closeSidebar);

  // Close when clicking outside (overlay)
  overlay.addEventListener("click", closeSidebar);

  // Optional: auto close when clicking a nav link (on mobile)
  document.querySelectorAll("#sidebar-nav a").forEach(link => {
    link.addEventListener("click", () => {
      if (window.innerWidth <= 768) {
        closeSidebar();
      }
    });
  });
});
