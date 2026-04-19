<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // 1. Koneksi ke Database menggunakan Query Builder
        $db = \Config\Database::connect();

        // 2. Mengambil ID User dan Role dari Session
        $id_user  = session()->get('id_user');
        $username = session()->get('username'); // Diambil untuk filter transaksi aktif
        $role     = session()->get('role'); // Diperlukan untuk filter notifikasi Admin

        /**
         * 3. LOGIKA NOTIFIKASI USER: Menghitung total nominal denda dalam Rupiah.
         * Hanya menghitung denda yang BELUM LUNAS (status_denda = 0).
         * Ini akan otomatis hilang dari dashboard user jika Admin sudah menandai lunas.
         */
        $total_denda = $db->table('transaksi')
            ->selectSum('denda')
            ->where('id_user', $id_user)
            ->where('status_denda', 0) // <--- HANYA YANG BELUM LUNAS
            ->get()->getRow()->denda;

        /**
         * 3.1 LOGIKA NOTIFIKASI ADMIN: Menghitung pesanan baru untuk card Dashboard.
         * Agar sinkron dengan lonceng, kita hanya menghitung yang 'is_read' = 0.
         * Ketika ikon ceklis diklik, maka 'is_read' jadi 1 dan angka di dashboard ini akan berkurang/hilang.
         */
        $pesanan_baru_count = 0;
        if ($role == 'admin' || $role == 'Admin') {
            $pesanan_baru_count = $db->table('transaksi')
                ->where('is_read', 0) // <--- PENTING: Hanya yang belum diklik ceklis (belum dibaca)
                ->countAllResults();
        }

        /**
         * 4. LOGIKA OTOMATIS: Mengambil rincian per kategori untuk dashboard.
         */
        $rincianKategori = $db->table('barang')
            ->select('kategori, COUNT(*) as total, MAX(foto_barang) as foto_barang')
            ->groupBy('kategori')
            ->get()
            ->getResultArray();

        /**
         * 5. LOGIKA GRAFIK PENDAPATAN:
         * Sekarang menyertakan Denda yang sudah LUNAS ke dalam grafik bulanan.
         */
        $dataPendapatan = $db->table('transaksi')
            ->select('MONTH(tgl_pinjam) as bulan, SUM(total_harga + IF(status_denda = 1, denda, 0)) as total')
            ->where('YEAR(tgl_pinjam)', date('Y'))
            ->where('status_transaksi', 'Selesai')
            ->groupBy('MONTH(tgl_pinjam)')
            ->get()->getResultArray();

        // Menyiapkan array 12 bulan (Jan-Des) dengan nilai awal 0
        $grafik = array_fill(0, 12, 0);

        // Memasukkan hasil query database ke dalam array grafik sesuai nomor bulannya
        foreach ($dataPendapatan as $row) {
            $grafik[$row['bulan'] - 1] = (int)$row['total'];
        }

        /**
         * 5.1 LOGIKA TOTAL PENDAPATAN KESELURUHAN:
         * Menghitung total_harga + denda (khusus yang status_denda = 1).
         */
        $resPendapatan = $db->table('transaksi')
            ->select('SUM(total_harga) as harga, SUM(IF(status_denda = 1, denda, 0)) as total_denda_lunas')
            ->where('status_transaksi', 'Selesai')
            ->get()->getRow();

        $totalSeluruhPendapatan = ($resPendapatan->harga ?? 0) + ($resPendapatan->total_denda_lunas ?? 0);

        // --- AWAL PENAMBAHAN LOGIKA VIEW TRACKER & TRANSAKSI AKTIF ---

        // Mengambil 4 barang dengan views terbanyak (Algorithm View Tracker)
        $rekomendasiBarang = $db->table('barang')
            ->orderBy('views', 'DESC')
            ->limit(4)
            ->get()->getResultArray();

        // Mengambil data transaksi yang sedang dipinjam oleh user ini
        $transaksiAktif = [];
        if ($role == 'user' || $role == 'User') {
            $transaksiAktif = $db->table('transaksi')
                ->select('transaksi.*, barang.nama_barang')
                ->join('barang', 'barang.id_barang = transaksi.id_barang')
                ->where('transaksi.id_user', $id_user)
                ->where('transaksi.status_transaksi', 'Dipinjam')
                ->get()->getResultArray();
        }

        // --- AKHIR PENAMBAHAN LOGIKA VIEW TRACKER & TRANSAKSI AKTIF ---

        /**
         * 6. Menyiapkan data untuk Dashboard.
         */
        $data = [
            'title'              => 'Dashboard Utama',
            'totalBarang'        => $db->table('barang')->countAll(), // Total stok barang
            'totalUser'          => $db->table('users')->countAll(),   // Total user terdaftar
            'rincianKategori'    => $rincianKategori,
            'total_denda'        => $total_denda ?? 0, // Nominal denda untuk alert user (hanya yg belum lunas)
            'pesanan_baru'       => $pesanan_baru_count, // Variabel baru untuk angka di card Dashboard Admin
            'pendapatan_bulanan' => json_encode($grafik), // Data untuk grafik pendapatan (termasuk denda lunas)
            'totalPendapatan'    => $totalSeluruhPendapatan, // Total uang masuk (Sewa + Denda Lunas)
            'rekomendasi_barang' => $rekomendasiBarang, // Data untuk algoritma view tracker
            'transaksi_aktif'    => $transaksiAktif,    // Data untuk card penyewaan user
        ];

        // Di dalam method index() atau dashboard()
        $modelBarang = new \App\Models\BarangModel();

        // Mengambil jumlah baris data barang
        $data['totalBarang'] = $modelBarang->countAll();

        // Mengambil TOTAL JUMLAH STOK
        $data['totalStok'] = $modelBarang->selectSum('stok')->get()->getRow()->stok;

        /**
         * 7. Mengirim data ke View Dashboard.
         */
        return view('layouts/dashboard', $data);
    }
}
