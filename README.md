# Website Prediksi Risiko Stroke dengan Algoritma SVM

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)
![Python](https://img.shields.io/badge/Python-3.11-yellow.svg)

Website sistem prediksi risiko stroke menggunakan algoritma Support Vector Machine (SVM) berbasis data kesehatan pasien.

## 📋 Deskripsi Projek

Projek ini merupakan implementasi machine learning menggunakan algoritma Support Vector Machine (SVM) untuk memprediksi risiko stroke berdasarkan data kesehatan pasien. Sistem ini dibangun dengan framework Laravel (PHP) untuk backend dan Python untuk model machine learning.

## 🚀 Fitur Utama

- **Upload Data Pasien**: Drag & drop atau klik untuk upload file CSV/Excel
- **Import ke Database**: Menyimpan data pasien ke database
- **Prediksi SVM**: Melakukan prediksi risiko stroke menggunakan model SVM
- **Visualisasi Hasil**: Chart doughnut untuk menampilkan hasil prediksi
- **Data Terbaru**: Tabel data pasien dengan status prediksi

## 🛠️ Teknologi yang Digunakan

### Backend
- **Laravel 12** - Framework PHP
- **MySQL** - Database
- **PhpSpreadsheet** - Untuk membaca file Excel/CSV

### Machine Learning
- **Python 3.11** - Bahasa pemrograman untuk ML
- **NumPy** - Library komputasi numerik
- **Scikit-learn** - Library machine learning
- **Pandas** - Library manipulasi data

### Frontend
- **Bootstrap 5** - Framework CSS
- **Chart.js** - Library visualisasi data
- **Font Awesome** - Icon library

## 📊 Alur Sistem

```
┌─────────────────┐     ┌──────────────────┐     ┌─────────────────┐
│  Upload File    │ ──► │  Import ke       │ ──► │  Prediksi       │
│  CSV/Excel     │     │  Database        │     │  Sekarang       │
└─────────────────┘     └──────────────────┘     └─────────────────┘
                                                            │
                                                            ▼
                         ┌──────────────────────────────────────────┐
                         │  Hasil Prediksi (Chart Doughnut + Tabel)│
                         └──────────────────────────────────────────┘
```

## 📁 Struktur Folder

```
web-svm-stroke/
├── app/
│   ├── Http/Controllers/
│   │   └── PrediksiController.php    # Controller utama
│   └── Models/
│       └── Pasien.php                # Model pasien
├── database/
│   └── migrations/
│       └── 2024_01_01_000001_create_pasien_table.php
├── python/
│   ├── prediksi.py                  # Script prediksi SVM
│   └── model/
│       ├── svm_model.pkl            # Model SVM terlatih
│       └── scaler.pkl               # Scaler untuk normalisasi
├── resources/
│   └── views/
│       └── prediksi/
│           └── index.blade.php       # Halaman utama
├── public/
│   └── sample_data.csv              # Sample data untuk testing
└── routes/
    └── web.php                      # Definisi route
```

## 📋 Format Data Pasien

File CSV/Excel harus memiliki kolom berikut:

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | Integer | ID unik pasien |
| gender | String | Male/Female/Other |
| age | Float | Umur pasien |
| hypertension | Integer | 0 atau 1 |
| heart_disease | Integer | 0 atau 1 |
| ever_married | String | Yes/No |
| work_type | String | Private/Self-employed/Govt_job/children/Never_worked |
| Residence_type | String | Urban/Rural |
| avg_glucose_level | Float | Level glukosa rata-rata |
| bmi | Float | Body Mass Index |
| smoking_status | String | smokes/formerly smoked/never smoked/Unknown |

## 🖥️ Cara Install

### 1. Clone Repository
```bash
git clone https://github.com/ardhikaxx/web-svm-stroke.git
cd web-svm-stroke
```

### 2. Install Dependencies PHP
```bash
composer install
```

### 3. Install Dependencies Python
```bash
python -m venv venv
venv\Scripts\pip install numpy scikit-learn pandas
```

### 4. Setup Database
```bash
# Buat database MySQL dengan nama 'web_svm_stroke'
# Konfigurasi .env sesuai database Anda

php artisan migrate
```

### 5. Jalankan Server
```bash
php artisan serve
```

Buka browser dan akses `http://127.0.0.1:8000`

## 📖 Cara Penggunaan

1. **Buka Website**: Akses `http://127.0.0.1:8000`
2. **Upload Data**: Klik area upload atau drag & drop file CSV/Excel
3. **Import ke Database**: Klik tombol "Import ke Database"
4. **Prediksi**: Klik tombol "Prediksi Sekarang"
5. **Lihat Hasil**: Hasil prediksi akan muncul di chart dan tabel

## 🔧 Konfigurasi

### Konfigurasi Database (.env)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=web_svm_stroke
DB_USERNAME=root
DB_PASSWORD=
```

### Konfigurasi Python
Path Python sudah dikonfigurasi untuk menggunakan virtual environment:
```
venv\Scripts\python.exe
```

## 📝 Lisensi

MIT License - lihat file [LICENSE](LICENSE) untuk detail.

## 👤 Author

- **Ardhika** - [GitHub](https://github.com/ardhikaxx)

## 🙏 Acknowledgments

- Dataset stroke dari Kaggle
- Laravel Framework
- Scikit-learn Documentation
