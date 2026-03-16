<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Prediksi Risiko Stroke - SVM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0891b2;
            --primary-dark: #0e7490;
            --primary-darker: #155e75;
            --primary-light: #22d3ee;
            --primary-bg: #ecfeff;
            --primary-bg-dark: #cffafe;
            --dark: #0c4a6e;
            --dark-light: #164e63;
            --gray: #6b7280;
            --gray-light: #9ca3af;
            --white: #ffffff;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0891b2 0%, #0e7490 30%, #164e63 60%, #0c4a6e 100%);
            min-height: 100vh;
            padding-bottom: 60px;
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(8, 145, 178, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(34, 211, 238, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(8, 145, 178, 0.1) 0%, transparent 30%);
            pointer-events: none;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: var(--shadow-md);
            padding: 18px 0;
            position: relative;
            z-index: 100;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.6rem;
            color: var(--primary) !important;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        
        .navbar-brand:hover {
            color: var(--primary-dark) !important;
        }
        
        .navbar-brand i {
            font-size: 2rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .main-container {
            padding-top: 50px;
            position: relative;
            z-index: 1;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .page-title {
            color: white;
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 10px;
            text-shadow: 0 4px 6px rgba(0,0,0,0.2);
            letter-spacing: -0.5px;
        }
        
        .page-subtitle {
            color: rgba(255,255,255,0.85);
            font-size: 1.2rem;
            font-weight: 400;
        }
        
        .page-subtitle span {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            padding: 5px 15px;
            border-radius: 20px;
            margin-top: 8px;
        }
        
        .card {
            border: none;
            border-radius: 24px;
            box-shadow: var(--shadow-xl);
            background: var(--white);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            height: auto;
        }
        
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 22px 28px;
            font-weight: 700;
            font-size: 1.15rem;
            border: none;
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            overflow: hidden;
        }
        
        .card-header-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s;
        }
        
        .card:hover .card-header-custom::before {
            left: 100%;
        }
        
        .card-header-custom i {
            font-size: 1.4rem;
        }
        
        .card-body {
            padding: 28px;
        }
        
        .stat-card {
            background: var(--white);
            border-radius: 24px;
            padding: 32px 24px;
            text-align: center;
            box-shadow: var(--shadow-xl);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            height: 100%;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
        }
        
        .stat-card:hover {
            transform: translateY(-10px) scale(1.02);
        }
        
        .stat-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(5deg);
        }
        
        .stat-icon.primary {
            background: linear-gradient(135deg, var(--primary-bg), var(--primary-bg-dark));
            color: var(--primary);
            box-shadow: 0 10px 30px rgba(8, 145, 178, 0.2);
        }
        
        .stat-icon.danger {
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            color: #ef4444;
            box-shadow: 0 10px 30px rgba(239, 68, 68, 0.2);
        }
        
        .stat-icon.success {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            color: #22c55e;
            box-shadow: 0 10px 30px rgba(34, 197, 94, 0.2);
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: var(--dark);
            line-height: 1;
            margin-bottom: 8px;
            letter-spacing: -1px;
        }
        
        .stat-label {
            color: var(--gray);
            font-size: 0.95rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-card:nth-child(1) .stat-number { color: var(--primary); }
        .stat-card:nth-child(2) .stat-number { color: #ef4444; }
        .stat-card:nth-child(3) .stat-number { color: #22c55e; }
        
        .upload-area {
            border: 3px dashed var(--primary);
            border-radius: 24px;
            padding: 40px 30px;
            text-align: center;
            background: linear-gradient(135deg, var(--primary-bg), #e0f7fa);
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .upload-area:hover {
            background: linear-gradient(135deg, #e0f7fa, #cffafe);
            border-color: var(--primary-dark);
            transform: scale(1.01);
        }
        
        .upload-area.dragover {
            background: #b9f6f0;
            border-color: var(--primary-darker);
            transform: scale(1.02);
        }
        
        .upload-icon {
            font-size: 60px;
            color: var(--primary);
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .upload-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 12px;
        }
        
        .upload-subtitle {
            color: var(--gray);
            margin-bottom: 25px;
            font-size: 1rem;
        }
        
        .file-info {
            background: linear-gradient(135deg, var(--primary-bg), #e0f7fa);
            border-radius: 16px;
            padding: 18px 24px;
            margin-top: 18px;
            display: none;
            align-items: center;
            gap: 15px;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .file-info.show {
            display: flex;
        }
        
        .file-info i {
            color: var(--primary);
            font-size: 1.8rem;
        }
        
        .file-info .file-name {
            font-weight: 700;
            color: var(--dark);
            font-size: 1rem;
        }
        
        .file-info .file-size {
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        .btn {
            padding: 14px 32px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            letter-spacing: -0.3px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            color: white;
            box-shadow: 0 4px 15px rgba(8, 145, 178, 0.4);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(8, 145, 178, 0.5);
            color: white;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border: none;
            color: white;
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.4);
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(34, 197, 94, 0.5);
            color: white;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border: none;
            color: white;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
        }
        
        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.5);
            color: white;
        }
        
        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }
        
        .btn-width {
            width: 100%;
            margin-bottom: 16px;
        }
        
        .btn-width:last-child {
            margin-bottom: 0;
        }
        
        .description-box {
            background: linear-gradient(135deg, var(--primary-bg), #e0f7fa);
            border-radius: 18px;
            padding: 22px;
            margin-bottom: 22px;
            border-left: 4px solid var(--primary);
        }
        
        .description-box p {
            color: var(--dark-light);
            margin: 0;
            line-height: 1.7;
            font-size: 0.98rem;
        }
        
        .chart-container {
            position: relative;
            min-height: 250px;
            height: auto;
            padding: 20px 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        #chartPlaceholder {
            text-align: center;
            padding: 50px 30px;
        }
        
        #chartPlaceholder i {
            font-size: 6rem;
            color: var(--primary);
            opacity: 0.2;
            margin-bottom: 25px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 0.2; transform: scale(1); }
            50% { opacity: 0.3; transform: scale(1.05); }
        }
        
        #chartPlaceholder p {
            color: var(--gray);
            font-size: 1.15rem;
            font-weight: 600;
            margin: 0 0 8px 0;
        }
        
        #chartPlaceholder .small {
            font-size: 0.95rem;
            opacity: 0.7;
        }
        
        .table-container {
            max-height: 300px;
            overflow-y: auto;
            border-radius: 18px;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .table {
            margin: 0;
            font-size: 0.95rem;
        }
        
        .table thead th {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            position: sticky;
            top: 0;
            z-index: 1;
            padding: 18px;
            font-weight: 700;
            border: none;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .table tbody tr {
            transition: all 0.2s ease;
        }
        
        .table tbody tr:hover {
            background: var(--primary-bg);
        }
        
        .table tbody td {
            padding: 16px 18px;
            vertical-align: middle;
            font-weight: 500;
        }
        
        .badge-stroke {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 8px 18px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 0.85rem;
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
        }
        
        .badge-no-stroke {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
            padding: 8px 18px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 0.85rem;
            box-shadow: 0 4px 10px rgba(34, 197, 94, 0.3);
        }
        
        .alert {
            border-radius: 16px;
            border: none;
            padding: 18px 24px;
            font-weight: 600;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            color: #166534;
            border-left: 5px solid #22c55e;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            color: #991b1b;
            border-left: 5px solid #ef4444;
        }
        
        .spinner-border-sm {
            width: 18px;
            height: 18px;
            border-width: 2.5px;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 30px;
        }
        
        .empty-state i {
            font-size: 5rem;
            color: var(--primary);
            opacity: 0.15;
            margin-bottom: 20px;
        }
        
        .empty-state p {
            color: var(--gray);
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }
        
        .wave-decoration {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
            pointer-events: none;
            z-index: 0;
        }
        
        @media (max-width: 992px) {
            .page-title {
                font-size: 2rem;
            }
            
            .stat-number {
                font-size: 2.2rem;
            }
        }
        
        @media (max-width: 768px) {
            .page-title {
                font-size: 1.7rem;
            }
            
            .page-subtitle {
                font-size: 1rem;
            }
            
            .card-body {
                padding: 20px;
            }
            
            .upload-area {
                padding: 40px 25px;
            }
            
            .stat-card {
                padding: 24px 18px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-heart-pulse"></i>
                SVM Stroke
            </a>
        </div>
    </nav>

    <div class="main-container">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">Prediksi Risiko Stroke</h1>
                <p class="page-subtitle">Menggunakan Algoritma Support Vector Machine (SVM)</p>
            </div>
            
            <div class="row mb-5 g-4">
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
                            <i class="fas fa-heart-crack"></i>
                        </div>
                        <div class="stat-number" id="pasienStroke">0</div>
                        <div class="stat-label">Risiko Stroke</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon success">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="stat-number" id="pasienTidakStroke">0</div>
                        <div class="stat-label">Tidak Stroke</div>
                    </div>
                </div>
            </div>

            <div class="row g-4 align-items-start">
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header-custom">
                            <i class="fas fa-file-import"></i> Import Data Pasien
                        </div>
                        <div class="card-body">
                            <form id="uploadForm" enctype="multipart/form-data">
                                @csrf
                                <div class="upload-area" id="uploadArea">
                                    <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                    <div class="upload-title">Drag & Drop file di sini</div>
                                    <div class="upload-subtitle">atau klik untuk memilih file</div>
                                    <input type="file" id="fileInput" name="file" accept=".csv,.xlsx,.xls" style="display: none;">
                                    <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                                        <i class="fas fa-folder-open"></i> Pilih File
                                    </button>
                                </div>
                                <div class="file-info" id="fileInfo">
                                    <i class="fas fa-file-csv"></i>
                                    <div>
                                        <div class="file-name" id="fileName"></div>
                                        <div class="file-size" id="fileSize"></div>
                                    </div>
                                </div>
                            </form>
                            
                            <div id="uploadStatus" class="mt-4"></div>
                            
                            <div class="mt-4">
                                <button type="button" class="btn btn-success btn-width" id="importBtn" disabled>
                                    <i class="fas fa-database"></i> Import ke Database
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header-custom">
                            <i class="fas fa-microchip"></i> Prediksi SVM
                        </div>
                        <div class="card-body">
                            <div class="description-box">
                                <p>Klik tombol di bawah untuk melakukan prediksi risiko stroke pada data pasien menggunakan algoritma Machine Learning SVM.</p>
                            </div>
                            <button type="button" class="btn btn-primary btn-width" id="prediksiBtn">
                                <i class="fas fa-bolt"></i> Prediksi Sekarang
                            </button>
                            <button type="button" class="btn btn-danger btn-width" id="hapusBtn">
                                <i class="fas fa-trash-alt"></i> Hapus Semua Data
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
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
                        <div class="card-body p-0">
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
                                <p>Belum ada data pasien</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <svg class="wave-decoration" viewBox="0 0 1440 320" preserveAspectRatio="none">
        <path fill="#ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,165.3C384,171,480,149,576,128C672,107,768,85,864,90.7C960,96,1056,128,1152,138.7C1248,149,1344,139,1392,133.3L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
    </svg>

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
                        hoverOffset: 15
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
                                padding: 25,
                                usePointStyle: true,
                                font: {
                                    size: 14,
                                    weight: '600',
                                    family: "'Plus Jakarta Sans', sans-serif"
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(12, 74, 110, 0.95)',
                            titleFont: {
                                size: 15,
                                weight: '700',
                                family: "'Plus Jakarta Sans', sans-serif"
                            },
                            bodyFont: {
                                size: 14,
                                weight: '600',
                                family: "'Plus Jakarta Sans', sans-serif"
                            },
                            padding: 16,
                            cornerRadius: 12,
                            displayColors: true,
                            boxPadding: 8
                        }
                    },
                    animation: {
                        animateRotate: true,
                        animateScale: true
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
            
            showAlert('success', '✅ File siap diimport: ' + file.name);
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
                    showAlert('success', '✅ ' + data.message);
                    updateStats();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                showAlert('error', '❌ Error: ' + error.message);
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
                        '🎉 Prediksi Selesai!<br>' +
                        '<strong>Risiko Stroke:</strong> ' + data.stroke + '<br>' +
                        '<strong>Tidak Stroke:</strong> ' + data.tidak_stroke
                    );
                    updateStats();
                    updateChart(data.stroke, data.tidak_stroke);
                    refreshTable();
                } else {
                    showAlert('error', '❌ ' + data.message);
                }
            })
            .catch(error => {
                showAlert('error', '❌ Error: ' + error.message);
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
                    showAlert('success', '✅ ' + data.message);
                    updateStats();
                    updateChart(0, 0);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('error', '❌ ' + data.message);
                }
            })
            .catch(error => {
                showAlert('error', '❌ Error: ' + error.message);
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
