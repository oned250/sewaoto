# DAFTAR TASK IMPLEMENTASI (TASKS.md)

Dokumen ini berisi rincian urutan 30 task proyek Ryokourent sesuai dengan arsitektur FASE 0 hingga FASE 4.

---

### TASK-001: Analisis Blueprint dan Dokumentasi Proyek
* **Tujuan:** Memahami seluruh kebutuhan, model data, alur bisnis, aturan ketat (Bromo CRF), dan menghasilkan dokumen arsitektur awal.
* **File yang Dibuat/Diubah:** `BLUEPRINT.md`, `PROJECT_OVERVIEW.md`, `ARCHITECTURE.md`, `DATA_MODEL.md`, `TASKS.md`, `AI_WORKFLOW.md`, `DECISIONS.md`, `TESTING.md`, `.env.example`.
* **Dependensi:** Tidak ada.
* **Kriteria Selesai:** Seluruh dokumen perencanaan (FASE 0) selesai dibuat, valid, dan disetujui.
* **Cara Pengujian:** Review dokumen checklist perencanaan dan verifikasi kelengkapan FASE 0.
* **Risiko:** Perubahan spek bisnis di tengah jalan jika ada asumsi yang tidak disetujui stakeholder.

---

### TASK-002: Buat Struktur Repository dan Plugin Kosong
* **Tujuan:** Menyiapkan struktur folder standar plugin `ryokourent-core` dan child theme `generatepress-child`, file `.gitignore`, `.editorconfig`, `.phpcs.xml.dist`.
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/ryokourent-core.php`
  * `wp-content/plugins/ryokourent-core/readme.txt`
  * `wp-content/themes/generatepress-child/style.css`
  * `wp-content/themes/generatepress-child/functions.php`
  * `.gitignore`, `.editorconfig`, `.phpcs.xml.dist`
* **Dependensi:** TASK-001 disetujui.
* **Kriteria Selesai:** Plugin dan theme terdeteksi di WordPress tanpa menimbulkan error saat diaktifkan.
* **Cara Pengujian:** Aktifkan theme dan plugin di WP-Admin; pastikan tidak ada PHP Fatal Error atau Warning.
* **Risiko:** Konflik path direktori jika struktur tidak konsisten.

---

### TASK-003: Buat Plugin Loader dan Helper Dasar
* **Tujuan:** Membangun bootstrap loader utama pada plugin, konstanta plugin, sanitasi helper, formatting mata uang Rupiah, dan helper waktu zona WIB.
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/ryokourent-core.php`
  * `wp-content/plugins/ryokourent-core/includes/helpers.php`
* **Dependensi:** TASK-002.
* **Kriteria Selesai:** Fungsi helper `ryokourent_format_rupiah()`, `ryokourent_sanitize_phone()`, `ryokourent_get_now_wib()` dapat dipanggil dan lolos testing fungsi dasar.
* **Cara Pengujian:** Panggil helper dengan berbagai input data; pastikan formatting dan sanitasi bekerja sesuai aturan.
* **Risiko:** Masalah timezone server non-WIB (UTC).

---

### TASK-004: Buat Custom Post Type `motor`
* **Tujuan:** Mendaftarkan CPT `motor` untuk mengelola data katalog armada dengan dukungan judul, editor deskripsi, gambar thumbnail, dan custom fields.
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/includes/post-types.php`
* **Dependensi:** TASK-003.
* **Kriteria Selesai:** Menu "Armada Motor" muncul di sidebar WP-Admin dengan ikon mobil/motor (`dashicons-car`).
* **Cara Pengujian:** Buka WP-Admin, klik "Armada Motor" -> "Tambah Motor Baru", buat post motor contoh dan simpan.
* **Risiko:** Konflik slug rewrite permalink.

---

### TASK-005: Buat Field Data Motor (Metabox Spesifikasi & Kuota)
* **Tujuan:** Menambahkan meta box kustom untuk menyimpan kapasitas mesin (cc), transmisi, karakter rute, penanda khusus Bromo, tarif harian/mingguan/bulanan, dan kuota unit fisik serta daftar plat nomor.
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/includes/meta-boxes.php`
* **Dependensi:** TASK-004.
* **Kriteria Selesai:** Meta box muncul rapi di halaman edit CPT `motor`, data tersimpan dengan aman dengan verifikasi nonce dan sanitasi.
* **Cara Pengujian:** Isi semua field pada form edit motor, simpan post, muat ulang halaman, pastikan data tersimpan persisten.
* **Risiko:** Kesalahan sanitasi field array plat nomor.

