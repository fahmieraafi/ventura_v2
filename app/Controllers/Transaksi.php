<?php

namespace App\Controllers;

use App\Models\TransaksiModel;
use App\Models\BarangModel;

class Transaksi extends BaseController
{
    protected $transaksiModel;
    protected $barangModel;

    public function __construct()
    {
        // Inisialisasi model untuk digunakan di semua fungsi
        $this->transaksiModel = new TransaksiModel();
        $this->barangModel = new BarangModel();
    }

    /**
     * Menampilkan halaman riwayat pinjam untuk user + Fitur Hapus + Cari Tanggal
     */
    public function index()
    {
        $id_user = session()->get('id_user');
        if (!$id_user) return redirect()->to('/login');

        $keyword = $this->request->getVar('cari');
        $tgl_filter = $this->request->getVar('tgl'); // TAMBAHAN: Tangkap filter tanggal

        // Mengambil data transaksi milik user yang login
        $query = $this->transaksiModel->select('transaksi.*, barang.nama_barang')
            ->join('barang', 'barang.id_barang = transaksi.id_barang')
            ->where('id_user', $id_user);

        // TAMBAHAN: Filter berdasarkan tanggal jika user memilih tanggal tertentu
        if ($tgl_filter) {
            $query->where('transaksi.tgl_pinjam', $tgl_filter);
        }

        if ($keyword) {
            $query->groupStart()
                ->like('barang.nama_barang', $keyword)
                ->orLike('transaksi.id_transaksi', $keyword)
                ->groupEnd();
        }

        $data = [
            'title'     => 'Riwayat Pinjam - Ventura',
            'transaksi' => $query->orderBy('transaksi.created_at', 'DESC')->findAll(),
            'cari'      => $keyword,
            'tgl'       => $tgl_filter // TAMBAHAN: Kirim ke view agar input date tetap terisi
        ];

        return view('users/riwayat', $data);
    }

