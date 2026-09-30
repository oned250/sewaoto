# CHANGELOG: RYOKOURENT

Semua perubahan penting pada proyek Ryokourent akan didokumentasikan di file ini.
Format penulisan berpedoman pada [Keep a Changelog](https://keepachangelog.com/id/1.0.0/) dan mengikuti standar [Semantic Versioning](https://semver.org/).

---

## [Unreleased] - 2026-09-30

### Added
- **Fase 0 (Analisis dan Perencanaan):**
  - Penyusunan `PROJECT_OVERVIEW.md` mencakup tujuan, target pengguna, ruang lingkup MVP, dan asumsi bisnis.
  - Penyusunan `ARCHITECTURE.md` mengatur pemisahan plugin `ryokourent-core` dan child theme `generatepress-child`.
  - Penyusunan `DATA_MODEL.md` mencakup spesifikasi CPT `motor`, CPT `penyewaan`, taxonomy `kategori_motor`, meta fields, 5 status kustom, dan relasi data.
  - Penyusunan `TASKS.md` merinci 30 task implementasi berurutan dari TASK-001 hingga TASK-030.
  - Penyusunan `AI_WORKFLOW.md` dan `AI_RULES.md` mengatur pembagian kerja AI, standar git branching, dan batasan operasional.
  - Penyusunan `DECISIONS.md` mendokumentasikan 7 Architectural Decision Records (ADR-001 s/d ADR-007).
  - Penyusunan `TESTING.md` memetakan 20 skenario uji (TC-001 s/d TC-020).
  - Penyusunan `.env.example` dan `CONFIG.example.php`.
- **Fase 2 (TASK-004: Custom Post Type Motor & Meta Fields):**
  - Pembuatan file `wp-content/plugins/ryokourent-core/includes/post-types.php` untuk pendaftaran CPT `motor` (Armada Motor), dukungan Gutenberg REST API, arsip `motor`, thumbnail, excerpt, dan custom updated messages.
  - Pembuatan file `wp-content/plugins/ryokourent-core/includes/meta-fields.php` untuk registrasi skema WordPress `register_post_meta()` untuk 10 atribut CPT `motor` (`_ryokou_engine_cc`, `_ryokou_transmission`, `_ryokou_route_character`, `_ryokou_is_bromo_ready`, `_ryokou_price_daily`, `_ryokou_price_weekly`, `_ryokou_price_monthly`, `_ryokou_physical_stock`, `_ryokou_plate_numbers`, `_ryokou_status_label`) dilengkapi fungsi sanitasi dan validasi capability.
  - Pembuatan file `wp-content/plugins/ryokourent-core/admin/motor-columns.php` untuk kustomisasi tabel daftar armada di WP-Admin (preview foto, spesifikasi mesin, tarif harian berformat Rupiah, kuota unit fisik, badge rute Bromo, badge ketersediaan publik) beserta sortable columns.
  - Pembuatan file automated test `wp-content/plugins/ryokourent-core/tests/test-cpt-motor.php` untuk pengujian parameter CPT, meta sanitasi, dan kolom admin.
- **Fase 2 (TASK-003: Plugin Loader & Helper Dasar):**
  - Pembuatan file `wp-content/plugins/ryokourent-core/includes/helpers.php` yang memuat fungsi utilitas inti: formatting Rupiah (`ryokourent_format_rupiah`), sanitasi & validasi nomor WhatsApp internasional (`ryokourent_sanitize_phone`, `ryokourent_is_valid_phone`), manipulasi waktu zona WIB (`ryokourent_get_timezone`, `ryokourent_get_now_wib`, `ryokourent_format_datetime_id`), perhitungan durasi sewa & toleransi 24 jam (`ryokourent_calculate_duration_hours`, `ryokourent_calculate_rental_days`), validasi jam operasional 07.00-23.00 WIB (`ryokourent_is_within_operating_hours`), sanitasi NIK e-KTP dan plat nomor motor, serta getter status booking dan lokasi pool resmi.
  - Pembaruan bootstrap loader `wp-content/plugins/ryokourent-core/ryokourent-core.php` untuk memuat helper secara otomatis dan menyiapkan modular autoloading pada hook `plugins_loaded`.
  - Pembuatan automated unit testing script `wp-content/plugins/ryokourent-core/tests/test-helpers.php` untuk verifikasi fungsi helper secara independen.

  - Inisialisasi struktur repositori resmi dengan `.editorconfig` dan `.phpcs.xml.dist`.
  - Pembuatan kerangka plugin `wp-content/plugins/ryokourent-core/` beserta file bootstrap, `uninstall.php`, `readme.txt`, dan struktur folder modular (`includes/`, `admin/`, `public/`, `assets/`, `tests/`).
  - Pembuatan kerangka child theme `wp-content/themes/generatepress-child/` beserta `style.css` dan `functions.php`.
  - Pembuatan dokumen panduan teknis pada direktori `/docs/` (instalasi lokal, hosting, coding standards, git workflow, checklist pra-merge, dan checklist produksi).