---

### TASK-006: Buat Taxonomy Kategori Motor
* **Tujuan:** Mendaftarkan taxonomy hierarkis `kategori_motor` (BeAT Series, Scoopy & Vario, Trail Adventure).
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/includes/taxonomies.php`
* **Dependensi:** TASK-004.
* **Kriteria Selesai:** Kategori Motor dapat dikelola dari submenu CPT `motor` dan dikaitkan ke masing-masing unit armada.
* **Cara Pengujian:** Buat 3 term kategori utama dan tetapkan ke data motor contoh.
* **Risiko:** Duplikasi nama kategori atau slug URL bertabrakan.

---

### TASK-007: Buat Tampilan Katalog Motor
* **Tujuan:** Membuat shortcode `[ryokou_catalog]` dan template grid katalog motor mobile-first dengan filter tab kategori, spesifikasi, dan tombol CTA "Sewa Sekarang" serta "Chat WA".
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/public/shortcodes.php`
  * `wp-content/plugins/ryokourent-core/public/templates.php`
  * `wp-content/plugins/ryokourent-core/assets/css/ryokourent-public.css`
  * `wp-content/plugins/ryokourent-core/assets/js/ryokourent-filter.js`
* **Dependensi:** TASK-005, TASK-006.
* **Kriteria Selesai:** Katalog menampilkan 7 armada sesuai blueprint dengan tab filter responsif tanpa reload halaman.
* **Cara Pengujian:** Pasang shortcode pada halaman, uji klik filter tab pada resolusi HP dan desktop.
* **Risiko:** Gambar motor lambat dimuat jika ukuran tidak dioptimasi.

---

### TASK-008: Buat Halaman Detail Motor
* **Tujuan:** Membuat template single post (`single-motor.php`) pada child theme yang menampilkan detail mendalam motor, keunggulan rute, peringatan rute Bromo, kelengkapan helm/jas hujan, dan form booking cepat.
* **File yang Dibuat/Diubah:**
  * `wp-content/themes/generatepress-child/templates/single-motor.php`
* **Dependensi:** TASK-007.
* **Kriteria Selesai:** Akses URL `/motor/honda-beat-deluxe/` menampilkan layout elegan dengan informasi spesifikasi lengkap dan link ke form booking.
* **Cara Pengujian:** Buka single post motor di browser, verifikasi peringatan khusus Bromo pada unit matik vs Trail CRF.
* **Risiko:** Override template hierarchy GeneratePress tidak terbaca.

---

### TASK-009: Buat Custom Post Type `penyewaan`
* **Tujuan:** Mendaftarkan CPT `penyewaan` (internal admin) untuk menampung riwayat pesanan booking dari website.
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/includes/post-types.php`
  * `wp-content/plugins/ryokourent-core/includes/meta-boxes.php`
* **Dependensi:** TASK-004.
* **Kriteria Selesai:** Menu "Penyewaan Motor" muncul di sidebar dengan ikon kalender, private untuk non-admin/operator.
* **Cara Pengujian:** Masuk ke menu Penyewaan, pastikan interface admin siap menampilkan daftar pesanan.
* **Risiko:** Data pelanggan terekspos ke feed RSS atau REST API publik jika parameter `public` salah diset.

---

### TASK-010: Buat Status Booking Kustom
* **Tujuan:** Mendaftarkan post status kustom: `status_menunggu`, `status_dikonfirmasi`, `status_berjalan`, `status_selesai`, `status_dibatalkan`.
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/includes/post-types.php`
* **Dependensi:** TASK-009.
* **Kriteria Selesai:** Dropdown status pada CPT `penyewaan` memuat seluruh status kustom dengan label warna yang jelas.
* **Cara Pengujian:** Simpan satu data booking dengan masing-masing status dan cek filter status di tabel admin.
* **Risiko:** Status kustom tidak muncul pada filter tabel default WordPress.

