<?php
include_once "admin_function_DB/links.php";
?>

<!DOCTYPE html>
<html lang="en">

<?php include_once "admin_includes/Head.php"; ?>

<style>
    .side-close {
        background: transparent;
        border: none;
        font-size: 20px;
        line-height: 1;
        color: #38BDF8;
        cursor: pointer;
        transition: color 0.3s ease;
        margin: 0 0 0 12px;
    }



    .side-close:hover {
        color: #007bff;
        background: none;
    }



    /* Container */
    .chart-container {
        max-width: 1200px;
        margin: 30px auto;
        padding: 20px;
        font-family: "Segoe UI", Roboto, sans-serif;
        color: #1e293b;
    }

    /* KPI Section */
    .kpis {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 30px;
    }

    .kpi {
        background: #fff;
        border-radius: 12px;
        padding: 18px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .kpi:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
    }

    .k {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .v {
        font-size: 26px;
        font-weight: 700;
        color: #0f172a;
    }

    .trend {
        font-size: 13px;
        margin-top: 6px;
    }

    .trend.up {
        color: #22c55e;
    }

    .trend.down {
        color: #ef4444;
    }

    /* Dots */
    .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }

    .dot.blue {
        background: #38bdf8;
    }

    .dot.green {
        background: #22c55e;
    }

    .dot.amber {
        background: #facc15;
    }

    .dot.rose {
        background: #f43f5e;
    }



    /* Card */
    .card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .card h3 {
        margin-bottom: 4px;
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }

    .card p {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 16px;
    }

    /* Bar Chart */
    .chart .x {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        height: 180px;
        margin-bottom: 8px;
    }

    .chart .bar {
        width: 14px;
        border-radius: 6px;
        background: linear-gradient(to top, #38bdf8, #0ea5e9);
        transition: height 0.3s ease;
    }

    .bar:hover {
        opacity: 0.85;
        cursor: pointer;
    }

    .b1 {
        height: 70px;
    }

    .b2 {
        height: 90px;
    }

    .b3 {
        height: 50px;
    }

    .b4 {
        height: 120px;
    }

    .b5 {
        height: 95px;
    }

    .b6 {
        height: 140px;
    }

    .b7 {
        height: 100px;
    }

    .b8 {
        height: 150px;
    }

    .b9 {
        height: 80px;
    }

    .b10 {
        height: 130px;
    }

    .b11 {
        height: 60px;
    }

    .b12 {
        height: 110px;
    }

    .xlabels {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: #64748b;
    }

    /* Line Chart */
    .linechart {
        margin-top: 24px;
        position: relative;
        height: 200px;
        background: #f8fafc;
        border-radius: 10px;
        padding: 16px;
    }

    .linechart .gridlines {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 16px;
        right: 16px;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
    }

    .linechart .gridlines span {
        border-top: 1px dashed #cbd5e1;
    }

    .linechart .poly {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
    }

    .series {
        display: flex;
        justify-content: space-between;
        position: relative;
        height: 100%;
    }

    .point {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: flex-end;
    }

    .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        position: relative;
        background: var(--c, #38bdf8);
        top: var(--y, 0%);
    }

    /* Legend */
    .legend {
        margin-top: 12px;
        display: flex;
        gap: 16px;
        font-size: 13px;
        color: #475569;
    }

    .legend .dot {
        margin-right: 5px;
    }

    /* Breakdown Section */
    .list .row {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        gap: 10px;
    }

    .list .label {
        flex: 1;
        font-size: 14px;
        font-weight: 500;
    }

    .meter {
        flex: 2;
        height: 8px;
        background: #e2e8f0;
        border-radius: 5px;
        overflow: hidden;
    }

    .meter div {
        height: 100%;
        border-radius: 5px;
    }

    .fill-blue {
        background: #38bdf8;
        width: 62%;
    }

    .fill-green {
        background: #22c55e;
        width: 48%;
    }

    .fill-amber {
        background: #facc15;
        width: 36%;
    }

    .fill-rose {
        background: #f43f5e;
        width: 22%;
    }

    .sub {
        font-size: 12px;
        color: #64748b;
    }

    /* === 📱 MOBILE (≤480px) === */
    @media (max-width: 480px) {
        .chart-container {
            padding: 12px;
            margin: 10px;
        }

        .kpis {
            grid-template-columns: 1fr;
            /* Single column for small screens */
            gap: 12px;
            width: 300px;
            margin-left: -12px;
        }

        .kpi {
            padding: 14px;
            text-align: center;
        }

        .kpi .v {
            font-size: 20px;
        }

        .card {
            padding: 14px;
            margin-bottom: 16px;
            margin-left: -20px;
            width: 320px;
        }

        .card h3,
        .card h2 {
            font-size: 16px;
            text-align: center;
        }

        select.form-select,
        select {
            width: 100% !important;
            margin-bottom: 10px;
        }

        #lineChart,
        #chart {
            height: 260px !important;
        }
    }

    /* === 💻 TABLET (481px – 768px) === */
    @media (min-width: 481px) and (max-width: 768px) {
        .chart-container {
            max-width: 95%;
            padding: 16px;
        }

        .kpis {
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }

        .kpi .v {
            font-size: 22px;
        }

        .card {
            padding: 18px;
            margin-bottom: 20px;
        }

        .chart-bar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        #lineChart,
        #chart {
            height: 320px !important;
        }
    }

    /* === 🖥 SMALL LAPTOP (769px – 1020px) === */
    @media (min-width: 769px) and (max-width: 1020px) {
        .chart-container {
            margin-left: 300px;
            max-width: 1000px;
            padding: 20px;
        }

        .kpis {
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .card {
            padding: 20px;
        }



        #lineChart,
        #chart {
            height: 380px !important;
        }
    }
