# Sistem Antrian Digital

Sistem antrian berbasis web untuk Rumah Sakit, dibangun menggunakan **Laravel 12**, **Vue.js**, dan **MySQL**.  
Aplikasi ini dirancang untuk membantu pengelolaan antrian pasien secara real-time dengan fitur utama:

- **Halaman Beranda**  
  Menampilkan informasi antrian dan staff pada setiap loket secara real-time untuk pasien.
  
- **Halaman Call**  
  Digunakan oleh staff untuk memanggil antrian pasien, menyelesaikan antrian, dan mengatur loket.

- **Halaman Queue**  
  Digunakan oleh user untuk mengambil nomor antrian.

- **Halaman Dashboard**  
  Menyediakan informasi ringkas seperti jumlah antrian aktif, staff yang tersedia, dan statistik pelayanan staff.

---

## 📦 Requirements

Sebelum menjalankan aplikasi ini, pastikan environment berikut sudah terpasang di sistem:

- **PHP** >= 8.3
- **Composer** >= 2.x
- **Node.js** >= 20.x & **npm** >= 10.x
- **MySQL** >= 8.x

---
## 🖥 Installation & Running

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di local development:

### 1. Clone Repository
```bash
git clone https://github.com/truekei/rspb-queue-system.git
cd rspb-queue-system
```
### 2. Install Dependencies
```bash
# Install dependency PHP
composer install

# Install dependency JavaScript
npm install
```
### 3. Konfigurasi Environment
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Atur konfigurasi database sesuai kebutuhan (contoh MySQL):
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rspb_queue_system
DB_USERNAME=root
DB_PASSWORD=
```
### 4. Generate Key Laravel
```bash
php artisan key:generate
```
### 5. Migrasi & Seeder Database
```bash
php artisan migrate --seed
```
### 6. Jalankan Development Server
Jalankan Laravel, Vite, Queue Worker, dan Reverb sekaligus dengan command:
```bash
composer run dev
```
#### Aplikasi bisa diakses di `http://localhost:8000`