---

### TASK-011: Buat Form Booking Dasar
* **Tujuan:** Membangun formulir booking HTML5 yang bersih dan terstruktur mencakup 10 field sesuai blueprint dan tombol WhatsApp.
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/public/forms.php`
  * `wp-content/plugins/ryokourent-core/public/shortcodes.php`
* **Dependensi:** TASK-005.
* **Kriteria Selesai:** Shortcode `[ryokou_booking_form]` merender formulir booking lengkap dan responsif di smartphone.
* **Cara Pengujian:** Buka halaman booking di mobile viewport, periksa ketersediaan seluruh input field.
* **Risiko:** Input form terlalu panjang untuk pengguna smartphone jika tidak ditata rapi.

---

### TASK-012: Buat Validasi Data Pelanggan
* **Tujuan:** Memvalidasi nama pelanggan, nomor WhatsApp (format Indonesia), nomor kontak darurat keluarga, dan alamat menginap baik di sisi client (JS) maupun sisi server (PHP).
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/includes/booking.php`
  * `wp-content/plugins/ryokourent-core/assets/js/ryokourent-booking.js`
* **Dependensi:** TASK-011.
* **Kriteria Selesai:** Form menolak nomor HP tidak valid (kurang dari 10 digit atau bukan format angka) dan wajib menyertakan kontak darurat yang berbeda dari kontak utama.
* **Cara Pengujian:** Kirim form dengan data dummy salah; pastikan muncul pesan error spesifik dan tidak dapat disubmit.
* **Risiko:** Validasi nomor HP terlalu ketat hingga menolak nomor dengan spasi atau tanda hubung.

---

### TASK-013: Buat Kalkulasi Durasi Sewa
* **Tujuan:** Menghitung selisih waktu sewa secara real-time berdasarkan tanggal & jam mulai serta tanggal & jam selesai.
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/assets/js/ryokourent-booking.js`
  * `wp-content/plugins/ryokourent-core/includes/booking.php`
* **Dependensi:** TASK-011.
* **Kriteria Selesai:** UI menampilkan indikator "Durasi: X Hari (Y Jam)" secara instan saat pengguna mengubah tanggal/jam.
* **Cara Pengujian:** Set waktu mulai 02/10/2026 08:30 dan selesai 04/10/2026 17:00, verifikasi kalkulasi menghasilkan 3 Hari (~57 Jam).
* **Risiko:** Kesalahan perhitungan karena perbedaan zona waktu browser penyewa.

---

### TASK-014: Buat Kalkulasi Harga Harian, Mingguan, dan Bulanan
* **Tujuan:** Membangun modul `pricing.php` untuk menghitung tarif sewa otomatis berdasarkan durasi total (paket harian 24 jam, mingguan 7 hari, bulanan 30 hari).
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/includes/pricing.php`
* **Dependensi:** TASK-013.
* **Kriteria Selesai:** Estimasi total tarif muncul di preview form sebelum pengguna mengirim booking.
* **Cara Pengujian:** Jalankan unit test kalkulasi untuk sewa 1 hari, 3 hari, 7 hari, dan 35 hari.
* **Risiko:** Perhitungan pembulatan jam overtime.

---