</style>

<body>


    <?php include 'admin_includes/Header.php'; ?>
    <?php include 'admin_includes/Sidebar.php'; ?>

    <main id="main" class="main">
        <?php include_once 'admin_includes/Welcome.php'; ?>

        <section class="section dashboard mt-4">
        </section>

        <div class="chart-container">

            <!-- KPI Cards -->
            <section class="kpis">
                <div class="kpi">
                    <div class="k">
                        <span class="dot rose"></span>Total Revenue
                    </div>
                    <div class="v">₱<?= number_format($totalRevenue, 2) ?></div>
                    <div class="trend <?= $trendDirection_rev ?>">
                        <?= sprintf("%+.1f%% vs last week", $trendPercent_rev) ?>
                    </div>
                </div>
                <div class="kpi">
                    <div class="k">
                        <span class="dot rose"></span>Today's Revenue
                    </div>
                    <div class="v">₱<?= number_format($todaysRevenue, 2) ?></div>
                    <div class="trend <?= $trendDirection_today ?>">
                        <?= sprintf("%+.1f%%", $trendPercent_today) ?>
                    </div>
                </div>
                <div class="kpi">
                    <div class="k">
                        <span class="dot blue"></span>Total Appointments
                    </div>
                    <div class="v"><?= $totalAppointments ?></div>
                    <div class="trend <?= $trendDirection ?>">
                        <?= sprintf("%+.1f%% vs last week", $trendPercent) ?>
                    </div>
                </div>
                <div class="kpi">
                    <div class="k">
                        <span class="dot amber"></span>Total Users
                    </div>
                    <div class="v"><?= number_format($totalUsers) ?></div>
                    <div class="trend <?= $trendDirection_user ?>">
                        <?= sprintf("+%.1f%%", $trendPercent_user) ?>
                    </div>
                </div>
                <div class="kpi">
                    <div class="k">
                        <span class="dot green"></span>Total Doctors
                    </div>
                    <div class="v"><?= number_format($totalDoctors) ?></div>
                </div>
            </section>

            <!-- Charts and breakdowns -->
            <section class="chart-bar">
                <!-- Left: charts -->
                <div class="card">

                    <h2>Monthly Appointments Per Specialty</h2>

                    <!-- Year Filter -->
                    <select id="yearSelectLine" class="form-select mb-3" style="width:200px;">
                        <option value="">Select Year</option>
                    </select>

                    <!-- Chart Container -->
                    <div id="lineChart"></div>

                    <script>
                        document.addEventListener("DOMContentLoaded", () => {
                            const yearSelectLine = document.getElementById("yearSelectLine");
                            let line_chart;

                            // Get current year automatically
                            const currentYear = new Date().getFullYear();

                            // Fetch available years from backend
                            fetch("../Auth/Admin/get_linechart_data.php?years=1")
                                .then(res => res.json())
                                .then(years => {
                                    yearSelectLine.innerHTML = ''; // clear first
                                    years.forEach(y => {
                                        yearSelectLine.innerHTML += `<option value="${y.year}">${y.year}</option>`;
                                    });

                                    // Automatically select and load current year if available
                                    const match = years.find(y => parseInt(y.year) === currentYear);
                                    const selectedYear = match ? currentYear : years[0].year;

                                    yearSelectLine.value = selectedYear;
                                    loadChart(selectedYear);
                                });

                            // When user changes the year manually
                            yearSelectLine.addEventListener("change", () => {
                                if (yearSelectLine.value) {
                                    loadChart(yearSelectLine.value);
                                }
                            });

                            function loadChart(year) {
                                fetch(`../Auth/Admin/get_linechart_data.php?year=${year}`)
                                    .then(res => res.json())
                                    .then(data => {
                                        console.log(data); // ✅ Debug output

                                        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                                        const specialties = [...new Set(data.map(item => item.specialty))];

                                        const series = specialties.map(spec => {
                                            let monthlyData = Array(12).fill(0);
                                            data.filter(d => d.specialty === spec).forEach(d => {
                                                monthlyData[d.month - 1] = parseInt(d.total);
                                            });
                                            return {
                                                name: spec,
                                                data: monthlyData
                                            };
                                        });

                                        if (line_chart) line_chart.destroy();

                                        line_chart = new ApexCharts(document.querySelector("#lineChart"), {
                                            chart: {
                                                type: "line",
                                                height: 400
                                            },
                                            series: series,
                                            xaxis: {
                                                categories: months
                                            },
                                            stroke: {
                                                curve: "smooth",
                                                width: 3
                                            },
                                            markers: {
                                                size: 5
                                            },
                                            title: {
                                                text: `Monthly Appointments by Type (${year})`,
                                                align: "center"
                                            }
                                        });

                                        line_chart.render();
                                    });
                            }
                        });
                    </script>

                </div>

                <!-- Right: breakdowns -->
                <aside class="card">
                    <h3>Breakdown</h3>
                    <!-- <p>Top-level distribution across your platform.</p>

                    <div class="list">
                        <div class="row">
                            <span class="label">By Department</span>
                            <div class="meter">
                                <div class="fill-blue"></div>
                            </div>
                            <span class="sub">62% General</span>
                        </div>
                        <div class="row">
                            <span class="label">Telehealth Share</span>
                            <div class="meter">
                                <div class="fill-green"></div>
                            </div>
                            <span class="sub">48% Online</span>
                        </div>
                        <div class="row">
                            <span class="label">Lab-Linked Visits</span>
                            <div class="meter">
                                <div class="fill-amber"></div>
                            </div>
                            <span class="sub">36% With Labs</span>
                        </div>
                        <div class="row">
                            <span class="label">No‑show Rate</span>
                            <div class="meter">
                                <div class="fill-rose"></div>
                            </div>
                            <span class="sub">22% Missed</span>
                        </div>
                    </div> -->

                    <h3 style="margin-top:12px;">Doctor Performances</h3>
                    <div class="list">
                        <?php foreach ($doctors as $index => $doc):
                            $percent = $totalAppts > 0 ? ($doc["appt_count"] / $totalAppts) * 100 : 0;
                            $color = $colors[$index % count($colors)];
                        ?>
                            <div class="row">
                                <span class="label"><?= htmlspecialchars($doc["name"]) ?></span>
                                <div class="meter">
                                    <div class="<?= $color ?>" style="width:<?= round($percent, 1) ?>%;"></div>
                                </div>
                                <span class="sub"><?= $doc["appt_count"] ?> appts</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </aside>

                <div class="card">
                    <h2>Appointments by Type</h2>

                    <label for="yearSelect">Year:</label>
                    <select id="yearSelect"></select>

                    <label for="monthSelect">Month:</label>
                    <select id="monthSelect"></select>

                    <div id="chart"></div>

                    <script>
                        const yearSelect = document.getElementById("yearSelect");
                        const monthSelect = document.getElementById("monthSelect");

                        // Get current date
                        const currentYear = new Date().getFullYear();
                        const currentMonth = new Date().getMonth() + 1;

                        // Initialize chart
                        let chartOptions = {
                            chart: {
                                type: 'bar',
                                height: 200
                            },
                            series: [{
                                name: 'Appointments',
                                data: []
                            }],
                            xaxis: {
                                categories: []
                            }
                        };
                        let chart = new ApexCharts(document.querySelector("#chart"), chartOptions);
                        chart.render();

                        // Load years
                        fetch("../Auth/Admin/get_data.php?years=1")
                            .then(res => res.json())
                            .then(years => {
                                yearSelect.innerHTML = '';
                                years.forEach(y => {
                                    yearSelect.innerHTML += `<option value="${y.year}">${y.year}</option>`;
                                });

                                // Auto-select current year (or first available)
                                const match = years.find(y => parseInt(y.year) === currentYear);
                                const selectedYear = match ? currentYear : years[0].year;
                                yearSelect.value = selectedYear;

                                // Then load months for that year
                                loadMonths(selectedYear);
                            });

                        // Load months when year changes
                        yearSelect.addEventListener("change", () => {
                            const year = yearSelect.value;
                            if (!year) return;
                            loadMonths(year);
                        });

                        function loadMonths(year) {
                            fetch("../Auth/Admin/get_data.php?months=" + year)
                                .then(res => res.json())
                                .then(months => {
                                    monthSelect.innerHTML = '';
                                    months.forEach(m => {
                                        const monthName = new Date(0, m.month - 1).toLocaleString('default', {
                                            month: 'long'
                                        });
                                        monthSelect.innerHTML += `<option value="${m.month}">${monthName}</option>`;
                                    });

                                    // Auto-select current month (or first available)
                                    const match = months.find(m => parseInt(m.month) === currentMonth);
                                    const selectedMonth = match ? currentMonth : months[0].month;
                                    monthSelect.value = selectedMonth;

                                    // Load chart automatically for current year & month
                                    loadChart(year, selectedMonth);
                                });
                        }

                        // When user changes month manually
                        monthSelect.addEventListener("change", () => {
                            const year = yearSelect.value;
                            const month = monthSelect.value;
                            if (!year || !month) return;
                            loadChart(year, month);
                        });

                        function loadChart(year, month) {
                            fetch(`../Auth/Admin/get_data.php?year=${year}&month=${month}`)
                                .then(res => res.json())
                                .then(data => {
                                    const categories = data.map(row => row.specialty);
                                    const counts = data.map(row => row.total);

                                    chart.updateOptions({
                                        xaxis: {
                                            categories: categories
                                        },
                                        title: {
                                            text: `Appointments by Type — ${new Date(year, month - 1).toLocaleString('default', { month: 'long', year: 'numeric' })}`,
                                            align: 'center'
                                        }
                                    });
                                    chart.updateSeries([{
                                        name: "Appointments",
                                        data: counts
                                    }]);
                                });
                        }
                    </script>
                </div>

            </section>


        </div>
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

    <!-- Template Main JS File -->
    <script src="../assets/js/main.js"></script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php
    include_once "admin_includes/Alert.php";
    ?>

</body>

</html>