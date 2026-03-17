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
            --primary: #0d9488;
            --primary-light: #2dd4bf;
            --primary-dark: #0f766e;
            --accent: #5eead4;
            --bg-gradient: linear-gradient(135deg, #021a1a 0%, #064e4b 50%, #022c2a 100%);
            --glass-bg: rgba(0, 0, 0, 0.25); 
            --glass-border: rgba(255, 255, 255, 0.15);
            --glass-blur: blur(16px);
            --card-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.5);
            --text-main: #ffffff;
            --text-muted: #ccfbf1;
            --white: #ffffff;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-gradient);
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--text-main);
            overflow-x: hidden;
            position: relative;
        }

        /* Decorative Background Elements */
        .deco-blob {
            position: fixed;
            z-index: -1;
            filter: blur(100px);
            border-radius: 50%;
            opacity: 0.25;
            pointer-events: none;
            animation: float-deco 25s infinite alternate;
        }

        .blob-1 {
            width: 600px;
            height: 600px;
            background: var(--primary-dark);
            top: -150px;
            right: -100px;
        }

        .blob-2 {
            width: 500px;
            height: 500px;
            background: #042f2e;
            bottom: -100px;
            left: -100px;
            animation-delay: -7s;
        }

        .blob-3 {
            width: 350px;
            height: 350px;
            background: var(--primary);
            top: 45%;
            left: 15%;
            animation-delay: -12s;
        }

        @keyframes float-deco {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(60px, 120px) scale(1.1); }
        }
        
        .navbar {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border-bottom: 1px solid var(--glass-border);
            padding: 20px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.7rem;
            color: var(--white) !important;
            display: flex;
            align-items: center;
            gap: 15px;
            letter-spacing: -0.5px;
        }
        
        .navbar-brand i {
            font-size: 2.2rem;
            color: var(--accent);
            filter: drop-shadow(0 0 8px rgba(94, 234, 212, 0.4));
        }
        
        .main-container {
            padding: 60px 0;
            position: relative;
            z-index: 1;
        }
        
        .page-header {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .page-title {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 15px;
            background: linear-gradient(to bottom, #ffffff, #cbd5e1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -1.5px;
        }
        
        .page-subtitle {
            color: rgba(255,255,255,0.9);
            font-size: 1.3rem;
            font-weight: 500;
            max-width: 700px;
            margin: 0 auto;
        }
        
        .glass-card {
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            border-radius: 30px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            height: auto;
        }
        
        .glass-card:hover {
            transform: translateY(-8px);
            background: rgba(0, 0, 0, 0.3);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
        }
        
        .card-header-custom {
            padding: 28px 32px;
            font-weight: 700;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 15px;
            border-bottom: 1px solid var(--glass-border);
            background: rgba(0, 0, 0, 0.3);
            color: var(--white);
        }
        
        .card-header-custom i {
            color: var(--accent);
        }
        
        .card-body {
            padding: 30px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.03));
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 32px;
            padding: 45px 25px;
            text-align: center;
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .stat-card:hover {
            transform: translateY(-12px) scale(1.02);
            background: rgba(255, 255, 255, 0.12);
            border-color: var(--accent);
            box-shadow: 0 25px 50px rgba(0,0,0,0.5), 0 0 20px rgba(13, 148, 136, 0.2);
        }

        .stat-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.05;
            background-image: radial-gradient(var(--accent) 1px, transparent 1px);
            background-size: 20px 20px;
            pointer-events: none;
            z-index: 1;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at top right, rgba(94, 234, 212, 0.1), transparent 60%);
            pointer-events: none;
        }

        .stat-icon {
            width: 80px;
            height: 80px;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            font-size: 2.2rem;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--accent);
            transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
            position: relative;
            z-index: 2;
        }
        
        .stat-card:hover .stat-icon {
            transform: rotateY(180deg);
            background: var(--accent);
            color: #042f2e;
            box-shadow: 0 0 30px rgba(94, 234, 212, 0.4);
        }
        
        .stat-number {
            font-size: 4rem;
            font-weight: 900;
            color: var(--white);
            line-height: 1;
            margin-bottom: 10px;
            letter-spacing: -2px;
            position: relative;
            z-index: 2;
        }
        
        .stat-label {
            color: var(--white);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            position: relative;
            z-index: 2;
            opacity: 0.8;
        }

        /* Specific card variants for colored glows */
        .stat-card.risk-high {
            border-bottom: 4px solid #ef4444;
        }
        .stat-card.risk-high .stat-icon {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }
        .stat-card.risk-high:hover .stat-icon {
            background: #ef4444;
            color: #ffffff;
            box-shadow: 0 0 30px rgba(239, 68, 68, 0.4);
        }
        .stat-card.risk-high .stat-number { color: #f87171; }

        .stat-card.status-healthy {
            border-bottom: 4px solid #10b981;
        }
        .stat-card.status-healthy .stat-icon {
            color: #10b981;
            background: rgba(16, 185, 129, 0.1);
        }
        .stat-card.status-healthy:hover .stat-icon {
            background: #10b981;
            color: #ffffff;
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.4);
        }
        .stat-card.status-healthy .stat-number { color: #34d399; }
        
        .upload-area {
            border: 2px dashed rgba(255, 255, 255, 0.2);
            border-radius: 26px;
            padding: 55px 35px;
            text-align: center;
            background: rgba(0, 0, 0, 0.15);
            cursor: pointer;
            transition: all 0.4s ease;
        }
        
        .upload-area:hover {
            background: rgba(0, 0, 0, 0.25);
            border-color: var(--accent);
            transform: scale(1.01);
        }
        
        .upload-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 12px;
        }
        
        .upload-subtitle {
            color: var(--text-muted);
            font-size: 1rem;
            margin-bottom: 35px;
            font-weight: 500;
        }
        
        .btn {
            padding: 18px 35px;
            border-radius: 20px;
            font-weight: 800;
            font-size: 1.05rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            border: none;
            color: var(--white);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transition: width 0.3s ease;
            z-index: -1;
        }

        .btn:hover::before {
            width: 100%;
        }
        
        .btn-primary {
            background: var(--primary);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .btn-primary:hover {
            background: var(--primary-light);
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(13, 148, 136, 0.5);
            color: #042f2e;
        }
        
        .btn-success {
            background: #059669;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .btn-success:hover {
            background: #10b981;
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }

        .btn-danger {
            background: #dc2626;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-danger:hover {
            background: #ef4444;
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
        }
        
        .btn-width {
            width: 100%;
            margin-bottom: 18px;
        }
        
        .description-box {
            background: rgba(0, 0, 0, 0.25);
            border-radius: 22px;
            padding: 28px;
            margin-bottom: 28px;
            border-left: 5px solid var(--accent);
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.2);
        }
        
        .description-box p {
            color: #e2fef9;
            margin: 0;
            line-height: 1.8;
            font-size: 1.05rem;
            font-weight: 500;
        }
        
        .table-container {
            border-radius: 24px;
            overflow: hidden;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .table {
            color: var(--white);
            margin: 0;
        }
        
        .table thead th {
            background: rgba(0, 0, 0, 0.4);
            color: var(--accent);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 22px;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }
        
        .table tbody td {
            padding: 20px 22px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            vertical-align: middle;
            background: transparent;
            font-weight: 600;
        }

        .table tbody tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }
        
        .badge-stroke {
            background: #ef4444;
            color: #ffffff;
            padding: 9px 18px;
            border-radius: 14px;
            font-weight: 800;
            font-size: 0.8rem;
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.4);
        }
        
        .badge-no-stroke {
            background: #10b981;
            color: #ffffff;
            padding: 9px 18px;
            border-radius: 14px;
            font-weight: 800;
            font-size: 0.8rem;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.4);
        }
        
        .chart-container {
            padding: 20px;
            min-height: 320px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .alert {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            color: var(--white);
            border-radius: 18px;
            padding: 20px;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent);
        }

        @media (max-width: 768px) {
            .page-title { font-size: 2.2rem; }
            .stat-number { font-size: 2.5rem; }
            .glass-card { margin-bottom: 20px; }
        }
    </style>
