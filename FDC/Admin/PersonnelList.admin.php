<?php
include "admin_function_DB/links.php";
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once "admin_includes/Head.php"; ?>

<style>
    .card-header {
        margin: -12px;
    }

    .card-header h4 {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* 📊 Table */
    .table {
        width: 980px;
        border-collapse: collapse;
        margin-top: 10px;
        font-size: 14px;
    }

    .table thead {
        background: #f3f4f6;
    }

    .table th {
        text-align: center;
        padding: 12px;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #dee2e6;
    }

    .table td {
        text-align: center;
        padding: 10px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f1f1;
        color: #555;
    }

    /* 🧩 Hover effect */
    .table tbody tr:hover {
        background-color: #f9fafb;
        transform: scale(1.01);
        transition: all 0.2s ease-in-out;
    }

    /* ✅ "View Profile" Button */
    .table .btn {
        font-size: 13px;
        padding: 5px 10px;
        border-radius: 20px;
        transition: all 0.2s;
    }

    .table .btn-success {
        background: #16a34a;
        border: none;
    }

    .table .btn-success:hover {
        background: #15803d;
        transform: translateY(-2px);
    }

    /* 🚫 Empty State */
    .table td[colspan] {
        color: #888;
        font-style: italic;
        padding: 20px;
    }

    /* 📱 Mobile Optimization (≤480px) */
    @media (max-width: 480px) {

        .card-header h4 {
            font-size: 16px;
        }

        .table {
            font-size: 12px;
        }

        .table thead {
            display: none;
            /* hide header */
        }

        .table,
        .table tbody,
        .table tr,
        .table td {
            display: block;
            width: 100%;
        }

        .table tr {
            margin-bottom: 12px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            background: #fff;
            padding: 10px;
        }

        .table td {
            text-align: left;
            padding: 8px 10px;
            border: none;
            position: relative;
        }

        .table td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #333;
            display: block;
            margin-bottom: 4px;
        }

        .btn {
            width: 100%;
            text-align: center;
        }


    }



    .card-header {
        margin: -12px;
    }

    .card-header h4 {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* 📊 Table */
    .table {
        width: 980px;
        border-collapse: collapse;
        margin-top: 10px;
        font-size: 14px;
    }

    .table thead {
        background: #f3f4f6;
    }

    .table th {
        text-align: center;
        padding: 12px;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #dee2e6;
    }

    .table td {
        text-align: center;
        padding: 10px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f1f1;
        color: #555;
    }

    /* 🧩 Hover effect */
    .table tbody tr:hover {
        background-color: #f9fafb;
        transform: scale(1.01);
        transition: all 0.2s ease-in-out;
    }

    /* ✅ "View Profile" Button */
    .table .btn {
        font-size: 13px;
        padding: 5px 10px;
        border-radius: 20px;
        transition: all 0.2s;
    }

    .table .btn-success {
        background: #16a34a;
        border: none;
    }

    .table .btn-success:hover {
        background: #15803d;
        transform: translateY(-2px);
    }

    /* 🚫 Empty State */
    .table td[colspan] {
        color: #888;
        font-style: italic;
        padding: 20px;
    }

    /* 📱 ≤480px — Mobile view */
    @media (max-width: 480px) {
        .card-header h4 {
            font-size: 16px;
        }

        .table {
            font-size: 12px;
        }

        .table thead {
            display: none;
        }

        .table,
        .table tbody,
        .table tr,
        .table td {
            display: block;
            width: 100%;
        }

        .table tr {
            margin-bottom: 12px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            background: #fff;
            padding: 10px;
        }

        .table td {
            text-align: left;
            padding: 8px 10px;
            border: none;
            position: relative;
        }

        .table td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #333;
            display: block;
            margin-bottom: 4px;
        }

        .btn {
            width: 100%;
            text-align: center;
        }
    }

    /* 📲 481px - 768px — Small tablets */
    @media (min-width: 481px) and (max-width: 768px) {
        .card-header h4 {
            font-size: 17px;
        }

        .table {
            width: 100%;
            font-size: 13px;
            overflow-x: auto;
            display: block;
        }

        .table thead th {
            padding: 10px 6px;
        }

        .table td {
            padding: 8px 6px;
        }

        .btn {
            font-size: 12px;
            padding: 4px 8px;
        }

        .table .btn-success {
            border-radius: 16px;
        }
    }

    /* 💻 769px - 1020px — Small desktops / large tablets */
    @media (min-width: 769px) and (max-width: 1020px) {
        .card-header h4 {
            font-size: 18px;
        }

        .table {
            width: 100%;
            font-size: 14px;
        }

        .table th,
        .table td {
            padding: 10px;
        }

        .table .btn {
            font-size: 13px;
            padding: 5px 9px;
        }

        .card {
            margin: 0 auto;
            width: 95%;
            margin-left: 320px;
        }
    }
