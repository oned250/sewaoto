# SESSION STATE: RYOKOURENT

Dokumen ini melacak status pengerjaan sesi, task aktif, dependensi yang telah terpenuhi, dan rencana task selanjutnya.

---

## 1. Status Sesi Saat Ini
* **Tanggal / Waktu:** 2026-09-30
* **Cabang Git Aktif:** `develop`
* **Task Terakhir Selesai:** `TASK-004: Buat Custom Post Type motor`
* **Status Task Terakhir:** **DONE (SELESAI)**
* **Task Selanjutnya:** `TASK-005: Buat Field Data Motor (Metabox Spesifikasi & Kuota)`

---

## 2. Riwayat Progress Task

| ID Task | Nama Task | Fase | Status | Dependensi | Tanggal Selesai |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TASK-001** | Analisis Blueprint & Dokumentasi | FASE 0 | **DONE** | - | 2026-09-30 |
| **TASK-002** | Struktur Repository & Kerangka Proyek | FASE 1 | **DONE** | TASK-001 | 2026-09-30 |
| **TASK-003** | Plugin Loader & Helper Dasar | FASE 2 | **DONE** | TASK-002 | 2026-09-30 |
| **TASK-004** | Custom Post Type `motor` | FASE 2 | **DONE** | TASK-003 | 2026-09-30 |
| **TASK-005** | Metabox Spesifikasi & Kuota Motor | FASE 2 | **PENDING** | TASK-004 | - |
| **TASK-006** | Taxonomy Kategori Motor | FASE 2 | **PENDING** | TASK-004 | - |

---

## 3. Komponen yang Telah Diimplementasikan pada TASK-004

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
* **File Terlindungi (Tidak Disentuh):**
  - `booking.php` (TIDAK DIUBAH)
  - `pricing.php` (TIDAK DIUBAH)
  - `availability.php` (TIDAK DIUBAH)
  - `whatsapp.php` (TIDAK DIUBAH)
  - `dashboard.php` (TIDAK DIUBAH)
* **Kompilasi & Build:** Build applet berhasil tanpa error.
