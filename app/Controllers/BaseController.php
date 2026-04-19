<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 */
abstract class BaseController extends Controller
{
    /**
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Deklarasi property agar tidak deprecated di PHP 8.2+
     */
    protected $session;
    protected $db;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->session = \Config\Services::session();
        $this->db      = \Config\Database::connect();

        // Ambil data session yang dibutuhkan
        $id_user = $this->session->get('id_user');
        $role    = $this->session->get('role'); // Ambil role untuk filter notifikasi

        // Inisialisasi variabel default agar view tidak error
        $notif_count = 0;
        $notif_list  = [];

        // --- 1. LOGIKA NOTIFIKASI KHUSUS ADMIN (Pesanan Baru) ---
        // Jika yang login adalah admin, ambil data transaksi yang belum dibaca (is_read = 0)
        if ($role == 'admin' || $role == 'Admin') {
            $builder = $this->db->table('transaksi');
            $builder->select('transaksi.*, users.nama as nama_user');
            $builder->join('users', 'users.id_user = transaksi.id_user');
            $builder->where('is_read', 0); // Hanya yang belum dibaca admin
            $builder->orderBy('transaksi.created_at', 'DESC');

            $notif_list  = $builder->get()->getResultArray();
            $notif_count = count($notif_list);
        }

        // --- 2. LOGIKA NOTIFIKASI KHUSUS USER (Denda) ---
        // Jika yang login adalah user biasa, ambil jumlah transaksi yang ada dendanya
        elseif ($id_user) {
            $notif_count = $this->db->table('transaksi')
                ->where('id_user', $id_user)
                ->where('denda >', 0) // Filter transaksi yang memiliki denda
                ->where('status_denda', 0) // <--- TAMBAHAN: Hanya hitung denda yang BELUM LUNAS
                ->countAllResults();
        }

        // --- 3. PENGIRIMAN DATA GLOBAL KE SEMUA VIEW ---
        // Menggunakan service renderer agar data tersedia di navbar (ikon lonceng) semua halaman
        $renderer = \Config\Services::renderer();
        $renderer->setData([
            'notif_count' => $notif_count, // Digunakan untuk angka merah di lonceng
            'notif_list'  => $notif_list   // Digunakan untuk daftar pesanan baru di dropdown Admin
        ]);

        // ---------------------------------
    }
}
