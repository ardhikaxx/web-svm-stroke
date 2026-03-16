<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Prediksi Risiko Stroke - SVM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0d9488;
            --primary-dark: #0f766e;
            --primary-light: #14b8a6;
            --primary-bg: #f0fdfa;
            --dark: #134e4a;
            --gray: #64748b;
            --light: #f8fafc;
            --white: #ffffff;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 50%, #134e4a 100%);
            min-height: 100vh;
            padding-bottom: 50px;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow);
            padding: 15px 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary) !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .navbar-brand i {
            font-size: 1.8rem;
        }
        
        .main-container {
            padding-top: 40px;
        }
        
        .page-title {
            color: white;
            font-size: 2rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .page-subtitle {
            color: rgba(255,255,255,0.9);
            text-align: center;
            margin-bottom: 40px;
            font-size: 1.1rem;
        }
        
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            background: var(--white);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 20px 25px;
            font-weight: 600;
            font-size: 1.1rem;
            border: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card-header-custom i {
            font-size: 1.3rem;
        }
        
        .card-body {
            padding: 25px;
        }
        
        .stat-card {
            background: var(--white);
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 1.8rem;
        }
        
        .stat-icon.primary {
            background: var(--primary-bg);
            color: var(--primary);
        }
        
        .stat-icon.danger {
            background: #fef2f2;
            color: #ef4444;
        }
        
        .stat-icon.success {
            background: #f0fdf4;
            color: #22c55e;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark);
            line-height: 1;
        }
        
        .stat-label {
            color: var(--gray);
            font-size: 0.9rem;
            margin-top: 5px;
            font-weight: 500;
        }
        
        .upload-area {
            border: 3px dashed var(--primary);
            border-radius: 20px;
            padding: 50px 30px;
            text-align: center;
            background: var(--primary-bg);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .upload-area:hover {
            background: #ccfbf1;
            border-color: var(--primary-dark);
            transform: scale(1.01);
        }
        
        .upload-area.dragover {
            background: #99f6e4;
            border-color: var(--primary-dark);
        }
        
        .upload-icon {
            font-size: 70px;
            color: var(--primary);
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .upload-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 10px;
        }
        
        .upload-subtitle {
            color: var(--gray);
            margin-bottom: 20px;
        }
        
        .file-info {
            background: var(--primary-bg);
            border-radius: 15px;
            padding: 15px 20px;
            margin-top: 15px;
            display: none;
            align-items: center;
            gap: 10px;
        }
        
        .file-info.show {
            display: flex;
        }
        
        .file-info i {
            color: var(--primary);
            font-size: 1.5rem;
        }
        
        .file-info .file-name {
            font-weight: 500;
            color: var(--dark);
        }
        
        .file-info .file-size {
            color: var(--gray);
            font-size: 0.85rem;
        }
        
        .btn {
            padding: 12px 30px;
            border-radius: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(13, 148, 136, 0.3);
            color: white;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border: none;
            color: white;
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(34, 197, 94, 0.3);
            color: white;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border: none;
            color: white;
        }
        
        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3);
            color: white;
        }
        
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }
        
        .btn-width {
            width: 100%;
            margin-bottom: 15px;
        }
        
        .btn-width:last-child {
            margin-bottom: 0;
        }
        
        .description-box {
            background: var(--primary-bg);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .description-box p {
            color: var(--gray);
            margin: 0;
            line-height: 1.6;
        }
        
        .chart-container {
            position: relative;
            height: 320px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        #chartPlaceholder {
            text-align: center;
            padding: 40px;
        }
        
        #chartPlaceholder i {
            font-size: 5rem;
            color: var(--primary);
            opacity: 0.3;
            margin-bottom: 20px;
        }
        
        #chartPlaceholder p {
            color: var(--gray);
            font-size: 1.1rem;
            margin: 0;
        }
        
        #chartPlaceholder .small {
            font-size: 0.9rem;
            opacity: 0.7;
        }
        
        .table-container {
            max-height: 400px;
            overflow-y: auto;
            border-radius: 15px;
        }
        
        .table {
            margin: 0;
        }
        
        .table thead th {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            position: sticky;
            top: 0;
            z-index: 1;
            padding: 15px;
            font-weight: 600;
            border: none;
        }
        
        .table tbody tr {
            transition: all 0.2s ease;
        }
        
        .table tbody tr:hover {
            background: var(--primary-bg);
        }
        
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
        }
        
        .badge-stroke {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .badge-no-stroke {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .alert {
            border-radius: 15px;
            border: none;
            padding: 15px 20px;
        }
        
        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border-left: 4px solid #22c55e;
        }
        
        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
        
        .spinner-border-sm {
            width: 18px;
            height: 18px;
            border-width: 2px;
        }
        
        .empty-state {
            text-align: center;
            padding: 50px 20px;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: var(--primary);
            opacity: 0.3;
            margin-bottom: 20px;
        }
        
        .empty-state p {
            color: var(--gray);
            font-size: 1.1rem;
            margin: 0;
        }
        
        @media (max-width: 768px) {
            .page-title {
                font-size: 1.5rem;
            }
            
            .stat-number {
                font-size: 2rem;
            }
            
            .card-body {
                padding: 20px;
            }
            
            .upload-area {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-heart-pulse"></i>
                SVM Stroke Prediction
            </a>
        </div>
    </nav>

    <div class="main-container">
        <div class="container">
            <h1 class="page-title">Prediksi Risiko Stroke</h1>
            <p class="page-subtitle">Sistem Prediksi Menggunakan Algoritma Support Vector Machine (SVM)</p>
            
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-number" id="totalPasien">0</div>
                        <div class="stat-label">Total Pasien</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon danger">
                            <i class="fas fa-procedures"></i>
                        </div>
                        <div class="stat-number" id="pasienStroke">0</div>
                        <div class="stat-label">Risiko Stroke</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon success">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="stat-number" id="pasienTidakStroke">0</div>
                        <div class="stat-label">Tidak Stroke</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header-custom">
                            <i class="fas fa-upload"></i> Upload Data Pasien
                        </div>
                        <div class="card-body">
                            <form id="uploadForm" enctype="multipart/form-data">
                                @csrf
                                <div class="upload-area" id="uploadArea">
                                    <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                    <div class="upload-title">Drag & Drop file di sini</div>
                                    <div class="upload-subtitle">atau</div>
                                    <input type="file" id="fileInput" name="file" accept=".csv,.xlsx,.xls" style="display: none;">
                                    <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                                        <i class="fas fa-folder-open"></i> Pilih File
                                    </button>
                                    <p class="mt-3 mb-0" style="color: var(--gray); font-size: 0.9rem;">Format: CSV, Excel (.xlsx, .xls)</p>
                                </div>
                                <div class="file-info" id="fileInfo">
                                    <i class="fas fa-file-csv"></i>
                                    <div>
                                        <div class="file-name" id="fileName"></div>
                                        <div class="file-size" id="fileSize"></div>
                                    </div>
                                </div>
                            </form>
                            
                            <div id="uploadStatus" class="mt-3"></div>
                            
                            <div class="mt-4">
                                <button type="button" class="btn btn-success btn-width" id="importBtn" disabled>
                                    <i class="fas fa-database"></i> Import ke Database
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header-custom">
                            <i class="fas fa-robot"></i> Prediksi
                        </div>
                        <div class="card-body">
                            <div class="description-box">
                                <p>Klik tombol di bawah untuk melakukan prediksi risiko stroke pada data pasien menggunakan algoritma SVM.</p>
                            </div>
                            <button type="button" class="btn btn-primary btn-width" id="prediksiBtn">
                                <i class="fas fa-bolt"></i> Prediksi Sekarang
                            </button>
                            <button type="button" class="btn btn-danger btn-width" id="hapusBtn">
                                <i class="fas fa-trash"></i> Hapus Semua Data
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header-custom">
                            <i class="fas fa-chart-pie"></i> Hasil Prediksi
                        </div>
                        <div class="card-body">
                            <div class="chart-container" id="chartContainer">
                                <div id="chartPlaceholder">
                                    <i class="fas fa-chart-line"></i>
                                    <p>Belum ada hasil prediksi</p>
                                    <p class="small">Import data dan klik "Prediksi Sekarang"</p>
                                </div>
                                <canvas id="doughnutChart" style="display: none;"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header-custom">
                            <i class="fas fa-list"></i> Data Terbaru
                        </div>
                        <div class="card-body">
                            <div class="table-container">
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
                                    </tbody>
                                </table>
                            </div>
                            <div class="empty-state" id="emptyState">
                                <i class="fas fa-inbox"></i>
                                <p>Belum ada data</p>
                            </div>
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
            checkEmptyState();
        });

        function checkEmptyState() {
            const tbody = document.querySelector('#dataTable tbody');
            const emptyState = document.getElementById('emptyState');
            if (tbody.children.length === 0) {
                emptyState.style.display = 'block';
            } else {
                emptyState.style.display = 'none';
            }
        }

        function initChart() {
            const ctx = document.getElementById('doughnutChart').getContext('2d');
            
            doughnutChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Tidak Stroke', 'Stroke'],
                    datasets: [{
                        data: [1, 0],
                        backgroundColor: [
                            '#22c55e',
                            '#ef4444'
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 14,
                                    family: "'Poppins', sans-serif"
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(19, 78, 74, 0.95)',
                            titleFont: {
                                size: 14,
                                family: "'Poppins', sans-serif"
                            },
                            bodyFont: {
                                size: 13,
                                family: "'Poppins', sans-serif"
                            },
                            padding: 12,
                            cornerRadius: 10
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
            const fileSize = document.getElementById('fileSize');
            
            fileInfo.classList.add('show');
            fileName.textContent = file.name;
            fileSize.textContent = formatBytes(file.size);
            
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
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mengimport...';

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
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memprediksi...';

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
                        '<strong>Risiko Stroke:</strong> ' + data.stroke + '<br>' +
                        '<strong>Tidak Stroke:</strong> ' + data.tidak_stroke
                    );
                    updateStats();
                    updateChart(data.stroke, data.tidak_stroke);
                    refreshTable();
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
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menghapus...';

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

        function updateChart(stroke, tidakStroke) {
            document.getElementById('chartPlaceholder').style.display = 'none';
            document.getElementById('doughnutChart').style.display = 'block';
            
            if (doughnutChart) {
                doughnutChart.data.datasets[0].data = [tidakStroke || 1, stroke];
                doughnutChart.update();
            }
        }

        function refreshTable() {
            fetch('/data-terbaru')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.querySelector('#dataTable tbody');
                    const emptyState = document.getElementById('emptyState');
                    tbody.innerHTML = '';
                    
                    if (data.length === 0) {
                        emptyState.style.display = 'block';
                        return;
                    }
                    
                    emptyState.style.display = 'none';
                    
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

        function showAlert(type, message) {
            const statusDiv = document.getElementById('uploadStatus');
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
            
            statusDiv.innerHTML = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <i class="fas fa-${icon}"></i>
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
