<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Prediksi Risiko Stroke - SVM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }
        
        .main-container {
            padding: 30px 0;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
            font-weight: 600;
        }
        
        .upload-area {
            border: 3px dashed var(--secondary-color);
            border-radius: 15px;
            padding: 50px;
            text-align: center;
            background: rgba(52, 152, 219, 0.05);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .upload-area:hover {
            background: rgba(52, 152, 219, 0.1);
            border-color: var(--primary-color);
        }
        
        .upload-area.dragover {
            background: rgba(52, 152, 219, 0.2);
            border-color: var(--success-color);
        }
        
        .upload-icon {
            font-size: 60px;
            color: var(--secondary-color);
            margin-bottom: 20px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(52, 152, 219, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success-color), #219a52);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(39, 174, 96, 0.4);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color), #c0392b);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .stroke-color { color: var(--danger-color); }
        .no-stroke-color { color: var(--success-color); }
        
        .chart-container {
            position: relative;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .spinner-border-sm {
            width: 18px;
            height: 18px;
            border-width: 2px;
        }
        
        .file-info {
            background: rgba(52, 152, 219, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin-top: 15px;
            display: none;
        }
        
        .progress {
            height: 8px;
            border-radius: 10px;
            background: #e9ecef;
        }
        
        .progress-bar {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            border-radius: 10px;
        }
        
        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background: var(--primary-color);
            color: white;
            position: sticky;
            top: 0;
            z-index: 1;
        }
        
        .badge-stroke {
            background: var(--danger-color);
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
        }
        
        .badge-no-stroke {
            background: var(--success-color);
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
        }
        
        .info-icon {
            cursor: pointer;
            color: var(--secondary-color);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-heartbeat me-2"></i>
                SVM Stroke Prediction
            </a>
        </div>
    </nav>

    <div class="main-container">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon" style="color: var(--primary-color);">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-number" id="totalPasien">0</div>
                        <div class="stat-label">Total Pasien</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon stroke-color">
                            <i class="fas fa-procedures"></i>
                        </div>
                        <div class="stat-number stroke-color" id="pasienStroke">0</div>
                        <div class="stat-label">Terkena Stroke</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon no-stroke-color">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="stat-number no-stroke-color" id="pasienTidakStroke">0</div>
                        <div class="stat-label">Tidak Stroke</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-upload me-2"></i> Upload Data Pasien
                        </div>
                        <div class="card-body">
                            <form id="uploadForm" enctype="multipart/form-data">
                                @csrf
                                <div class="upload-area" id="uploadArea">
                                    <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                    <h5>Drag & Drop file di sini</h5>
                                    <p class="text-muted">atau</p>
                                    <input type="file" id="fileInput" name="file" accept=".csv,.xlsx,.xls" style="display: none;">
                                    <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                                        <i class="fas fa-folder-open me-2"></i> Pilih File
                                    </button>
                                    <p class="mt-3 text-muted small">Format yang didukung: CSV, Excel (.xlsx, .xls)</p>
                                </div>
                                <div class="file-info" id="fileInfo">
                                    <i class="fas fa-file-alt me-2"></i>
                                    <span id="fileName"></span>
                                </div>
                            </form>
                            
                            <div id="uploadStatus"></div>
                            
                            <div class="mt-4">
                                <button type="button" class="btn btn-success w-100" id="importBtn" disabled>
                                    <i class="fas fa-database me-2"></i> Import ke Database
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-robot me-2"></i> Prediksi
                        </div>
                        <div class="card-body">
                            <p class="text-muted">
                                Klik tombol di bawah untuk melakukan prediksi risiko stroke pada data pasien yang sudah diimport ke database menggunakan algoritma SVM.
                            </p>
                            <button type="button" class="btn btn-primary w-100 mb-3" id="prediksiBtn">
                                <i class="fas fa-bolt me-2"></i> Prediksi Sekarang
                            </button>
                            <button type="button" class="btn btn-danger w-100" id="hapusBtn">
                                <i class="fas fa-trash me-2"></i> Hapus Semua Data
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-chart-pie me-2"></i> Hasil Prediksi
                        </div>
                        <div class="card-body">
                            <div class="chart-container" id="chartContainer">
                                <div id="chartPlaceholder" class="text-center">
                                    <i class="fas fa-chart-line fa-4x text-muted mb-3"></i>
                                    <p class="text-muted">Belum ada hasil prediksi</p>
                                    <p class="small text-muted">Import data dan klik "Prediksi Sekarang"</p>
                                </div>
                                <canvas id="doughnutChart" style="display: none;"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-list me-2"></i> Data Terbaru
                        </div>
                        <div class="card-body table-container">
                            <table class="table table-striped" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Umur</th>
                                        <th>Gender</th>
                                        <th>Glukosa</th>
                                        <th>BMI</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentData as $index => $pasien)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $pasien->age }}</td>
                                        <td>{{ $pasien->gender }}</td>
                                        <td>{{ $pasien->avg_glucose_level }}</td>
                                        <td>{{ $pasien->bmi ?? 'N/A' }}</td>
                                        <td>
                                            @if($pasien->stroke == 1)
                                                <span class="badge-stroke">Stroke</span>
                                            @else
                                                <span class="badge-no-stroke">Normal</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($recentData->isEmpty())
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>Belum ada data</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <script>
        let uploadedFile = null;
        let doughnutChart = null;

        document.addEventListener('DOMContentLoaded', function() {
            initChart();
            setupUploadArea();
            setupEventListeners();
        });

        function initChart() {
            const ctx = document.getElementById('doughnutChart').getContext('2d');
            
            // Initialize with empty data - will be updated after prediction
            const stroke = 0;
            const tidakStroke = 0;
            
            doughnutChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Tidak Stroke', 'Stroke'],
                    datasets: [{
                        data: [1, 0],
                        backgroundColor: [
                            '#27ae60',
                            '#e74c3c'
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 14
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(44, 62, 80, 0.9)',
                            titleFont: {
                                size: 14
                            },
                            bodyFont: {
                                size: 13
                            },
                            padding: 12,
                            cornerRadius: 8
                        }
                    }
                }
            });
        }

        function setupUploadArea() {
            const uploadArea = document.getElementById('uploadArea');
            const fileInput = document.getElementById('fileInput');

            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', function() {
                uploadArea.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    handleFileSelect(files[0]);
                }
            });

            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    handleFileSelect(this.files[0]);
                }
            });
        }

        function handleFileSelect(file) {
            const validExtensions = ['csv', 'xlsx', 'xls'];
            const extension = file.name.split('.').pop().toLowerCase();

            if (!validExtensions.includes(extension)) {
                showAlert('error', 'Format file tidak valid! Silakan upload file CSV atau Excel.');
                return;
            }

            if (file.size > 10 * 1024 * 1024) {
                showAlert('error', 'Ukuran file terlalu besar! Maksimal 10MB.');
                return;
            }

            uploadedFile = file;
            
            const fileInfo = document.getElementById('fileInfo');
            const fileName = document.getElementById('fileName');
            fileInfo.style.display = 'block';
            fileName.textContent = file.name + ' (' + formatBytes(file.size) + ')';
            
            document.getElementById('importBtn').disabled = false;
            
            showAlert('success', 'File siap diupload: ' + file.name);
        }

        function setupEventListeners() {
            document.getElementById('importBtn').addEventListener('click', importToDatabase);
            document.getElementById('prediksiBtn').addEventListener('click', doPrediksi);
            document.getElementById('hapusBtn').addEventListener('click', hapusData);
        }

        function importToDatabase() {
            if (!uploadedFile) {
                showAlert('error', 'Silakan upload file terlebih dahulu!');
                return;
            }

            const btn = document.getElementById('importBtn');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengimport...';

            const formData = new FormData();
            formData.append('file', uploadedFile);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('/upload', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    return fetch('/import', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ filename: data.filename })
                    });
                } else {
                    throw new Error(data.message);
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    updateStats();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                showAlert('error', 'Error: ' + error.message);
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        }

        function doPrediksi() {
            const btn = document.getElementById('prediksiBtn');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memprediksi...';

            fetch('/prediksi', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', 
                        'Prediksi Selesai!<br>' +
                        '<strong>Stroke:</strong> ' + data.stroke + '<br>' +
                        '<strong>Tidak Stroke:</strong> ' + data.tidak_stroke
                    );
                    updateStats();
                    updateChart(data.stroke, data.tidak_stroke);
                    refreshTable(); // Refresh table with new data
                } else {
                    showAlert('error', data.message);
                }
            })
            .catch(error => {
                showAlert('error', 'Error: ' + error.message);
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        }

        function hapusData() {
            if (!confirm('Apakah Anda yakin ingin menghapus semua data pasien?')) {
                return;
            }

            const btn = document.getElementById('hapusBtn');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menghapus...';

            fetch('/hapus-data', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    updateStats();
                    updateChart(0, 0);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('error', data.message);
                }
            })
            .catch(error => {
                showAlert('error', 'Error: ' + error.message);
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        }

        function updateStats() {
            fetch('/stats')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('totalPasien').textContent = data.total;
                    document.getElementById('pasienStroke').textContent = data.stroke;
                    document.getElementById('pasienTidakStroke').textContent = data.tidak_stroke;
                });
        }

        function refreshTable() {
            fetch('/data-terbaru')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.querySelector('#dataTable tbody');
                    tbody.innerHTML = '';
                    
                    data.forEach((pasien, index) => {
                        const row = document.createElement('tr');
                        const statusBadge = pasien.stroke === 1 
                            ? '<span class="badge-stroke">Stroke</span>' 
                            : '<span class="badge-no-stroke">Normal</span>';
                        
                        row.innerHTML = `
                            <td>${index + 1}</td>
                            <td>${pasien.age}</td>
                            <td>${pasien.gender}</td>
                            <td>${pasien.avg_glucose_level}</td>
                            <td>${pasien.bmi ?? 'N/A'}</td>
                            <td>${statusBadge}</td>
                        `;
                        tbody.appendChild(row);
                    });
                });
        }

        function updateChart(stroke, tidakStroke) {
            // Show chart, hide placeholder
            document.getElementById('chartPlaceholder').style.display = 'none';
            document.getElementById('doughnutChart').style.display = 'block';
            
            if (doughnutChart) {
                doughnutChart.data.datasets[0].data = [tidakStroke || 1, stroke];
                doughnutChart.update();
            }
        }

        function showAlert(type, message) {
            const statusDiv = document.getElementById('uploadStatus');
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
            
            statusDiv.innerHTML = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <i class="fas fa-${icon} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            setTimeout(() => {
                statusDiv.innerHTML = '';
            }, 5000);
        }

        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }
    </script>
</body>
</html>