</head>
<body>
    <!-- Decorative Elements -->
    <div class="deco-blob blob-1"></div>
    <div class="deco-blob blob-2"></div>
    <div class="deco-blob blob-3"></div>

    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-microscope"></i>
                <span>STROKE<span style="color: var(--accent);">SVM</span></span>
            </a>
        </div>
    </nav>

    <div class="main-container">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">AI Prediksi Risiko Stroke</h1>
                <p class="page-subtitle">Sistem Prediksi Canggih dengan Algoritma Machine Learning SVM untuk Deteksi Dini Risiko Stroke</p>
            </div>
            
            <div class="row mb-5 g-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-pattern"></div>
                        <div class="stat-icon">
                            <i class="fas fa-users-viewfinder"></i>
                        </div>
                        <div class="stat-number" id="totalPasien">0</div>
                        <div class="stat-label">Total Pasien</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card risk-high">
                        <div class="stat-pattern"></div>
                        <div class="stat-icon">
                            <i class="fas fa-heart-pulse"></i>
                        </div>
                        <div class="stat-number" id="pasienStroke">0</div>
                        <div class="stat-label">Berisiko Stroke</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card status-healthy">
                        <div class="stat-pattern"></div>
                        <div class="stat-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="stat-number" id="pasienTidakStroke">0</div>
                        <div class="stat-label">Tidak Berisiko</div>
                    </div>
                </div>
            </div>

            <div class="row g-4 align-items-stretch">
                <div class="col-lg-6">
                    <div class="glass-card mb-4">
                        <div class="card-header-custom">
                            <i class="fas fa-cloud-arrow-up"></i> Import Data Pasien
                        </div>
                        <div class="card-body">
                            <form id="uploadForm" enctype="multipart/form-data">
                                @csrf
                                <div class="upload-area" id="uploadArea">
                                    <i class="fas fa-dna upload-icon mb-3" style="font-size: 4rem; color: var(--accent); opacity: 0.3;"></i>
                                    <div class="upload-title">Letakkan File Data Pasien</div>
                                    <div class="upload-subtitle" style="color: #ccfbf1;">Format CSV atau Excel (Maks 10MB)</div>
                                    <input type="file" id="fileInput" name="file" accept=".csv,.xlsx,.xls" style="display: none;">
                                    <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                                        <i class="fas fa-folder-tree"></i> Pilih Dataset
                                    </button>
                                </div>
                                <div class="file-info" id="fileInfo">
                                    <div>
                                        <div class="file-name" id="fileName" style="font-weight: 700; color: var(--white);"></div>
                                        <div class="file-size" id="fileSize" style="color: #ccfbf1; font-size: 0.9rem;"></div>
                                    </div>
                                </div>
                            </form>
                            
                            <div id="uploadStatus" class="mt-4"></div>
                            
                            <div class="mt-4">
                                <button type="button" class="btn btn-success btn-width" id="importBtn" disabled>
                                    <i class="fas fa-server"></i> Proses & Simpan Data
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card">
                        <div class="card-header-custom">
                            <i class="fas fa-brain"></i> Mesin Prediksi AI
                        </div>
                        <div class="card-body">
                            <div class="description-box">
                                <p>Inisialisasi kernel SVM untuk menganalisis parameter fisiologis dan menghasilkan profil risiko kesehatan pasien.</p>
                            </div>
                            <div class="d-flex flex-column gap-3">
                                <button type="button" class="btn btn-primary btn-width" id="prediksiBtn">
                                    <i class="fas fa-wand-magic-sparkles"></i> Jalankan Model Prediksi
                                </button>
                                <button type="button" class="btn btn-danger btn-width" id="hapusBtn">
                                    <i class="fas fa-eraser"></i> Hapus Semua Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="glass-card mb-4">
                        <div class="card-header-custom">
                            <i class="fas fa-chart-simple"></i> Wawasan Analitik
                        </div>
                        <div class="card-body p-0">
                            <div class="chart-container" id="chartContainer">
                                <div id="chartPlaceholder" class="text-center">
                                    <i class="fas fa-atom fa-spin-pulse" style="font-size: 4rem; color: var(--accent); opacity: 0.3; margin-bottom: 20px;"></i>
                                    <p style="color: #ffffff; font-weight: 700;">Menunggu analisis data...</p>
                                    <p class="small" style="color: #ccfbf1;">Upload data dan jalankan prediksi untuk melihat hasil</p>
                                </div>
                                <canvas id="doughnutChart" style="display: none;"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card">
                        <div class="card-header-custom">
                            <i class="fas fa-table-list"></i> Registri Pasien
                        </div>
                        <div class="card-body p-0">
                            <div class="table-container" style="max-height: 400px; overflow-y: auto;">
                                <table class="table" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Ref</th>
                                            <th>Umur</th>
                                            <th>Gen</th>
                                            <th>Glukosa</th>
                                            <th>BMI</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="empty-state p-5 text-center" id="emptyState">
                                <i class="fas fa-box-open" style="font-size: 3.5rem; color: var(--glass-border); margin-bottom: 15px;"></i>
                                <p style="color: #ffffff; font-weight: 700;">Registri data masih kosong</p>
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
                    labels: ['Tidak Berisiko', 'Berisiko'],
                    datasets: [{
                        data: [1, 0],
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.5)',
                            'rgba(239, 68, 68, 0.5)'
                        ],
                        borderColor: [
                            '#10b981',
                            '#ef4444'
                        ],
                        borderWidth: 2,
                        hoverOffset: 20,
                        borderRadius: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 30,
                                usePointStyle: true,
                                color: '#ffffff',
                                font: {
                                    size: 13,
                                    weight: '700',
                                    family: "'Plus Jakarta Sans', sans-serif"
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: {
                                size: 14,
                                weight: '700',
                                family: "'Plus Jakarta Sans', sans-serif"
                            },
                            bodyFont: {
                                size: 13,
                                weight: '600',
                                family: "'Plus Jakarta Sans', sans-serif"
                            },
                            padding: 15,
                            cornerRadius: 15,
                            displayColors: true,
                            borderColor: 'rgba(255,255,255,0.1)',
                            borderWidth: 1
                        }
                    },
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 2000,
                        easing: 'easeOutQuart'
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
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';

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
                    showAlert('success', '✅ Berhasil: ' + data.message);
                    updateStats();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                showAlert('error', '❌ Kesalahan: ' + error.message);
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
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menganalisis...';

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
                        '🎉 Analisis AI Selesai!<br>' +
                        '<strong>Berisiko Stroke:</strong> ' + data.stroke + '<br>' +
                        '<strong>Tidak Berisiko:</strong> ' + data.tidak_stroke
                    );
                    updateStats();
                    updateChart(data.stroke, data.tidak_stroke);
                    refreshTable();
                } else {
                    showAlert('error', '❌ ' + data.message);
                }
            })
            .catch(error => {
                showAlert('error', '❌ Kesalahan: ' + error.message);
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        }

        function hapusData() {
            if (!confirm('Apakah Anda yakin ingin menghapus seluruh data registri?')) {
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
                showAlert('error', '❌ Kesalahan: ' + error.message);
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
                            ? '<span class="badge-stroke"><i class="fas fa-warning me-1"></i> Berisiko</span>' 
                            : '<span class="badge-no-stroke"><i class="fas fa-check-circle me-1"></i> Sehat</span>';
                        
                        row.innerHTML = `
                            <td><span style="opacity: 0.5;">#</span>${index + 1}</td>
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
            const icon = type === 'success' ? 'circle-check' : 'circle-exclamation';
            const color = type === 'success' ? '#22c55e' : '#f87171';
            
            statusDiv.innerHTML = `
                <div class="alert fade show" role="alert" style="border-left: 4px solid ${color};">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-${icon}" style="color: ${color}; font-size: 1.2rem;"></i>
                        <div>${message}</div>
                        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert" style="font-size: 0.8rem;"></button>
                    </div>
                </div>
            `;
            
            setTimeout(() => {
                const alert = statusDiv.querySelector('.alert');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
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
