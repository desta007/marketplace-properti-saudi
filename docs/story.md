ini adalah Product Requirement Document (PRD) yang komprehensif dan alur kerja (workflow) untuk aplikasi Marketplace Properti di Arab Saudi.

Pasar properti Saudi sangat unik karena regulasi pemerintah yang ketat (REGA - Real Estate General Authority) dan transformasi digital yang masif (Vision 2030). Dokumen ini disesuaikan untuk kebutuhan pasar tersebut.
Product Requirement Document (PRD): Saudi Property Marketplace

Nama Proyek (Sementara): "SaudiProp" Versi: 1.0 (MVP Phase) Target Pasar: Arab Saudi (KSA) Platform: Mobile App (iOS/Android) & Web Admin
1. Executive Summary

Aplikasi ini bertujuan untuk menghubungkan pencari properti (penyewa/pembeli) dengan pemilik properti, pengembang, dan agen berlisensi di Arab Saudi. Fokus utama adalah kepercayaan (trust), kepatuhan regulasi (compliance), dan kemudahan pencarian berbasis lokasi.
2. User Personas (Target Pengguna)

    Seeker (Pencari): Warga lokal atau ekspatriat yang mencari tempat tinggal/kantor. Mengutamakan foto akurat, lokasi presisi, dan harga transparan.

    Lister (Agen/Broker Berlisensi): Agen real estate yang memiliki lisensi Valuation/Fal dari REGA. Butuh manajemen listing cepat dan prospek berkualitas.

    Owner (Pemilik Langsung): Pemilik aset yang ingin menyewakan/menjual tanpa perantara (atau dengan perantara).

    Admin: Tim internal yang memverifikasi lisensi dan memantau konten.

3. Fitur Utama (Core Features - MVP)
A. Lokalisasi & Kepatuhan (Krusial untuk KSA)

    Dual Language (RTL/LTR): Dukungan penuh Bahasa Arab (Right-to-Left) dan Inggris. UI harus adaptif (mirroring).

    Integrasi Peta: Menggunakan Google Maps atau penyedia lokal, mendukung Saudi National Address (sistem alamat resmi KSA).

    Verifikasi Identitas: Integrasi (atau persiapan integrasi) dengan Nafath/Absher untuk verifikasi identitas pengguna (sangat penting untuk trust).

    Listing License Field: Kolom wajib bagi agen untuk memasukkan nomor lisensi iklan REGA (Wajib hukumnya di Saudi agar tidak didenda).

B. Modul Pencari (Seeker)

    Advanced Search: Filter berdasarkan Kota (Riyadh, Jeddah, dll), Tipe (Villa, Apartemen, Compound, Tanah), Harga, Jumlah Kamar, dan Arah Kiblat (opsional tapi disukai).

    Map View Search: Mencari properti dengan menggambar area di peta.

    Virtual Tour: Dukungan upload video 360 atau link Matterport (sangat diminati di pasar mewah Saudi).

    Direct Contact: Tombol WhatsApp (saluran komunikasi utama di Saudi) dan In-App Chat.

C. Modul Lister (Agen/Owner)

    Post Property: Form input detail (Luas, Fasilitas: Majlis, Maid Room, Driver Room - ini istilah umum di properti Saudi).

    Lead Management: Dashboard sederhana melihat siapa yang menghubungi.

    Featured Listing: Opsi berbayar untuk menaikkan posisi iklan.

4. Alur Proses (User Flow)
Alur 1: Pencari Properti (Seeker Flow)

    Onboarding: Pilih Bahasa (Arab/Inggris) -> Login (OTP via SMS - Wajib karena nomor HP Saudi terikat ID).

    Home: Melihat kategori populer (misal: "Apartemen di Riyadh Utara").

    Search & Filter: User memilih filter "Villa", "Jeddah", "Budget 50k-80k SAR/tahun".

    Result: Tampil list dan Peta.

    Detail Page: Melihat foto, cek nomor lisensi iklan (verifikasi), lokasi peta.

    Action: Klik "WhatsApp Agent" atau "Request Viewing".

Alur 2: Posting Properti (Agent Flow)

    Registration: Input data diri + Upload Lisensi Fal (REGA License).

    Add Listing:

        Step 1: Lokasi (Pin point di peta / National Address).

        Step 2: Detail (Tipe, Luas, Fasilitas Khusus seperti Separate Entrance).

        Step 3: Media (Foto/Video).

        Step 4: Legalitas (Input Nomor Izin Iklan).

    Review: Admin atau Sistem mengecek validitas nomor izin.

    Published: Iklan tayang.

5. Technical Stack Recommendation

Mengingat Anda memiliki pengalaman dengan Flutter dan Laravel, stack ini sangat cocok dan powerful untuk MVP ini:

    Mobile App: Flutter (Satu codebase untuk iOS & Android). Sangat mudah mengatur RTL (Bahasa Arab) di Flutter.

    Backend API: Laravel (PHP). Tangguh untuk menangani logic marketplace dan manajemen database.

    Database: MySQL (Relational data) + PostGIS (jika butuh fitur geo-spatial kompleks) atau cukup simpan lat/long standar.

    Maps: Google Maps SDK (Biaya per load) atau Mapbox / OpenStreetMap (Lebih hemat).

    Storage: AWS S3 atau DigitalOcean Spaces (untuk foto/video properti).

    SMS Gateway: Twilio atau penyedia lokal Saudi (seperti Unifonic) untuk OTP.

    Sistem backend sebaiknya mendukung konversi kalender Masehi (Gregorian) dan Hijriah, meskipun bisnis properti sekarang dominan Masehi.