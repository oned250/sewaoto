# SESSION STATE: RYOKOURENT

Dokumen ini melacak status pengerjaan sesi, task aktif, dependensi yang telah terpenuhi, dan rencana task selanjutnya.

---

## 1. Status Sesi Saat Ini
* **Tanggal / Waktu:** 2026-09-30
* **Cabang Git Aktif:** `develop`
* **Daftar Cabang Proyek Terdaftar (sesuai `GIT_WORKFLOW.md`):**
  - `main` (Branch produksi resmi)
  - `develop` (Branch integrasi aktif)
  - `feature/cpt-motor` (Fitur CPT & katalog armada motor)
  - `feature/cpt-booking` (Fitur CPT penyewaan & transaksi sewa)
  - `feature/booking-form` (Fitur formulir pemesanan & kalkulasi)
  - `feature/pricing` (Fitur kalkulator tarif harian, mingguan, bulanan)
  - `feature/availability` (Fitur pencegahan double booking & pengecekan stok unit)
  - `feature/whatsapp` (Fitur generator draft pesan & URL WhatsApp)
  - `feature/admin-dashboard` (Fitur dashboard admin & pelaporan operasional)
* **Task Terakhir Selesai:** `TASK-008: Buat Halaman Detail Motor`
* **Status Task Terakhir:** **DONE (SELESAI)**
* **Task Selanjutnya:** `TASK-009: Buat Custom Post Type penyewaan` (Menunggu perintah persetujuan pengguna)

---

## 2. Riwayat Progress Task

| ID Task | Nama Task | Fase | Status | Dependensi | Tanggal Selesai |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TASK-001** | Analisis Blueprint & Dokumentasi | FASE 0 | **DONE** | - | 2026-09-30 |
| **TASK-002** | Struktur Repository & Kerangka Proyek | FASE 1 | **DONE** | TASK-001 | 2026-09-30 |
| **TASK-003** | Plugin Loader & Helper Dasar | FASE 2 | **DONE** | TASK-002 | 2026-09-30 |
| **TASK-004** | Custom Post Type `motor` | FASE 2 | **DONE** | TASK-003 | 2026-09-30 |
| **TASK-005** | Metabox Spesifikasi & Kuota Motor | FASE 2 | **DONE** | TASK-004 | 2026-09-30 |
| **TASK-006** | Taxonomy Kategori Motor | FASE 2 | **DONE** | TASK-004 | 2026-09-30 |
| **TASK-007** | Tampilan Katalog Motor | FASE 2 | **DONE** | TASK-005, TASK-006 | 2026-09-30 |
| **TASK-008** | Halaman Detail Motor | FASE 2 | **DONE** | TASK-007 | 2026-09-30 |
| **TASK-009** | Custom Post Type `penyewaan` | FASE 3 | **PENDING** | TASK-004 | - |

---

## 3. Komponen yang Telah Diimplementasikan pada TASK-008
1. **Template Single Post Motor (`wp-content/themes/generatepress-child/templates/single-motor.php` & `single-motor.php`):**
   - Header & breadcrumbs "Kembali ke Katalog Armada".
   - Media showcase & floating status badges (Tersedia / Booking Menipis / Penuh).
   - Spesifikasi teknis terstruktur: Kapasitas mesin (cc), transmisi, karakter rute, dan sistem bahan bakar.
   - Kotak edukasi & peringatan rute Bromo adaptif (peringatan larangan skutik ke pasir Bromo vs unit Trail CRF 150L resmi Bromo).
   - Fasilitas standar gratis (2 Helm SNI steril, 2 Jas Hujan setelan, phone holder stang, dan bantuan darurat jalan).
   - Sticky Pricing Sidebar: Tarif resmi harian dengan catatan toleransi overtime 2 jam, paket mingguan 7 hari, paket bulanan 30 hari.
   - Quick rental requirements: e-KTP asli, 2 dokumen pendukung, dan akun media sosial.
   - Dual CTA Action: Tombol booking cepat langsung memilih unit dan tombol Chat WhatsApp Admin terformat rapi.
2. **Filter Template Hierarchy (`wp-content/themes/generatepress-child/functions.php`):**
   - Hook `single_template` untuk resolusi otomatis template `templates/single-motor.php` dan fallback ke root child theme.
   - Enqueue dinamis stylesheet publik ketika post single motor dikunjungi.
3. **Styling Elegan Tema Gelap (`wp-content/themes/generatepress-child/style.css`):**
   - Layout grid 2 kolom desktop dengan sticky sidebar dan 1 kolom responsif pada smartphone.

---

