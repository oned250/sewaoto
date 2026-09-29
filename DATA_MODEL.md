# DATA MODEL: RYOKOURENT

Dokumen ini mendefinisikan skema data untuk sistem persewaan sepeda motor Ryokourent, mencakup Custom Post Types, Taxonomies, Post Meta Fields, Status Post, dan relasi antar entitas data.

---

## 1. Custom Post Types (CPT)

### A. CPT `motor` (Armada Sepeda Motor)
* **Post Type Slug:** `motor`
* **Label:** Armada Motor
* **Singular Label:** Motor
* **Public:** `true` (memiliki halaman arsip katalog dan single post)
* **Supports:** `title`, `editor`, `thumbnail`, `custom-fields`
* **Menu Icon:** `dashicons-car`
* **Rewrite Slug:** `motor`
* **Tujuan:** Menyimpan katalog tipe model motor yang disewakan kepada publik beserta spesifikasi mesin, panduan rute, tarif, dan kuota unit fisik internal.

### B. CPT `penyewaan` (Data Pemesanan & Sewa)
* **Post Type Slug:** `penyewaan`
* **Label:** Penyewaan Motor
* **Singular Label:** Penyewaan
* **Public:** `false` (hanya dapat diakses melalui WP-Admin)
* **Show UI:** `true`
* **Show in Menu:** `true`
* **Supports:** `title`, `custom-fields`
* **Menu Icon:** `dashicons-calendar-alt`
* **Capability Type:** `post` (dengan pemetaan custom capability `ryokourent_booking`)
* **Tujuan:** Menyimpan rekam jejak pesanan sewa pelanggan yang masuk dari formulir website sebelum diteruskan ke WhatsApp, riwayat status, dan detail sewa.

---

## 2. Custom Taxonomy: `kategori_motor`
* **Taxonomy Slug:** `kategori_motor`
* **Attached CPT:** `motor`
* **Hierarchical:** `true` (mirip kategori post)
* **Show in REST:** `true`
* **Terms Awal:**
  1. `beat-series` (Honda BeAT Series - BeAT Deluxe, CBS, Street)
  2. `scoopy-vario` (Honda Scoopy & Vario - Scoopy, Vario 125, Vario 160)
  3. `trail-adventure` (Honda Trail CRF 150L - Wajib Bromo)

---

## 3. Post Meta Fields (Skema Data & Tipe Data)

### A. Meta Fields untuk CPT `motor`
Prefix meta key: `_ryokou_`

| Meta Key | Label Field | Tipe Data | Keterangan & Aturan |
| :--- | :--- | :--- | :--- |
| `_ryokou_engine_cc` | Kapasitas Mesin | `text` / `integer` | Contoh: `110`, `125`, `150`, `160` (cc) |
| `_ryokou_transmission` | Transmisi | `select` | Pilihan: `Otomatis (CVT)`, `Manual (Kopling)` |
| `_ryokou_route_character` | Karakter Rute | `text` | Contoh: `Lincah & Sangat Irit`, `Adventure (Wajib Bromo)` |
| `_ryokou_is_bromo_ready` | Khusus Bromo | `boolean` (0/1) | `1` jika diizinkan untuk trip Bromo (hanya CRF 150L) |
| `_ryokou_price_daily` | Tarif Harian (24 Jam) | `integer` | Angka integer dalam Rupiah (misal: `85000`) |
| `_ryokou_price_weekly` | Tarif Mingguan (7 Hari)| `integer` | Angka integer dalam Rupiah (misal: `500000`) |
| `_ryokou_price_monthly` | Tarif Bulanan (30 Hari)| `integer` | Angka integer dalam Rupiah (misal: `1600000`) |
| `_ryokou_physical_stock`| Jumlah Unit Fisik | `integer` | **INTERNAL OPERATOR**: Total motor fisik yang dimiliki |
| `_ryokou_plate_numbers` | Daftar Plat Nomor | `textarea` / `array`| **INTERNAL OPERATOR**: Plat nomor unit (dipisah baris baru) |
| `_ryokou_status_label` | Badge Status Publik | `select` | Pilihan: `Tersedia`, `Booking Menipis`, `Penuh` |

### B. Meta Fields untuk CPT `penyewaan`
Prefix meta key: `_ryokou_booking_`

