<?php
include "admin_function_DB/links.php";
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once "admin_includes/Head.php"; ?>

<style>
    .custom-close {
        background: none;
        border: none;
        outline: none;
        box-shadow: none;
        color: #333;
        font-size: 15px;
        cursor: pointer;
        padding: 0;
        line-height: 1;
    }

    .custom-close:hover,
    .custom-close:focus {
        background: none !important;
        color: #333 !important;
        outline: none !important;
        box-shadow: none !important;
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
                        <h4 class="mb-0">Doctor Lists</h4>
                    </div>
                    <div class="card-body">
                        <div class="pt-5 pb-2">
                            <div class="d-flex justify-content-center align-items-center">
                                <table id="clinicTable" class="table table-bordered">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th>#</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Specialty</th>
                                            <th>Procedure Fee</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($dInfo): ?>
                                            <?php foreach ($dInfo as $index => $row): ?>
                                                <tr>
                                                    <td><?php echo $index + 1; ?></td>
                                                    <td><?php echo htmlspecialchars($row['firstname']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['lastname']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['specialty']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['price']); ?></td>
                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#update_doctor<?php echo $row['doctor_id']; ?>">
                                                            Update
                                                        </a>
                                                        <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $row['doctor_id']; ?>">
                                                            Delete
                                                        </button>
                                                    </td>
                                                </tr>

                                                <!-- Update Doctor Modal -->
                                                <div class="modal fade" id="update_doctor<?php echo $row['doctor_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="updateDoctorLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content p-4">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Update Doctor</h5>
                                                                <button type="close" class="custom-close" data-bs-dismiss="modal" aria-label="Close"><i class="fi fi-bs-cross"></i></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="../Auth/Admin/Update_Doctor.auth.php" method="POST">
                                                                    <input type="hidden" name="doctor_id" value="<?php echo $row['doctor_id']; ?>">

                                                                    <div class="form-group w-100">
                                                                        <label class="font-weight-bold">Doctor First Name</label>
                                                                        <input type="text" class="form-control" name="firstname" value="<?php echo htmlspecialchars($row['firstname']); ?>" required>
                                                                    </div>

                                                                    <div class="form-group w-100">
                                                                        <label class="font-weight-bold">Doctor Last Name</label>
                                                                        <input type="text" class="form-control" name="lastname" value="<?php echo htmlspecialchars($row['lastname']); ?>" required>
                                                                    </div>

                                                                    <div class="form-group w-100">
                                                                        <label class="font-weight-bold">Doctor Specialty</label>
                                                                        <select class="form-select form-control" name="specialty" required>
                                                                            <?php
                                                                            $specialties = ["2D Echo", "Ultrasound", "Vascular Studies", "DNA", "ECG", "Clinical Laboratory", "XRAY"];
                                                                            foreach ($specialties as $spec): ?>
                                                                                <option value="<?php echo $spec; ?>" <?php if ($row['specialty'] == $spec) echo "selected"; ?>>
                                                                                    <?php echo $spec; ?>
                                                                                </option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>

                                                                    <div class="form-group w-100">
                                                                        <label class="font-weight-bold">Doctor Price</label>
                                                                        <input type="text" class="form-control" name="price" value="<?php echo htmlspecialchars($row['price']); ?>" required>
                                                                    </div>

                                                                    <div class="form-group w-100">
                                                                        <button type="submit" class="btn btn-primary btn-block" name="update_doctor">Update Doctor</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center">No results found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </main><!-- End #main -->


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
    <script src="../Assets/vendor/apexcharts/apexcharts.min.js">
    </script>
    <script src="../Assets/vendor/bootstrap/js/bootstrap.bundle.min.js">
    </script>
    <script src="../Assets/vendor/chart.js/chart.umd.js"></script>
    <script src="../Assets/vendor/echarts/echarts.min.js"></script>
    <script src="../Assets/vendor/quill/quill.js"></script>
    <script src="../Assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="../Assets/vendor/php-email-form/validate.js"></script>

    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>


    <!-- Template Main JS File -->
    <script src="../assets/js/main.js"></script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php
    include_once "admin_includes/Alert.php";
    ?>


    <!--Script for Sweet Alert of Delete Button-->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const deleteButtons = document.querySelectorAll(".delete-btn");

            deleteButtons.forEach(button => {
                button.addEventListener("click", function() {
                    let doctorId = this.getAttribute("data-id");

                    Swal.fire({
                        title: "Are you sure?",
                        text: "This doctor will be permanently deleted!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to delete page
                            window.location.href = "../Auth/Admin/Delete_Doctor.auth.php?doctor_id=" + doctorId;
                        }
                    });
                });
            });
        });
    </script>

</body>

</html>