## 4. Komponen yang Telah Diimplementasikan pada TASK-007
1. **Shortcode `[ryokou_catalog]` (`wp-content/plugins/ryokourent-core/public/shortcodes.php`):**
   - Mendaftarkan shortcode fleksibel dengan parameter `kategori`, `limit`, `show_filter`, dan `columns`.
   - Enqueue aset CSS dan JS secara terisolasi hanya pada halaman yang memuat katalog.
   - Localize script configuration (`ajaxUrl`, `waNumber`, `bookingAnchor`).
2. **Template Renderer Motor Card & Grid (`wp-content/plugins/ryokourent-core/public/templates.php`):**
   - Fungsi `ryokourent_render_motor_card()` dan `ryokourent_render_catalog_grid()`.
   - Menampilkan 7 armada resmi blueprint dengan fallback yang kokoh jika basis data WordPress belum terisi.
   - Format harga rapi (`Rp xx.xxx / 24 Jam` atau placeholder `Tanya Admin`).
   - Badges status ketersediaan (hijau, amber, merah) dan penanda rute Bromo.
   - Dual action button: "Sewa Sekarang" dan "Chat WA" dengan format pesan WhatsApp yang di-encode rapi.
   - Banner garansi 4 poin kepercayaan di bawah grid katalog.
3. **Desain Mobile-First & Filter Interaktif (`assets/css/ryokourent-public.css` & `assets/js/ryokourent-filter.js`):**
   - Tab filter pills kategori tanpa reload halaman (*zero jQuery, pure vanilla JS*).
   - Tampilan *empty state* interaktif dengan tombol reset filter.
   - Integrasi auto-select dropdown armada form booking saat tombol "Sewa Sekarang" diklik.

---

## 5. Komponen yang Telah Diimplementasikan pada TASK-006
1. **Taxonomy `kategori_motor` (`wp-content/plugins/ryokourent-core/includes/taxonomies.php`):**
   - Terdaftar secara hierarkis untuk CPT `motor` dengan slug `kategori-motor`.
   - Dukungan Block Editor Gutenberg (`show_in_rest => true`).
   - Proteksi kapabilitas: `manage_ryokourent_settings` untuk manipulasi term dan `edit_posts` untuk penugasan term.
2. **Seeding Kategori Default Idempoten:**
   - Term: `beat-series` (Honda BeAT Series), `scoopy-vario` (Honda Scoopy & Vario), dan `trail-adventure` (Trail Adventure (Bromo)).
   - Helper `ryokourent_get_motor_categories()` untuk kemudahan querying term di admin maupun frontend.

---

## 3. Komponen yang Telah Diimplementasikan pada TASK-005

1. **Metabox Spesifikasi & Karakter Armada (`ryokourent_motor_specs_metabox`):**
   - Field Kapasitas Mesin (`_ryokou_engine_cc`), Transmisi (`_ryokou_transmission`), Karakter Rute (`_ryokou_route_character`).
   - Checkbox Bromo Ready (`_ryokou_is_bromo_ready`) dengan pesan peringatan keras bahwa rute Bromo hanya untuk unit Trail CRF 150L.
   - Badge Status Publik (`_ryokou_status_label`: Tersedia, Booking Menipis, Penuh).
2. **Metabox Tarif Sewa Armada (`ryokourent_motor_pricing_metabox`):**
   - Field Tarif Harian 24 Jam (`_ryokou_price_daily`), Mingguan 7 Hari (`_ryokou_price_weekly`), Bulanan 30 Hari (`_ryokou_price_monthly`).
   - Proteksi hak akses: Hanya Administrator (`manage_ryokourent_settings` / `manage_options`) yang dapat mengedit tarif; untuk Operator field terkunci otomatis (*disabled*).
3. **Metabox Inventaris Unit Fisik & Plat Nomor (`ryokourent_motor_stock_metabox`):**
   - Total Unit Fisik (`_ryokou_physical_stock`) dan Daftar Plat Nomor Kendaraan (`_ryokou_plate_numbers`).
   - Tertutup dari REST API publik (`show_in_rest => false`), sanitasi pembersihan plat nomor huruf kapital per baris.
   - Proteksi hak akses Administrator (`manage_ryokourent_settings`).
4. **Keamanan & Guard Penyimpanan (`ryokourent_save_motor_meta_data`):**
   - Guard `DOING_AUTOSAVE`.
   - Verifikasi nonce `wp_verify_nonce($_POST['ryokourent_motor_meta_nonce'], 'ryokourent_save_motor_meta_action')`.
   - Validasi `post_type === 'motor'` dan `current_user_can('edit_post', $post_id)`.
   - Validasi hak akses khusus untuk field sensitif (harga & kuota fisik).

