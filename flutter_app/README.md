# 📱 Flutter Portal Instansi (Frontend)

Aplikasi klien frontend berbasis **Flutter 3** untuk portal publik instansi, terintegrasi penuh dengan REST API Laravel 13, mendukung navigasi modern berbasis [**go_router**](https://pub.dev/packages/go_router) dan fitur **dwibahasa (Multilingual: Indonesia 🇮🇩 & English 🇬🇧)**.

---

## 🌟 Fitur Unggulan

1. **Navigasi Terstruktur dengan `go_router`:**
   - `StatefulShellRoute` dengan bottom navigation bar 5 tab: Beranda (`/`), Berita & Artikel (`/posts`), FAQ (`/faqs`), Tanya Kami (`/questions`), dan Pengaturan (`/settings`).
   - Deep-linking & parameterized routing: `/posts/:slug`, `/pages/:slug`, `/categories/:slug`, `/questions/create`.
2. **Dukungan Penuh Multibahasa (ID & EN):**
   - Menggunakan `AppLocalizations` & `LocaleProvider` dengan persistensi otomatis via `shared_preferences`.
   - Mengirimkan header `Accept-Language: id` atau `Accept-Language: en` (serta query `?locale=`) pada setiap panggilan API, sehingga data dinamis dari Laravel Spatie Translatable otomatis tersaji sesuai bahasa aktif.
   - Tombol pengalih bahasa instan di AppBar dan dialog pemilih bahasa.
3. **Integrasi Seluruh REST API (`/api/v1`):**
   - **Slide Banner (`/slides`):** Carousel interaktif dengan indikator titik dan auto-play.
   - **Artikel & Berita (`/posts`):** Filter kategori, pencarian real-time, ringkasan, dan konten HTML lengkap (`flutter_widget_from_html`).
   - **Halaman Statis (`/pages/{slug}`):** Profil instansi, visi-misi, sarana prasarana.
   - **FAQ & Bantuan (`/faqs`):** Accordion per pertanyaan yang dapat diciutkan/dibuka dengan pencarian instan.
   - **Layanan Masyarakat / Tanya Kami (`/questions`):** Daftar pertanyaan publik terjawab beserta tanggapan resmi petugas, serta formulir pengajuan tiket pertanyaan baru (`POST /api/v1/questions`) dengan validasi input.
   - **Pengaturan & Profil Kontak (`/settings`):** Konfigurasi alamat server API dan informasi kontak instansi.

---

## 🚀 Cara Menjalankan Aplikasi

### 1. Jalankan Server Backend Laravel
Pastikan server backend aktif di port 8000:
```bash
php artisan serve
```

### 2. Jalankan Aplikasi Flutter
Masuk ke direktori `flutter_app`:
```bash
cd flutter_app
```

Pilih target platform:
```bash
# Menjalankan di Chrome / Web
flutter run -d chrome

# Menjalankan di Windows Desktop
flutter run -d windows

# Menjalankan di Emulator Android
flutter run -d emulator
```

> [!TIP]
> **Catatan Alamat IP API:**  
> - **Emulator Android:** Otomatis mendeteksi `http://10.0.2.2:8000/api/v1`  
> - **Windows Desktop / Web / iOS:** Otomatis mendeteksi `http://127.0.0.1:8000/api/v1`  
> - Alamat URL API dapat diubah sewaktu-waktu secara langsung melalui menu **Pengaturan (`/settings`)** di dalam aplikasi.

---

## 📂 Struktur Direktori

```
flutter_app/lib/
├── config/
│   └── api_config.dart          # Konfigurasi URL dasar & endpoint API
├── l10n/
│   ├── app_localizations.dart   # String terjemahan ID & EN
│   └── locale_provider.dart     # Provider pengelola bahasa aktif
├── models/
│   ├── category.dart            # Model Kategori
│   ├── faq.dart                 # Model FAQ
│   ├── menu.dart                # Model Navigasi Menu
│   ├── page_model.dart          # Model Halaman Statis
│   ├── post.dart                # Model Artikel & Berita
│   ├── question.dart            # Model Pertanyaan & Jawaban Publik
│   ├── question_topic.dart      # Model Topik Pertanyaan
│   ├── setting.dart             # Model Pengaturan
│   └── slide.dart               # Model Banner Slider
├── providers/
│   ├── portal_provider.dart     # State manajemen untuk beranda, berita, halaman
│   └── question_provider.dart   # State manajemen form tiket & pertanyaan
├── routes/
│   └── app_router.dart          # Konfigurasi go_router & shell route
├── screens/
│   ├── ask_question_screen.dart # Form pengajuan pertanyaan publik
│   ├── category_posts_screen.dart # Daftar berita berdasarkan kategori
│   ├── faqs_screen.dart         # Halaman FAQ interaktif
│   ├── home_screen.dart         # Beranda portal (slider, featured, berita terbaru)
│   ├── page_detail_screen.dart  # Halaman baca konten profil statis
│   ├── post_detail_screen.dart  # Halaman detail berita lengkap
│   ├── posts_screen.dart        # Halaman katalog berita & pencarian
│   ├── questions_screen.dart    # Halaman daftar pertanyaan terjawab
│   └── settings_screen.dart     # Halaman preferensi & kontak instansi
├── services/
│   └── api_service.dart         # HTTP Client penghubung API Laravel
├── widgets/
│   ├── app_drawer.dart          # Navigasi laci samping
│   ├── error_retry_view.dart    # Tampilan error koneksi dengan tombol coba lagi
│   ├── language_switch_dialog.dart # Dialog pemilih bahasa ID / EN
│   ├── loading_view.dart        # Indikator loading
│   ├── post_card.dart           # Kartu pratinjau berita
│   └── slide_carousel.dart      # Komponen slider banner
└── main.dart                    # Entry point aplikasi & tema Material 3
```