| Meta Key | Label Field | Tipe Data | Keterangan & Sanitasi |
| :--- | :--- | :--- | :--- |
| `_ryokou_booking_code` | Kode Booking | `string` | Format unik: `RYK-YYYYMMDD-XXXX` |
| `_ryokou_booking_name` | Nama Penyewa | `string` | Nama lengkap sesuai e-KTP |
| `_ryokou_booking_ktp_address` | Alamat Asal KTP | `text` | Alamat domisili asal pelanggan |
| `_ryokou_booking_stay_address` | Lokasi Menginap | `string` | Nama Hotel/Villa/Kost di Malang atau Batu |
| `_ryokou_booking_whatsapp` | Nomor WhatsApp | `string` | Nomor HP aktif (format `08...` atau `62...`) |
| `_ryokou_booking_emergency` | Kontak Darurat | `string` | Nomor keluarga/kerabat penjamin |
| `_ryokou_booking_social_media`| Akun Media Sosial | `string` | Username Instagram / Facebook penyewa |
| `_ryokou_booking_motor_id` | ID Motor Disewa | `integer` | Relasi ID Post ke CPT `motor` |
| `_ryokou_booking_pickup_loc` | Lokasi Ambil/Antar | `select` | `pool_dinoyo`, `pool_batu`, `stasiun_malang`, `antar_lokasi` |
| `_ryokou_booking_start_datetime`| Waktu Mulai Sewa | `datetime` (Y-m-d H:i) | Jam antara 07:00 – 23:00 WIB |
| `_ryokou_booking_end_datetime` | Waktu Selesai Sewa| `datetime` (Y-m-d H:i) | Jam antara 07:00 – 23:00 WIB |
| `_ryokou_booking_total_days` | Durasi Sewa | `integer` / `float` | Dihitung otomatis (Hari & Jam pembulatan) |
| `_ryokou_booking_total_price` | Estimasi Total Biaya| `integer` | Nilai Rupiah kalkulasi tarif |
| `_ryokou_booking_allocated_plate`| Plat Nomor Unit | `string` | Diinput oleh operator saat konfirmasi |
| `_ryokou_booking_notes` | Catatan Tambahan | `text` | Ukuran helm (L/M), jas hujan, rute trip |

---

## 4. Skema Status Booking (Custom Post Status)

Semua status didaftarkan menggunakan fungsi WordPress `register_post_status()`:

| Slug Status | Label UI | Warna Badge | Keterangan Alur |
| :--- | :--- | :--- | :--- |
| `status_menunggu` | Menunggu Konfirmasi | Kuning/Amber (`#f59e0b`) | Pesanan baru disubmit dari website, draf WA sedang dikirim ke admin. |
| `status_dikonfirmasi` | Dikonfirmasi | Biru (`#0284c7`) | Admin telah verifikasi identitas, DP diterima, slot unit dan tanggal terkunci. |
| `status_berjalan` | Sewa Berjalan | Hijau (`#10b981`) | Hari H: Motor telah diserahterimakan kepada penyewa. |
| `status_selesai` | Selesai | Abu-abu (`#64748b`) | Motor dikembalikan dalam kondisi baik, deposit dikembalikan, sewa ditutup. |
| `status_dibatalkan` | Dibatalkan | Merah (`#ef4444`) | Pesanan batal oleh pelanggan atau ditolak admin (kuota kembali pulih). |

---

## 5. Relasi Data & Algoritma Ketersediaan Unit

### A. Diagram Relasi Entitas (ERD Sederhana)
```
[kategori_motor] 1 ──── N [CPT: motor] 1 ──── N [CPT: penyewaan]
 (Taxonomy)                 (Model Unit)            (Transaksi Booking)
                             - Stock Fisik (N)        - Status Booking
                             - Plat Nomor (List)      - Start DateTime
                                                      - End DateTime
```

### B. Logika Pengecekan Ketersediaan & Pencegahan Double Booking
Saat pengguna memilih model motor $M$ dengan rentang sewa $[T_{mulai}, T_{selesai}]$:
1. Ambil data total unit fisik model $M$:
   $$\text{TotalStok} = \text{get\_post\_meta}(M, \text{'_ryokou\_physical\_stock'}, \text{true})$$
2. Query seluruh post pada CPT `penyewaan` dengan kondisi:
   * `_ryokou_booking_motor_id` = $M$
   * `post_status` IN (`status_dikonfirmasi`, `status_berjalan`)
   * Rentang waktu bertabrakan (*overlapping*):
     $$\text{StartBooking} < T_{selesai} \quad \text{DAN} \quad \text{EndBooking} > T_{mulai}$$
3. Hitung jumlah pemesanan aktif yang bentrok ($\text{ActiveBookings}$).
4. Sisa Unit Tersedia:
   $$\text{SisaUnit} = \text{TotalStok} - \text{ActiveBookings}$$
5. Jika $\text{SisaUnit} \le 0$:
   * Formulir menolak booking untuk tanggal tersebut.
   * Muncul notifikasi ramah: *"Armada ini telah terpesan penuh pada tanggal tersebut. Silakan pilih armada lain atau sesuaikan jadwal Anda."*
