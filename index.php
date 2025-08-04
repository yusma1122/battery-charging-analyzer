<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Battery Charging Analyzer</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.4.1/papaparse.min.js"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
          Oxygen, Ubuntu, Cantarell, sans-serif;
        background-color: #f8fafc;
        color: #334155;
        line-height: 1.6;
      }

      .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
      }

      .header {
        text-align: center;
        margin-bottom: 3rem;
      }

      .header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
      }

      .header p {
        color: #64748b;
        font-size: 1.1rem;
      }

      .upload-section {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
      }

      .upload-area {
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        padding: 3rem 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
      }

      .upload-area:hover {
        border-color: #3b82f6;
        background-color: #f8fafc;
      }

      .upload-area.dragover {
        border-color: #3b82f6;
        background-color: #eff6ff;
      }

      .upload-icon {
        font-size: 3rem;
        color: #94a3b8;
        margin-bottom: 1rem;
      }

      .upload-text {
        font-size: 1.1rem;
        font-weight: 500;
        color: #475569;
        margin-bottom: 0.5rem;
      }

      .upload-subtext {
        color: #64748b;
        font-size: 0.9rem;
      }

      .file-input {
        display: none;
      }

      .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
      }

      .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
      }

      .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
      }

      .stat-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
      }

      .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        font-size: 1.2rem;
      }

      .stat-icon.blue {
        background-color: #dbeafe;
        color: #2563eb;
      }

      .stat-icon.green {
        background-color: #dcfce7;
        color: #16a34a;
      }

      .stat-icon.purple {
        background-color: #f3e8ff;
        color: #9333ea;
      }

      .stat-icon.orange {
        background-color: #fed7aa;
        color: #ea580c;
      }

      .stat-label {
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 500;
      }

      .stat-description {
        font-size: 0.8rem;
        color: #94a3b8;
        margin-top: 0.5rem;
        line-height: 1.4;
      }

      .calculation-info {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
      }

      .info-header {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
        color: #374151;
      }

      .calculation-info p {
        margin: 0.5rem 0;
        color: #4b5563;
        line-height: 1.5;
      }

      .calculation-info code {
        background: #e5e7eb;
        padding: 0.2rem 0.4rem;
        border-radius: 4px;
        font-family: "Courier New", monospace;
        font-size: 0.9rem;
      }

      .note {
        background: #fffbeb;
        border: 1px solid #fed7aa;
        border-radius: 6px;
        padding: 0.75rem;
        margin-top: 0.75rem;
        display: flex;
        align-items: flex-start;
      }

      .note small {
        color: #92400e;
        line-height: 1.4;
      }

      .footer {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        margin-top: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
      }

      .footer-content {
        text-align: center;
      }

      .creator-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
      }

      .creator-name {
        font-size: 1.1rem;
        color: #1e293b;
        display: flex;
        align-items: center;
      }

      .motivation {
        color: #64748b;
        font-size: 1rem;
        display: flex;
        align-items: center;
      }

      .social-links {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
      }

      .social-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #475569;
        text-decoration: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
      }

      .social-link:hover {
        background-color: #f1f5f9;
        color: #3b82f6;
        transform: translateY(-1px);
      }

      .github-link:hover {
        background-color: #e5e7eb; /* abu-abu netral */
        color: #000000; /* hitam khas GitHub */
        transform: translateY(-1px);
      }

      .social-link i {
        font-size: 1.1rem;
      }

      .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
      }

      .chart-container {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
      }

      .chart-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
      }

      .chart-header h3 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1e293b;
        margin-left: 0.5rem;
      }

      .calculator-section {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
      }

      .calculator-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
      }

      .calculator-header h3 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1e293b;
        margin-left: 0.5rem;
      }

      .calculator-form {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 1rem;
        align-items: end;
        margin-bottom: 1rem;
      }

      .input-group {
        display: flex;
        flex-direction: column;
      }

      .input-group label {
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #374151;
        font-size: 0.9rem;
      }

      .input-group input {
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
      }

      .input-group input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
      }

      .calculate-btn {
        background-color: #3b82f6;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
      }

      .calculate-btn:hover {
        background-color: #2563eb;
        transform: translateY(-1px);
      }

      .result {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
        color: #0c4a6e;
      }

      .result-value {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
      }

      .result-subtitle {
        font-size: 0.9rem;
        opacity: 0.8;
      }

      .hidden {
        display: none;
      }

      .loading {
        text-align: center;
        padding: 2rem;
        color: #64748b;
      }

      .error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
      }

      @media (max-width: 768px) {
        .container {
          padding: 1rem;
        }

        .calculator-form {
          grid-template-columns: 1fr;
          gap: 1rem;
        }

        .header h1 {
          font-size: 2rem;
        }

        .stats-grid {
          grid-template-columns: 1fr;
        }

        .social-links {
          flex-direction: column;
          gap: 0.75rem;
        }

        .creator-info {
          gap: 0.75rem;
        }
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="header">
        <h1>Battery Charging Analyzer</h1>
        <p>Analisis data pengisian baterai dan prediksi waktu charging</p>
      </div>

      <div class="upload-section">
        <div class="upload-area" id="uploadArea">
          <i class="fas fa-cloud-upload-alt upload-icon"></i>
          <div class="upload-text">
            Drag & drop file CSV atau klik untuk memilih
          </div>
          <div class="upload-subtext">
            Format: % Awal, Durasi (Jam), % Akhir, Tanggal
          </div>
          <input type="file" id="csvFile" class="file-input" accept=".csv" />
        </div>
      </div>

      <div id="results" class="hidden">
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-header">
              <div class="stat-icon blue">
                <i class="fas fa-calendar-alt"></i>
              </div>
              <div class="stat-label">Interval Charging</div>
            </div>
            <div class="stat-value" id="avgChargeDays">-</div>
            <div class="stat-description">
              Rata-rata hari antar pengisian berdasarkan pola data
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-header">
              <div class="stat-icon green">
                <i class="fas fa-bolt"></i>
              </div>
              <div class="stat-label">Kecepatan Charging</div>
            </div>
            <div class="stat-value" id="avgPercentPerHour">-</div>
            <div class="stat-description">
              Rata-rata peningkatan baterai setiap jam
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-header">
              <div class="stat-icon purple">
                <i class="fas fa-database"></i>
              </div>
              <div class="stat-label">Total Data Pengisian</div>
            </div>
            <div class="stat-value" id="totalCharges">-</div>
            <div class="stat-description">
              Jumlah data pengisian yang berhasil dianalisis
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-header">
              <div class="stat-icon orange">
                <i class="fas fa-clock"></i>
              </div>
              <div class="stat-label">Durasi Rata-rata</div>
            </div>
            <div class="stat-value" id="avgDuration">-</div>
            <div class="stat-description">
              Waktu rata-rata yang dibutuhkan untuk charging
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-header">
              <div
                class="stat-icon blue"
                style="background-color: #e0f2fe; color: #0277bd"
              >
                <i class="fas fa-chart-line"></i>
              </div>
              <div class="stat-label">Efisiensi Charging</div>
            </div>
            <div class="stat-value" id="chargingEfficiency">-</div>
            <div class="stat-description">
              Rata-rata peningkatan baterai per sesi charging
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-header">
              <div
                class="stat-icon green"
                style="background-color: #f1f8e9; color: #558b2f"
              >
                <i class="fas fa-battery-three-quarters"></i>
              </div>
              <div class="stat-label">Level Baterai Awal</div>
            </div>
            <div class="stat-value" id="avgStartLevel">-</div>
            <div class="stat-description">
              Rata-rata level baterai saat mulai charging
            </div>
          </div>
        </div>

        <div class="chart-container">
          <div class="chart-header">
            <i class="fas fa-chart-line" style="color: #3b82f6"></i>
            <h3>Grafik Pengisian Baterai</h3>
          </div>
          <canvas id="batteryChart"></canvas>
        </div>

        <div class="calculator-section">
          <div class="calculator-header">
            <i class="fas fa-calculator" style="color: #3b82f6"></i>
            <h3>Kalkulator Estimasi Waktu Pengisian</h3>
          </div>

          <div class="calculation-info">
            <div class="info-header">
              <i
                class="fas fa-info-circle"
                style="color: #6366f1; margin-right: 0.5rem"
              ></i>
              <strong>Cara Perhitungan:</strong>
            </div>
            <p>
              Estimasi dihitung berdasarkan rata-rata kecepatan charging dari
              data historis Anda. Formula:
              <code>(Target% - Awal%) ÷ Kecepatan Rata-rata</code>
            </p>
            <div class="note">
              <i
                class="fas fa-exclamation-triangle"
                style="color: #f59e0b; margin-right: 0.25rem"
              ></i>
              <small
                >Catatan: Hasil estimasi dapat berbeda dengan kondisi aktual
                karena faktor suhu, kondisi baterai, dan charger yang
                digunakan.</small
              >
            </div>
          </div>

          <div class="calculator-form">
            <div class="input-group">
              <label for="startPercent">Baterai Awal (%)</label>
              <input
                type="number"
                id="startPercent"
                min="0"
                max="100"
                placeholder="20"
              />
            </div>
            <div class="input-group">
              <label for="targetPercent">Target Baterai (%)</label>
              <input
                type="number"
                id="targetPercent"
                min="0"
                max="100"
                placeholder="90"
              />
            </div>
            <button class="calculate-btn" onclick="calculateChargingTime()">
              <i class="fas fa-play"></i>
              Hitung
            </button>
          </div>
          <div id="calculationResult" class="hidden"></div>
        </div>

        <div class="footer">
          <div class="footer-content">
            <div class="creator-info">
              <div class="d-flex align-items-center text-secondary">
                <i class="fas fa-code text-primary me-2"></i>
                <div>
                  <strong class="me-2">Yusma Rahman</strong>
                  <span class="text-muted">- Junior Web Developer</span>
                </div>
              </div>
              <div class="motivation">
                <i
                  class="fas fa-quote-left"
                  style="color: #64748b; margin-right: 0.25rem"
                ></i>
                <em>"From small code to real-world impact"</em>
              </div>
              <div class="social-links">
                <a
                  href="https://www.linkedin.com/in/yusmarahman09/"
                  target="_blank"
                  class="social-link"
                >
                  <i class="fab fa-linkedin"></i>
                  LinkedIn
                </a>
                <a
                  href="https://github.com/yusma1122"
                  target="_blank"
                  class="social-link"
                >
                  <i class="fab fa-github"></i>
                  GitHub
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
      let batteryData = [];
      let avgPercentPerHour = 0;
      let chart = null;

      // Setup drag and drop
      const uploadArea = document.getElementById("uploadArea");
      const fileInput = document.getElementById("csvFile");

      uploadArea.addEventListener("click", () => fileInput.click());

      uploadArea.addEventListener("dragover", (e) => {
        e.preventDefault();
        uploadArea.classList.add("dragover");
      });

      uploadArea.addEventListener("dragleave", () => {
        uploadArea.classList.remove("dragover");
      });

      uploadArea.addEventListener("drop", (e) => {
        e.preventDefault();
        uploadArea.classList.remove("dragover");
        const files = e.dataTransfer.files;
        if (files.length > 0) {
          handleFile(files[0]);
        }
      });

      fileInput.addEventListener("change", (e) => {
        if (e.target.files.length > 0) {
          handleFile(e.target.files[0]);
        }
      });

      function handleFile(file) {
        if (!file.name.toLowerCase().endsWith(".csv")) {
          showError("File harus berformat CSV");
          return;
        }

        Papa.parse(file, {
          header: false,
          skipEmptyLines: true,
          dynamicTyping: false,
          complete: function (results) {
            console.log("Raw CSV data:", results.data);
            processData(results.data);
          },
          error: function (error) {
            console.error("Parse error:", error);
            showError("Error membaca file CSV: " + error.message);
          },
        });
      }

      function parseTime(timeStr) {
        if (!timeStr) return 0;
        const cleanStr = timeStr.toString().trim();

        // Handle formats like "6", "5:30", "6:20"
        if (cleanStr.includes(":")) {
          const parts = cleanStr.split(":");
          const hours = parseFloat(parts[0]) || 0;
          const minutes = parseFloat(parts[1]) || 0;
          return hours + minutes / 60;
        } else {
          // Just a number like "6" or "5"
          return parseFloat(cleanStr) || 0;
        }
      }

      function parsePercent(percentStr) {
        if (!percentStr) return NaN;
        return parseInt(percentStr.toString().replace("%", "").trim());
      }

      function processData(data) {
        console.log("Processing data:", data);

        if (data.length < 3) {
          showError("File CSV tidak memiliki data yang cukup");
          return;
        }

        // Find the actual data rows - skip header and empty rows
        let dataRows = [];
        let headerFound = false;

        for (let i = 0; i < data.length; i++) {
          const row = data[i];

          // Skip empty rows
          if (
            !row ||
            row.length === 0 ||
            row.every((cell) => !cell || cell.trim() === "")
          ) {
            continue;
          }

          // Check if this is header row
          if (
            (row[0] && row[0].includes("%") && row[0].includes("Awal")) ||
            row[0] === "% Awal" ||
            row[0].trim() === "% Awal"
          ) {
            headerFound = true;
            continue;
          }

          // Skip the main title row
          if (row[0] && row[0].includes("Data Pengisian Baterai")) {
            continue;
          }

          // If we found header or this looks like data, add it
          if (headerFound || (row[0] && row[0].includes("%"))) {
            dataRows.push(row);
          }
        }

        console.log("Data rows found:", dataRows);

        let validData = [];

        dataRows.forEach((row, index) => {
          if (row.length < 3) return;

          const startPercent = parsePercent(row[0]);
          const duration = parseTime(row[1]);
          const endPercent = parsePercent(row[2]);
          const date = row[3] || `Entry ${index + 1}`;

          console.log(`Row ${index}:`, {
            raw: row,
            parsed: { startPercent, duration, endPercent, date },
          });

          if (
            !isNaN(startPercent) &&
            !isNaN(duration) &&
            !isNaN(endPercent) &&
            duration > 0 &&
            startPercent >= 0 &&
            endPercent >= 0 &&
            endPercent > startPercent
          ) {
            const percentGain = endPercent - startPercent;
            const percentPerHour = percentGain / duration;

            validData.push({
              startPercent,
              endPercent,
              duration,
              percentGain,
              percentPerHour,
              date: date || `Data ${index + 1}`,
            });
          }
        });

        console.log("Valid data:", validData);

        if (validData.length === 0) {
          showError(
            "Tidak ada data valid yang ditemukan. Pastikan format CSV sesuai."
          );
          return;
        }

        batteryData = validData;
        calculateStatistics();
        createChart();
        document.getElementById("results").classList.remove("hidden");
      }

      function calculateStatistics() {
        const totalCharges = batteryData.length;

        // Hitung rata-rata % per jam
        const avgPercentPerHourCalc =
          batteryData.reduce((sum, item) => sum + item.percentPerHour, 0) /
          totalCharges;
        avgPercentPerHour = avgPercentPerHourCalc;

        // Hitung rata-rata durasi
        const avgDurationCalc =
          batteryData.reduce((sum, item) => sum + item.duration, 0) /
          totalCharges;

        // Hitung rata-rata efisiensi charging (% gain per sesi)
        const avgEfficiency =
          batteryData.reduce((sum, item) => sum + item.percentGain, 0) /
          totalCharges;

        // Hitung rata-rata level baterai awal
        const avgStartLevel =
          batteryData.reduce((sum, item) => sum + item.startPercent, 0) /
          totalCharges;

        // Estimasi interval charging berdasarkan tanggal (jika ada)
        let avgChargeDaysCalc = 0;
        if (batteryData.length > 1) {
          // Coba parse tanggal untuk menghitung interval
          const dates = batteryData
            .map((item) => {
              if (item.date && item.date.includes("/")) {
                const parts = item.date.split("/");
                if (parts.length === 3) {
                  return new Date(parts[2], parts[0] - 1, parts[1]);
                }
              }
              return null;
            })
            .filter((date) => date !== null);

          if (dates.length > 1) {
            dates.sort((a, b) => a - b);
            let totalDays = 0;
            for (let i = 1; i < dates.length; i++) {
              const diffTime = dates[i] - dates[i - 1];
              const diffDays = diffTime / (1000 * 60 * 60 * 24);
              totalDays += diffDays;
            }
            avgChargeDaysCalc = totalDays / (dates.length - 1);
          } else {
            // Fallback: estimasi berdasarkan efisiensi baterai
            avgChargeDaysCalc =
              (100 - avgStartLevel) / avgPercentPerHourCalc / 24;
          }
        }

        // Update tampilan
        document.getElementById("totalCharges").textContent = totalCharges;
        document.getElementById("avgPercentPerHour").textContent =
          avgPercentPerHourCalc.toFixed(1) + "% per jam";
        document.getElementById("avgDuration").textContent =
          avgDurationCalc.toFixed(1) + " jam";
        document.getElementById("avgChargeDays").textContent =
          avgChargeDaysCalc.toFixed(1) + " hari";
        document.getElementById("chargingEfficiency").textContent =
          avgEfficiency.toFixed(1) + "%";
        document.getElementById("avgStartLevel").textContent =
          avgStartLevel.toFixed(1) + "%";
      }

      function createChart() {
        const ctx = document.getElementById("batteryChart").getContext("2d");

        if (chart) {
          chart.destroy();
        }

        const dates = batteryData.map((item, index) => {
          // Clean up date format or use index
          let dateStr = item.date;
          if (dateStr && dateStr.includes("/")) {
            return dateStr;
          }
          return `Entry ${index + 1}`;
        });

        const startLevels = batteryData.map((item) => item.startPercent);
        const endLevels = batteryData.map((item) => item.endPercent);
        const durations = batteryData.map((item) => item.duration);

        chart = new Chart(ctx, {
          type: "line",
          data: {
            labels: dates,
            datasets: [
              {
                label: "Baterai Awal (%)",
                data: startLevels,
                borderColor: "#ef4444",
                backgroundColor: "rgba(239, 68, 68, 0.1)",
                tension: 0.4,
                borderWidth: 2,
                pointBackgroundColor: "#ef4444",
                pointBorderColor: "#ef4444",
                pointRadius: 4,
              },
              {
                label: "Baterai Akhir (%)",
                data: endLevels,
                borderColor: "#10b981",
                backgroundColor: "rgba(16, 185, 129, 0.1)",
                tension: 0.4,
                borderWidth: 2,
                pointBackgroundColor: "#10b981",
                pointBorderColor: "#10b981",
                pointRadius: 4,
              },
              {
                label: "Durasi (Jam)",
                data: durations,
                borderColor: "#3b82f6",
                backgroundColor: "rgba(59, 130, 246, 0.1)",
                yAxisID: "y1",
                tension: 0.4,
                borderWidth: 2,
                pointBackgroundColor: "#3b82f6",
                pointBorderColor: "#3b82f6",
                pointRadius: 4,
              },
            ],
          },
          options: {
            responsive: true,
            interaction: {
              mode: "point",
              intersect: true,
            },
            plugins: {
              legend: {
                position: "top",
              },
              tooltip: {
                callbacks: {
                  label: function (context) {
                    let label = context.dataset.label || "";
                    if (label) {
                      label += ": ";
                    }

                    if (context.datasetIndex === 2) {
                      // For duration, format properly
                      const hours = context.parsed.y;
                      const wholeHours = Math.floor(hours);
                      const minutes = Math.round((hours - wholeHours) * 60);

                      if (minutes === 0) {
                        label += wholeHours + " jam";
                      } else {
                        label +=
                          wholeHours +
                          ":" +
                          minutes.toString().padStart(2, "0") +
                          " jam";
                      }
                    } else {
                      // For percentage
                      label += context.parsed.y + "%";
                    }

                    return label;
                  },
                },
              },
            },
            scales: {
              x: {
                display: true,
                title: {
                  display: true,
                  text: "Tanggal",
                },
              },
              y: {
                type: "linear",
                display: true,
                position: "left",
                beginAtZero: true,
                max: 100,
                title: {
                  display: true,
                  text: "Persentase Baterai (%)",
                },
              },
              y1: {
                type: "linear",
                display: true,
                position: "right",
                beginAtZero: true,
                max: Math.max(...durations) + 1,
                title: {
                  display: true,
                  text: "Durasi (Jam)",
                },
                grid: {
                  drawOnChartArea: false,
                },
              },
            },
          },
        });
      }

      function calculateChargingTime() {
        const startPercent = parseInt(
          document.getElementById("startPercent").value
        );
        const targetPercent = parseInt(
          document.getElementById("targetPercent").value
        );

        if (
          isNaN(startPercent) ||
          isNaN(targetPercent) ||
          startPercent >= targetPercent
        ) {
          showResult(
            "Mohon masukkan nilai yang valid (target harus lebih besar dari awal)",
            "error"
          );
          return;
        }

        if (avgPercentPerHour === 0) {
          showResult("Silakan upload data CSV terlebih dahulu", "error");
          return;
        }

        const percentNeeded = targetPercent - startPercent;
        const estimatedHours = percentNeeded / avgPercentPerHour;
        const hours = Math.floor(estimatedHours);
        const minutes = Math.round((estimatedHours - hours) * 60);

        const resultHTML = `
                <div class="result-value">${hours} jam ${minutes} menit</div>
                <div class="result-subtitle">Berdasarkan rata-rata ${avgPercentPerHour.toFixed(
                  1
                )}% per jam dari data Anda</div>
            `;

        showResult(resultHTML, "success");
      }

      function showResult(message, type) {
        const resultDiv = document.getElementById("calculationResult");
        resultDiv.className = type === "error" ? "error" : "result";
        resultDiv.innerHTML = message;
        resultDiv.classList.remove("hidden");
      }

      function showError(message) {
        const resultDiv = document.getElementById("calculationResult");
        resultDiv.className = "error";
        resultDiv.innerHTML = message;
        resultDiv.classList.remove("hidden");
      }
    </script>
  </body>
</html>