### TASK-015: Buat Validasi Tanggal dan Jam (Operational Hours)
* **Tujuan:** Membatasi pilihan jam sewa hanya pada jam operasional pool (07.00 – 23.00 WIB) dan mencegah pemilihan tanggal selesai sebelum tanggal mulai.
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/includes/booking.php`
  * `wp-content/plugins/ryokourent-core/assets/js/ryokourent-booking.js`
* **Dependensi:** TASK-013.
* **Kriteria Selesai:** Input jam di luar 07.00 - 23.00 WIB ditolak dengan pemberitahuan jam operasional resmi.
* **Cara Pengujian:** Pilih jam mulai 02:00 WIB atau tanggal selesai masa lalu; pastikan sistem memblokir submit.
* **Risiko:** Inkonsistensi format 12 jam vs 24 jam di browser mobile.

---

### TASK-016: Buat Validasi Ketersediaan Unit
* **Tujuan:** Membangun mesin kueri `availability.php` untuk memeriksa sisa kuota unit fisik model motor pada rentang tanggal yang diminta.
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/includes/availability.php`
* **Dependensi:** TASK-005, TASK-010.
* **Kriteria Selesai:** Fungsi `ryokourent_check_availability($motor_id, $start, $end)` mengembalikan status `true`/`false` dan sisa kuota internal.
* **Cara Pengujian:** Simulasikan 3 booking aktif pada motor yang memiliki stok 3; pastikan pengecekan berikutnya menghasilkan status penuh.
* **Risiko:** Query lambat jika jumlah data booking besar (memerlukan index meta_query yang efisien).

---

### TASK-017: Buat Pencegahan Double Booking
* **Tujuan:** Menerapkan penguncian logika saat submit pesanan agar tidak terjadi dua booking yang memotong kuota yang sama pada detik bersamaan.
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/includes/availability.php`
  * `wp-content/plugins/ryokourent-core/includes/booking.php`
* **Dependensi:** TASK-016.
* **Kriteria Selesai:** Sistem memblokir pesanan jika saat validasi akhir kuota sudah habis terisi pesanan terkonfirmasi lain.
* **Cara Pengujian:** Tes dua request bersamaan pada unit dengan sisa kuota 1; pastikan hanya satu yang lolos.
* **Risiko:** Race condition database pada server dengan traffic tinggi.

---

### TASK-018: Buat Generator Pesan WhatsApp
* **Tujuan:** Menyusun draf pesan WhatsApp resmi yang rapi, ber-emotikon terstruktur sesuai blueprint, dan menghasilkan URL `https://api.whatsapp.com/send?phone=...&text=...`.
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/includes/whatsapp.php`
  * `wp-content/plugins/ryokourent-core/assets/js/ryokourent-booking.js`
* **Dependensi:** TASK-011, TASK-014.
* **Kriteria Selesai:** Live preview pesan WhatsApp di form terisi dinamis dan tombol mengarahkan ke WhatsApp dengan pesan siap kirim.
* **Cara Pengujian:** Isi formulir secara lengkap, klik tombol, cek teks yang muncul di aplikasi WhatsApp Web/Mobile.
* **Risiko:** Karakter khusus atau baris baru rusak saat di-URL-encode di perangkat tertentu.

---

### TASK-019: Buat Penyimpanan Booking (AJAX & Nonce Handler)
* **Tujuan:** Menyimpan data formulir ke CPT `penyewaan` dengan status `status_menunggu` dan kode unik `RYK-...` secara asynchronous saat pengguna mengklik kirim ke WhatsApp.
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/includes/booking.php`
* **Dependensi:** TASK-017, TASK-018.
* **Kriteria Selesai:** Data booking langsung masuk ke WP-Admin sebelum jendela WhatsApp terbuka, dengan respons JSON status sukses.
* **Cara Pengujian:** Submit booking dari frontend, periksa daftar post pada CPT `penyewaan` di backend.
* **Risiko:** Pop-up blocker browser menghalangi pembukaan tab WhatsApp setelah AJAX selesai.

---

### TASK-020: Buat Role Operator
* **Tujuan:** Mendaftarkan peran user WordPress baru `ryokou_operator` dengan hak akses terbatas pada menu operasional harian rental motor.
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/includes/user-roles.php`
* **Dependensi:** TASK-009.
* **Kriteria Selesai:** Role `Ryokou Operator` dapat dipilih saat membuat user baru di WP-Admin.
* **Cara Pengujian:** Buat user dengan role operator dan uji login.
* **Risiko:** Role tidak terhapus bersih saat deactivasi jika tidak di-handle dengan rapi.

---

### TASK-021: Buat Capability dan Pembatasan Akses
* **Tujuan:** Mengonfigurasi capabilities (`manage_ryokourent_bookings` vs `manage_ryokourent_settings`) agar operator dilarang mengakses halaman pengaturan tarif, kuota armada, dan manajemen user.
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/includes/user-roles.php`
  * `wp-content/plugins/ryokourent-core/admin/admin-settings.php`
