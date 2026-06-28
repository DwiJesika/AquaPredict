# AquaPredict: Smart Fisheries Monitoring System

AquaPredict adalah aplikasi web yang dikembangkan sebagai solusi cerdas untuk memantau, menganalisis, dan memberikan rekomendasi perawatan kualitas air kolam budidaya perikanan air tawar dalam konteks *Smart Fisheries – Living Lab*.

## Deskripsi Project
AquaPredict menyediakan platform digital untuk mendokumentasikan riwayat kualitas air secara sistematis. Aplikasi ini mengintegrasikan **Laravel** sebagai *backend* web dengan **FastAPI** sebagai layanan *Machine Learning* untuk pemrosesan data prediktif secara otomatis, memberikan rekomendasi mitigasi yang cepat dan aplikatif bagi pembudidaya.

## Latar Belakang & Permasalahan
Sektor budidaya perikanan air tawar sering terkendala fluktuasi kualitas air yang dinamis. Pembudidaya umumnya masih mengandalkan intuisi manual, ketiadaan data historis, serta keterlambatan dalam pengambilan tindakan mitigasi yang berisiko pada tingkat mortalitas ikan. AquaPredict hadir untuk mentransformasi praktik konvensional menjadi berbasis data (*data-driven*).

## Fitur Utama
- **Dashboard Interaktif:** Ringkasan statistik, distribusi status kualitas air (Optimal/Atensi/Kritis), dan log terbaru.
- **Manajemen Kolam (CRUD):** Pengelolaan data kolam budidaya.
- **Log Kualitas Air (CRUD):** Pencatatan 14 parameter kualitas air (pH, Suhu, DO, dll).
- **Prediksi Kualitas Air (AI):** Analisis otomatis via FastAPI dengan model *Random Forest*.
- **Rekomendasi Mitigasi:** Saran tindakan spesifik berdasarkan parameter yang terdeteksi bermasalah.
- **Fitur Analyzer (Sandbox):** Simulasi analisis tanpa menyimpan data.
- **Konsultasi Budidaya:** Tanya-jawab berbasis kata kunci.
- **Manajemen Pengguna (RBAC):** Hak akses Admin dan Petani.

## Arsitektur & Teknologi
Aplikasi menggunakan arsitektur *hybrid* yang memisahkan tanggung jawab:
- **Laravel (Port 8000):** Menangani *routing*, autentikasi, manajemen data, tampilan (Blade), dan integrasi API (Guzzle).
- **FastAPI (Port 8001):** Layanan *Machine Learning* untuk prediksi dan konsultasi.
- **Database:** MySQL.
- **Frontend:** Tailwind CSS, Vite.

## Struktur Direktori Proyek
| Path | Keterangan |
| :--- | :--- |
| `app/Http/Controllers/` | Controller utama (Auth, Pond, WaterLog, dll.) |
| `app/Http/Middleware/` | `RoleMiddleware` untuk pengelolaan peran |
| `app/Models/` | Eloquent ORM Models |
| `database/migrations/` | Struktur tabel database |
| `fastapi_app/` | Layanan AI (FastAPI, Model, Endpoints) |
| `resources/views/` | Template Blade UI |

## Proses Instalasi

### Prasyarat
- PHP >= 8.2, Composer, Node.js & npm, MySQL 8.0, Python 3.x.

### Langkah 1 – Konfigurasi Laravel
```bash
composer install
cp .env.example .env
# Sesuaikan DB_DATABASE, DB_USERNAME, DB_PASSWORD di .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

### Langkah 2 – Instalasi & Menjalankan FastAPI
```bash
cd fastapi_app
pip install fastapi uvicorn scikit-learn pandas joblib
python main.py
```

## Kontribusi
Project ini dikerjakan oleh kelompok 3 untuk mata kuliah Pemrograman Web Framework.
- **Ni Kadek Dwi Jesika Sari**: Mengembangkan backend aplikasi menggunakan Laravel, merancang dan melakukan pelatihan model Machine Learning dari dataset (.ipynb) hingga menghasilkan model akhir (.joblib), melakukan integrasi model ke layanan FastAPI, serta menyusun bagian Abstrak dan Introduction pada laporan.
- **Kadek Annamira Dwiva Yunita**: Bertanggung jawab dalam pembuatan materi presentasi dan penyiapan skenario demo aplikasi.
- **Gusti Bagus Alvian Ditya Parasurama**: Membuat perancangan antarmuka pengguna (UI/UX) menggunakan Tailwind CSS, pengembangan fitur analisis kelayakan air, serta melakukan validasi form pada sisi frontend.
- **Putu Agus Dhion Devandra**: Pembuatan wireframe aplikasi, pengujian fungsionalitas CRUD secara menyeluruh, penyusunan bagian Method dan Results pada laporan, serta penyusunan dokumentasi manual instalasi aplikasi.

## Akun Default Aplikasi
| Peran | Email | Password | Role |
| :--- | :--- | :--- | :--- |
| **Admin** | admin@aquapredict.com | password | admin |
| **Petani** | petani@aquapredict.com | password | petani |

## Lisensi
[MIT License](https://opensource.org/licenses/MIT)
