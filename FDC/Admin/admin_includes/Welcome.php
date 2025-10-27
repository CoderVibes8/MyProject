<section class="welcome">
    <div>
        <h2>Welcome back, Admin <?php echo $full_name; ?></h2>
        <p>Here’s what’s next with your care at <strong>Family Diagnostic Center</strong>.</p>
    </div>
    <div class="grow"></div>
    <div class="actions">
        <!-- <button class="welcome-btn alt" type="button" id="startVideo">Analytics Review
            <br>
            <span>Tap to view.</span>
        </button> -->
        <button class="welcome-btn" type="button" id="exportPDF">Download Report
            <br>
            <i class="fas fa-file-pdf"></i>
            <span style="color: #f0f8ff;">Pdf File</span>
        </button>
    </div>

    <script>
        document.getElementById("exportPDF").addEventListener("click", async () => {
            const {
                jsPDF
            } = window.jspdf;
            const pdf = new jsPDF("p", "mm", "a4");

            // Select all chart containers
            const chartElements = document.querySelectorAll(".card");

            for (let i = 0; i < chartElements.length; i++) {
                const canvas = await html2canvas(chartElements[i], {
                    scale: 2
                });
                const imgData = canvas.toDataURL("image/png");

                // Calculate image size to fit A4
                const imgWidth = 190;
                const pageHeight = 295;
                const imgHeight = (canvas.height * imgWidth) / canvas.width;

                let position = 10;
                if (i > 0) pdf.addPage();
                pdf.addImage(imgData, "PNG", 10, position, imgWidth, imgHeight);
            }

            pdf.save("analytics_report.pdf");
        });
    </script>
</section>