* **Dependensi:** TASK-020.
* **Kriteria Selesai:** Operator hanya dapat melihat dan mengedit data penyewaan; menu plugin settings dan tema terkunci (403 forbidden).
* **Cara Pengujian:** Login sebagai operator, coba akses URL langsung halaman pengaturan; pastikan ditolak.
* **Risiko:** Eskalasi privilege jika capability tidak dicek secara server-side.

---

### TASK-022: Buat Dashboard Booking & Operasional Armada
* **Tujuan:** Membuat halaman ringkasan operasional di WP-Admin yang menampilkan metrik: Unit Disewa Hari Ini, Booking Menunggu Konfirmasi, Unit Siap di Pool Dinoyo, Unit Siap di Pool Batu.
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/admin/dashboard.php`
  * `wp-content/plugins/ryokourent-core/admin/booking-columns.php`
* **Dependensi:** TASK-010, TASK-019.
* **Kriteria Selesai:** Dashboard menampilkan kartu statistik real-time dan quick actions untuk konfirmasi pesanan.
* **Cara Pengujian:** Buka menu Dashboard Ryokou, verifikasi sinkronisasi angka dengan data CPT `penyewaan`.
* **Risiko:** Beban kueri jika tidak menggunakan transient caching untuk statistik dashboard.

---

### TASK-023: Buat Perubahan Status Booking (Quick Actions)
* **Tujuan:** Memfasilitasi alur kerja operator untuk mengubah status booking secara cepat (Menunggu -> Dikonfirmasi -> Berjalan -> Selesai -> Dibatalkan) serta mencatat plat nomor motor yang diserahkan.
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/admin/booking-columns.php`
  * `wp-content/plugins/ryokourent-core/includes/booking.php`
* **Dependensi:** TASK-022.
* **Kriteria Selesai:** Operator dapat mengubah status langsung dari tabel admin dan menginput plat nomor kendaraan.
* **Cara Pengujian:** Ubah status booking dari daftar tabel; pastikan badge warna dan kuota armada terbarui.
* **Risiko:** Operator lupa menginput plat nomor motor.

---

### TASK-024: Buat Pengaturan Harga dan Nomor WhatsApp (Admin Settings)
* **Tujuan:** Membuat antarmuka pengaturan untuk nomor WhatsApp admin resmi, teks default, jam operasional, dan fitur multi-update harga (bulk price adjustment nominal/persentase untuk peak season).
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/admin/admin-settings.php`
  * `wp-content/plugins/ryokourent-core/includes/settings.php`
* **Dependensi:** TASK-014, TASK-021.
* **Kriteria Selesai:** Admin dapat mengubah nomor tujuan WhatsApp dan menerapkan kenaikan harga bulk per kategori motor.
* **Cara Pengujian:** Naikkan harga kategori BeAT +10.000 melalui bulk update, periksa perubahan harga pada katalog.
* **Risiko:** Salah input formula persentase yang merusak data harga master.

---

### TASK-025: Buat Halaman FAQ dan Lokasi Pool
* **Tujuan:** Membuat komponen informasi 2 Pool resmi (Dinoyo Malang & Diponegoro Batu), jam operasional (07.00 - 23.00), aturan ketat Bromo (Trail CRF 150L wajib), dan FAQ accordion 7 poin sesuai blueprint.
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/public/templates.php`
  * `wp-content/themes/generatepress-child/templates/`
* **Dependensi:** TASK-007.
* **Kriteria Selesai:** Halaman menyajikan alamat pool lengkap dengan tautan Google Maps, syarat dokumen e-KTP, dan accordion FAQ interaktif.
* **Cara Pengujian:** Klik setiap item FAQ, uji tautan Google Maps Pool 1 dan Pool 2.
* **Risiko:** Tampilan accordion rusak pada perangkat layar kecil.

---