</style>

<body>


    <?php include "admin_includes/Header.php"; ?>
    <?php include "admin_includes/Sidebar.php"; ?>

    <main id="main" class="main">
        <?php include "admin_includes/Welcome.php"; ?>

        <section class="section dashboard mt-4">

            <div class="row">
                <div class="card h-100 w-100 mt-3">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">Overall Personnel Lists</h4>
                    </div>
                    <div class="card-body">
                        <div class="pt-5 pb-2">
                            <div class="d-flex justify-content-center align-items-center">
                                <table id="clinicTable" class="table table-bordered">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th>#</th>
                                            <th>Profile</th>
                                            <th>Doctor's Name</th>
                                            <th>Email</th>
                                            <th>Phone Number</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($overallDoctorRetrieval): ?>
                                            <?php foreach ($overallDoctorRetrieval as $index => $row): ?>
                                                <tr>
                                                    <td><?= $index + 1; ?></td>
                                                    <td>
                                                        <img src="<?= '../uploads/' . htmlspecialchars($row['profile_pic']); ?>"
                                                            alt="Doctor Image"
                                                            width="50"
                                                            height="50"
                                                            class="rounded-circle"
                                                            style="cursor: pointer;"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#profileModal<?= $index; ?>">
                                                    </td>
                                                    <td><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></td>
                                                    <td><?= htmlspecialchars($row['email']); ?></td>
                                                    <td><?= htmlspecialchars($row['phone']); ?></td>
                                                    <td>
                                                        <?php if ($row["status"] === "activated") { ?>
                                                            <span class="text-success fw-bold"><?= htmlspecialchars($row["status"]); ?></span>
                                                        <?php } elseif ($row["status"] === "not-activated") { ?>
                                                            <span class="text-warning fw-bold"><?= htmlspecialchars($row["status"]); ?></span>
                                                        <?php } else { ?>
                                                            <span class="text-danger fw-bold"><?= htmlspecialchars($row["status"]); ?></span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($row["status"] === "restricted"): ?>
                                                            <button class="btn btn-success btn-sm restrictBtn"
                                                                data-id="<?= $row['doc_id']; ?>"
                                                                data-status="restricted">Unrestrict</button>
                                                        <?php else: ?>
                                                            <button class="btn btn-warning btn-sm restrictBtn"
                                                                data-id="<?= $row['doc_id']; ?>"
                                                                data-status="activated">Restrict</button>
                                                        <?php endif; ?>

                                                        <button class="btn btn-danger btn-sm deleteBtn" data-id="<?= $row['doc_id']; ?>">Delete</button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center">No results found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>

                                <!-- SweetAlert -->
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {

                                        // === Restrict / Unrestrict Toggle ===
                                        document.querySelectorAll('.restrictBtn').forEach(button => {
                                            button.addEventListener('click', function() {
                                                const doctorId = this.getAttribute('data-id');
                                                const currentStatus = this.getAttribute('data-status');

                                                const isRestricted = currentStatus === 'restricted';
                                                const action = isRestricted ? 'unrestrict' : 'restrict';
                                                const title = isRestricted ? 'Unrestrict this personnel?' : 'Restrict this personnel?';
                                                const text = isRestricted ?
                                                    'This will restore their access.' :
                                                    'This will limit their access.';
                                                const confirmText = isRestricted ? 'Yes, unrestrict' : 'Yes, restrict';
                                                const confirmColor = isRestricted ? '#198754' : '#f0ad4e';

                                                Swal.fire({
                                                    title: title,
                                                    text: text,
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: confirmColor,
                                                    cancelButtonColor: '#6c757d',
                                                    confirmButtonText: confirmText,
                                                    cancelButtonText: 'Cancel'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        console.log(`${action}ing personnel with ID:`, doctorId);
                                                        fetch('../Auth/Admin/Restrict_Personnel.php', {
                                                                method: 'POST',
                                                                headers: {
                                                                    'Content-Type': 'application/x-www-form-urlencoded'
                                                                },
                                                                body: `doc_id=${doctorId}&action=${action}`
                                                            })
                                                            .then(res => res.json())
                                                            .then(data => {
                                                                if (data.success) {
                                                                    Swal.fire(
                                                                        isRestricted ? 'Unrestricted!' : 'Restricted!',
                                                                        data.message,
                                                                        'success'
                                                                    ).then(() => {
                                                                        location.reload();
                                                                    });
                                                                } else {
                                                                    Swal.fire('Error!', data.message, 'error');
                                                                }
                                                            })
                                                            .catch(() => {
                                                                Swal.fire('Error!', 'Something went wrong.', 'error');
                                                            });
                                                    }
                                                });
                                            });
                                        });

                                        // === Delete Doctor ===
                                        document.querySelectorAll('.deleteBtn').forEach(button => {
                                            button.addEventListener('click', function() {
                                                const doctorId = this.getAttribute('data-id');

                                                Swal.fire({
                                                    title: 'Delete this personnel?',
                                                    text: "You won’t be able to undo this!",
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#d33',
                                                    cancelButtonColor: '#6c757d',
                                                    confirmButtonText: 'Yes, delete',
                                                    cancelButtonText: 'Cancel'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        console.log('Deleting personnel with ID:', doctorId);
                                                        fetch('../Auth/Admin/Delete_Personnel.php', {
                                                                method: 'POST',
                                                                headers: {
                                                                    'Content-Type': 'application/x-www-form-urlencoded'
                                                                },
                                                                body: `doc_id=${doctorId}`
                                                            })
                                                            .then(res => res.json())
                                                            .then(data => {
                                                                if (data.success) {
                                                                    Swal.fire('Deleted!', data.message, 'success').then(() => {
                                                                        location.reload();
                                                                    });
                                                                } else {
                                                                    Swal.fire('Error!', data.message, 'error');
                                                                }
                                                            })
                                                            .catch(() => {
                                                                Swal.fire('Error!', 'Something went wrong.', 'error');
                                                            });
                                                    }
                                                });
                                            });
                                        });

                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </main><!-- End #main -->

    <!-- Modal for Enlarged Profile Image -->
    <div class="modal fade" id="profileModal<?php echo $index; ?>" tabindex="-1" aria-labelledby="profileModalLabel<?php echo $index; ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel<?php echo $index; ?>">Doctor Profile Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="<?php echo '../uploads/' . htmlspecialchars($row['profile_pic']); ?>"
                        alt="Doctor Image"
                        class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>


    <!-- ======= Footer ======= -->
    <?php include "admin_includes/footer.php"; ?>


    <!-- jQuery and Bootstrap JS -->
    <!-- Include jQuery (Required for Bootstrap Modals to Work) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Include jQuery (Required for Bootstrap Modals to Work) -->


    <!-- Bootstrap JS (Ensure it's included) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Js for Modal -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Handle switching between modals
            document.querySelectorAll(".switch-modal").forEach(link => {
                link.addEventListener("click", function(event) {
                    event.preventDefault();
                    let targetModal = this.getAttribute("data-target");

                    $(".modal").modal("hide"); // Hide any open modal

                    $(".modal").on("hidden.bs.modal", function() {
                        $(targetModal).modal("show"); // Show the target modal
                        $(".modal").off("hidden.bs.modal"); // Prevent multiple event bindings
                    });
                });
            });

            // Ensure modals remove backdrops properly when closed
            $(".modal").on("hidden.bs.modal", function() {
                $("body").removeClass("modal-open"); // Remove class preventing scroll
                $(".modal-backdrop").remove(); // Remove any lingering modal-backdrop
            });

            // Fix password toggle visibility
            document.querySelectorAll(".toggle-password").forEach(button => {
                button.addEventListener("click", function() {
                    let input = this.closest(".input-group").querySelector("input");
                    input.type = input.type === "password" ? "text" : "password";
                    this.firstElementChild.classList.toggle("fa-eye-slash");
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#clinicTable').DataTable({
                "paging": true, // Enables pagination
                "searching": true, // Enables search
                "ordering": true, // Enables column sorting
                "lengthMenu": [5, 10, 25, 50], // Dropdown for number of rows per page
                "language": {
                    "search": "Search Clinic:", // Customizing search box placeholder
                    "lengthMenu": "Show _MENU_ entries per page"
                }
            });
        });
    </script>

    <!-- Vendor JS Files -->
    <script src="../assets/vendor/apexcharts/apexcharts.min.js">
    </script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js">
    </script>
    <script src="../assets/vendor/chart.js/chart.umd.js"></script>
    <script src="../assets/vendor/echarts/echarts.min.js"></script>
    <script src="../assets/vendor/quill/quill.js"></script>
    <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="../assets/vendor/php-email-form/validate.js"></script>

    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>


    <!-- Template Main JS File -->
    <script src="../assets/js/main.js"></script>

    <?php
    include_once "admin_includes/Alert.php";
    ?>

</body>

</html>