---

## 4. Komponen yang Telah Diimplementasikan pada TASK-004

1. **Custom Post Type `motor` (`includes/post-types.php`):**
   - Registrasi CPT `motor` dengan label bahasa Indonesia lengkap.
   - Dukungan fitur: `title`, `editor`, `thumbnail`, `excerpt`, `custom-fields`.
   - Konfigurasi `public => true`, `has_archive => 'motor'`, `show_in_rest => true` (Block Editor Gutenberg), menu icon `dashicons-car`, posisi menu 25.
   - Kustomisasi pesan pembaruan (`post_updated_messages`).

2. **Skema & Sanitasi Meta Fields (`includes/meta-fields.php`):**
   - Pendaftaran 10 meta fields resmi sesuai `DATA_MODEL.md` via `register_post_meta()`:
     - `_ryokou_engine_cc` (integer, sanitasi angka kapasitas)
     - `_ryokou_transmission` (whitelist: Otomatis CVT / Manual Kopling)
     - `_ryokou_route_character` (sanitasi string deskripsi)
     - `_ryokou_is_bromo_ready` (boolean 0/1 untuk proteksi armada Bromo)
     - `_ryokou_price_daily`, `_ryokou_price_weekly`, `_ryokou_price_monthly` (integer IDR)
     - `_ryokou_physical_stock` (kuota unit fisik internal)
     - `_ryokou_plate_numbers` (daftar plat multi-baris dinormalisasi)
     - `_ryokou_status_label` (whitelist: Tersedia / Booking Menipis / Penuh)
   - Capability checks pada callback autentikasi (`current_user_can('edit_post', $post_id)`).
   - Helper fungsi pembaca data: `ryokourent_get_motor_meta()` dan `ryokourent_is_motor_bromo_ready()`.

3. **Kustomisasi Kolom Admin List Table (`admin/motor-columns.php`):**
   - Penambahan kolom: Foto Thumbnail, Model Motor, Spesifikasi Mesin, Tarif Harian, Unit Fisik, Rute Bromo, Status Publik, dan Tanggal.
   - Escaping output ketat (`esc_html`, `esc_attr`, `esc_url`) pada semua data kolom.
   - Pengecekan capability `current_user_can('edit_posts')`.
   - Sortable columns untuk Tarif Harian dan Jumlah Unit Fisik dengan penanganan query `pre_get_posts`.

---

## 4. Batasan & Aturan Keamanan Terjaga
* **Prefix:** Seluruh fungsi dan hook menggunakan prefix `ryokourent_`.
* **Sanitasi & Escaping:** Setiap input disanitasi sebelum disimpan; setiap output diescape.
* **Audit REVIEW-ARCHITECTURE.md (Lolos 100%):**
  - **K1 & M9:** Role `ryokourent_operator` dan hak akses `manage_ryokourent_bookings` + `manage_ryokourent_settings` diatur aktif pada aktivasi dan `admin_init`.
  - **K2 & ADR-008:** Pengecekan 2 titik dan mekanisme atomic lock didokumentasikan di `DATA_MODEL.md` dan `DECISIONS.md`.
  - **K3:** Penanganan status kustom `public => false` dengan panjang slug $\le 20$ karakter disematkan ke metabox dan filter `wp_insert_post_data`.
  - **K4:** Meta fields internal `_ryokou_physical_stock` dan `_ryokou_plate_numbers` diproteksi `show_in_rest => false` dan respon AJAX ketersediaan hanya boolean.
  - **K5:** Kalkulasi durasi (24 jam + 2 jam grace period), operasional 07:00-23:00 WIB, dan penetapan harga dikunci mutlak di server backend.
  - **K6:** Snippet `functions.php` pada `BLUEPRINT.md` §7A ditandai *superseded* dan disatukan ke plugin `ryokourent-core`.
  - **M1 s/d M15:** Penanganan cache nonce, honeypot anti-spam, format `https://wa.me/`, penyesuaian bulk price, guard autosave, dan sinkronisasi struktur folder arsitektur telah selesai diperbarui tanpa ada yang terlewat.
* **File Terlindungi (Tidak Disentuh):**
  - `booking.php` (TIDAK DIUBAH)
  - `pricing.php` (TIDAK DIUBAH)
  - `availability.php` (TIDAK DIUBAH)
  - `whatsapp.php` (TIDAK DIUBAH)
  - `dashboard.php` (TIDAK DIUBAH)
* **Kompilasi & Build:** Build applet berhasil tanpa error.