### TASK-026: Buat Responsive Design & Mobile-First Optimization
* **Tujuan:** Mengoptimalkan seluruh elemen UI (katalog, form booking, floating mobile bar < 15% viewport, navigasi) agar tampil sempurna di resolusi smartphone 360px - 430px.
* **File yang Dibuat/Diubah:**
  * `wp-content/themes/generatepress-child/style.css`
  * `wp-content/plugins/ryokourent-core/assets/css/ryokourent-public.css`
* **Dependensi:** TASK-011, TASK-025.
* **Kriteria Selesai:** Tidak ada horizontal overflow, tombol WhatsApp nyaman dijangkau satu tangan (thumb zone), skor Lighthouse Mobile > 90.
* **Cara Pengujian:** Jalankan audit Lighthouse di Chrome DevTools pada mode emulasi mobile.
* **Risiko:** Floating bar menutupi tombol penting pada form.

---

### TASK-027: Buat Validasi Keamanan (Security Hardening)
* **Tujuan:** Melakukan audit menyeluruh: sanitasi seluruh input (`sanitize_text_field`), escaping seluruh output (`esc_html`, `esc_attr`, `esc_url`), verifikasi nonce pada setiap request POST/AJAX, dan pencegahan eksekusi langsung file PHP.
* **File yang Dibuat/Diubah:** Seluruh file pada `wp-content/plugins/ryokourent-core/`.
* **Dependensi:** TASK-002 s/d TASK-026.
* **Kriteria Selesai:** Kode lolos uji PHP_CodeSniffer WordPress Coding Standards (WordPress-Core, WordPress-Security).
* **Cara Pengujian:** Jalankan `phpcs` pada direktori plugin dan uji penetrasi input payload XSS/SQL Injection pada form.
* **Risiko:** False positive rule sniffer atau missing sanitization pada field custom array.

---

### TASK-028: Buat Pengujian Manual dan Otomatis
* **Tujuan:** Menjalankan rangkaian unit test kalkulasi tarif, tes ketersediaan kuota, serta pengujian manual end-to-end dari pemilihan motor hingga pesan WhatsApp diterima.
* **File yang Dibuat/Diubah:**
  * `wp-content/plugins/ryokourent-core/tests/test-pricing.php`
  * `wp-content/plugins/ryokourent-core/tests/test-availability.php`
  * `TESTING.md`
* **Dependensi:** TASK-027.
* **Kriteria Selesai:** Seluruh 18 skenario uji pada `TESTING.md` berstatus PASSED.
* **Cara Pengujian:** Eksekusi script testing dan verifikasi manual di perangkat HP nyata.
* **Risiko:** Ketergantungan environment PHP CLI lokal.

---

### TASK-029: Buat Dokumentasi Admin & SOP Operator
* **Tujuan:** Menyusun buku panduan operasional (SOP) untuk admin dan operator: cara konfirmasi pesanan WA dalam < 5 menit, verifikasi e-KTP, pengalokasian plat motor, dan pengelolaan kuota hari libur.
* **File yang Dibuat/Diubah:**
  * `docs/OPERATOR_MANUAL.md`
  * `docs/ADMIN_GUIDE.md`
* **Dependensi:** TASK-023, TASK-024.
* **Kriteria Selesai:** Dokumen panduan tersedia dan mudah dipahami oleh staf non-teknis.
* **Cara Pengujian:** Uji keterbacaan panduan bersama calon operator.
* **Risiko:** SOP tidak dipatuhi operator jika terlalu rumit.

---

### TASK-030: Buat Panduan Deployment & Checklist Produksi
* **Tujuan:** Menyusun dokumentasi deployment lengkap ke server hosting (LiteSpeed / Nginx), konfigurasi SSL, cache rules, konfigurasi permalink, backup otomatis, dan prosedur rollback.
* **File yang Dibuat/Diubah:**
  * `docs/DEPLOYMENT_GUIDE.md`
  * `README.md`
* **Dependensi:** TASK-028, TASK-029.
* **Kriteria Selesai:** Checklist pra-produksi lengkap dan siap dieksekusi untuk go-live.
* **Cara Pengujian:** Lakukan simulasi dry-run deployment di staging server.
* **Risiko:** Perbedaan konfigurasi environment staging vs production.