    /**
     * Fitur User: Menghapus data riwayat jika status sudah 'Selesai' atau 'Dibatalkan'
     */
    public function hapus_riwayat($id)
    {
        $transaksi = $this->transaksiModel->find($id);

        // Proteksi agar hanya status tertentu yang bisa dihapus user dari riwayatnya
        if ($transaksi && ($transaksi['status_transaksi'] == 'Selesai' || $transaksi['status_transaksi'] == 'Dibatalkan')) {
            $this->transaksiModel->delete($id);
            return redirect()->back()->with('success', 'Riwayat transaksi berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Gagal menghapus! Hanya status Selesai/Batal yang bisa dihapus.');
    }

    /**
     * Menampilkan halaman kelola transaksi untuk ADMIN + Fitur Cari Tanggal
     */
    public function kelola()
    {
        $keyword = $this->request->getVar('cari');
        $tgl_filter = $this->request->getVar('tgl'); // Ambil input filter tanggal

        $query = $this->transaksiModel->select('transaksi.*, barang.nama_barang, users.nama as nama_user, users.no_wa')
            ->join('barang', 'barang.id_barang = transaksi.id_barang')
            ->join('users', 'users.id_user = transaksi.id_user');

        // Filter berdasarkan tanggal jika admin memilih tanggal tertentu
        if ($tgl_filter) {
            $query->where('transaksi.tgl_pinjam', $tgl_filter);
        }

        if ($keyword) {
            $query->groupStart()
                ->like('barang.nama_barang', $keyword)
                ->orLike('users.nama', $keyword)
                ->orLike('transaksi.status_transaksi', $keyword)
                ->groupEnd();
        }

        $data = [
            'title'     => 'Kelola Transaksi - Admin',
            'transaksi' => $query->orderBy('transaksi.created_at', 'DESC')->findAll(),
            'cari'      => $keyword,
            'tgl'       => $tgl_filter
        ];

        return view('admin/transaksi/index', $data);
    }

    public function simpan()
    {
        $id_barang   = $this->request->getPost('id_barang');
        $tgl_pinjam  = $this->request->getPost('tgl_pinjam');
        $tgl_kembali = $this->request->getPost('tgl_kembali');

        $barang = $this->barangModel->find($id_barang);

        $start  = strtotime($tgl_pinjam);
        $end    = strtotime($tgl_kembali);
        $durasi = ($end - $start) / (60 * 60 * 24);
        if ($durasi <= 0) $durasi = 1;

        $total_harga = $durasi * $barang['harga_sewa'];

        // Proses upload bukti bayar
        $fileBukti = $this->request->getFile('bukti_bayar');
        if ($fileBukti && $fileBukti->isValid() && !$fileBukti->hasMoved()) {
            $namaFoto = $fileBukti->getRandomName();
            $fileBukti->move(ROOTPATH . 'uploads/bukti_bayar/', $namaFoto);
        } else {
            return redirect()->back()->with('error', 'Bukti pembayaran wajib diunggah.');
        }

        $this->transaksiModel->save([
            'id_user'           => session()->get('id_user'),
            'id_barang'         => $id_barang,
            'tgl_pinjam'        => $tgl_pinjam,
            'tgl_kembali'       => $tgl_kembali,
            'total_harga'       => $total_harga,
            'bukti_bayar'       => $namaFoto,
            'denda'             => 0,
            'status_denda'      => 0, // Inisialisasi denda belum lunas
            'status_transaksi'  => 'Waiting',
            'is_read'           => 0
        ]);

        return redirect()->to('/riwayat')->with('success', 'Booking berhasil! Tunggu konfirmasi admin.');
    }

    public function konfirmasi_bayar($id)
    {
        $transaksi = $this->transaksiModel->find($id);

        if ($transaksi) {
            $this->transaksiModel->update($id, ['status_transaksi'  => 'Booking']);

            // Kurangi stok barang sebanyak 1
            $this->barangModel->where('id_barang', $transaksi['id_barang'])
                ->set('stok', 'stok - 1', FALSE)
                ->update();

            return redirect()->back()->with('success', 'Pembayaran dikonfirmasi dan stok telah dikurangi.');
        }

        return redirect()->back()->with('error', 'Gagal konfirmasi! Data tidak ditemukan.');
    }

    public function updateStatus($id, $status)
    {
        $transaksi = $this->transaksiModel->find($id);

        if ($transaksi) {
            $this->transaksiModel->update($id, ['status_transaksi' => $status]);

            // Jika Selesai, stok bertambah kembali
            if ($status == 'Selesai') {
                $this->barangModel->where('id_barang', $transaksi['id_barang'])
                    ->set('stok', 'stok + 1', FALSE)
                    ->update();
            }

            return redirect()->back()->with('success', 'Status diperbarui dan stok telah dikembalikan.');
        }

        return redirect()->back()->with('error', 'Data transaksi tidak ditemukan.');
    }

    public function hitungDenda($id)
    {
        $transaksi = $this->transaksiModel->find($id);
        $tgl_kembali_seharusnya = strtotime($transaksi['tgl_kembali']);
        $tgl_sekarang = time();

        if ($tgl_sekarang > $tgl_kembali_seharusnya) {
            $selisih = floor(($tgl_sekarang - $tgl_kembali_seharusnya) / (60 * 60 * 24));
            $denda = $selisih * 10000;
            $this->transaksiModel->update($id, ['denda' => $denda]);
        }

        return redirect()->back()->with('success', 'Denda berhasil diperbarui.');
    }

    // --- FITUR BARU: Menandai Denda Lunas (Admin) ---
    public function lunaskan_denda($id)
    {
        $this->transaksiModel->update($id, ['status_denda' => 1]);
        return redirect()->back()->with('success', 'Denda telah ditandai Lunas.');
    }

    public function markAsRead($id)
    {
        $this->transaksiModel->update($id, ['is_read' => 1]);
        return redirect()->back();
    }

    public function delete($id)
    {
        $this->transaksiModel->delete($id);
        return redirect()->back()->with('success', 'Data transaksi berhasil dihapus.');
    }

    public function edit($id)
    {
        $transaksi = $this->transaksiModel->select('transaksi.*, barang.nama_barang, users.username, users.nama as nama_user')
            ->join('barang', 'barang.id_barang = transaksi.id_barang')
            ->join('users', 'users.id_user = transaksi.id_user')
            ->where('transaksi.id_transaksi', $id)
            ->first();

        if (!$transaksi) return redirect()->back()->with('error', 'Data tidak ditemukan.');

        $data = [
            'title'     => 'Edit Transaksi',
            'transaksi' => $transaksi
        ];

        return view('admin/transaksi/edit', $data);
    }

    public function update($id)
    {
        $this->transaksiModel->update($id, $this->request->getPost());
        return redirect()->to('admin/transaksi')->with('success', 'Data berhasil diupdate.');
    }

    // --- FITUR BATAL: Otomatis Kembalikan Stok ---
    public function batal($id)
    {
        $transaksi = $this->transaksiModel->find($id);

        if ($transaksi) {
            // Hanya status Booking yang bisa dibatalkan user
            if ($transaksi['status_transaksi'] == 'Booking') {

                // 1. KEMBALIKAN STOK (+1)
                $barang = $this->barangModel->find($transaksi['id_barang']);
                if ($barang) {
                    $this->barangModel->update($transaksi['id_barang'], [
                        'stok' => $barang['stok'] + 1 // Memperbaiki logika: stok bertambah 1
                    ]);
                }

                // 2. UBAH STATUS TRANSAKSI
                $this->transaksiModel->update($id, [
                    'status_transaksi' => 'Dibatalkan'
                ]);

                return redirect()->back()->with('success', 'Booking berhasil dibatalkan dan stok telah kembali.');
            }
        }

        return redirect()->back()->with('error', 'Gagal membatalkan booking.');
    }
}
