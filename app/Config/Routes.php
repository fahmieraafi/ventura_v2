<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- VARIABEL FILTER ---
$authFilter    = ['filter' => 'auth'];
$adminFilter   = ['filter' => 'role:admin'];
$userFilter    = ['filter' => 'role:user'];
$allRoleFilter = ['filter' => 'role:admin,user'];

// --- PUBLIC ROUTES ---
$routes->get('/users/create', 'Users::create');
$routes->post('/users/store', 'Users::store');

// --- AUTHENTICATION ---
$routes->get('/login', 'Auth::login');
$routes->post('/proses-login', 'Auth::prosesLogin');
$routes->get('/logout', 'Auth::logout');

// --- DASHBOARD ---
$routes->get('/', 'Home::index', $authFilter);
$routes->get('/dashboard', 'Home::index', $authFilter);

// --- MANAGEMENT USERS ---
$routes->get('/users', 'Users::index', $allRoleFilter);
$routes->get('/users/edit/(:num)', 'Users::edit/$1', $allRoleFilter);
$routes->post('/users/update/(:num)', 'Users::update/$1', $allRoleFilter);
$routes->get('/users/delete/(:num)', 'Users::delete/$1', $adminFilter);

// --- DATA BARANG ---
$routes->get('barang', 'Barang::index');

// --- FITUR BARU: DETAIL BARANG & TRACKER VIEWS ---
$routes->get('barang/(:num)', 'Barang::detail/$1');

$routes->get('barang/create', 'Barang::create', $adminFilter);
$routes->post('barang/store', 'Barang::store', $adminFilter);
$routes->get('barang/edit/(:num)', 'Barang::edit/$1', $adminFilter);
$routes->post('barang/update/(:num)', 'Barang::update/$1', $adminFilter);
$routes->get('barang/delete/(:num)', 'Barang::delete/$1', $adminFilter);
$routes->post('barang/hapusFotoSatuan', 'Barang::hapusFotoSatuan', $adminFilter);

// --- FITUR EXPLORE (INFO GUNUNG) ---
// Semua role bisa melihat daftar gunung dan detail gunung
$routes->get('gunung', 'Explore::index', $allRoleFilter);
$routes->get('gunung/detail/(:num)', 'Explore::detail/$1', $allRoleFilter); // <-- Tambahan Detail

// Admin bisa mengakses halaman tambah, proses simpan, edit, dan hapus
$routes->get('gunung/create', 'Explore::create', $adminFilter);
$routes->post('gunung/tambah', 'Explore::tambah', $adminFilter);
$routes->get('gunung/edit/(:num)', 'Explore::edit/$1', $adminFilter);     // <-- Tambahan Edit
$routes->post('gunung/update/(:num)', 'Explore::update/$1', $adminFilter); // <-- Tambahan Update
$routes->get('gunung/delete/(:num)', 'Explore::delete/$1', $adminFilter);

// --- FITUR TRANSAKSI USER ---
$routes->get('riwayat', 'Transaksi::index', $userFilter);
$routes->post('transaksi/simpan', 'Transaksi::simpan', $userFilter);
$routes->get('transaksi/hapus_riwayat/(:num)', 'Transaksi::hapus_riwayat/$1', $userFilter);

// FITUR BATAL (USER)
$routes->get('transaksi/batal/(:num)', 'Transaksi::batal/$1', $userFilter);

// --- FITUR TRANSAKSI ADMIN ---
$routes->group('admin', $adminFilter, function ($routes) {

    // Menampilkan halaman kelola transaksi
    $routes->get('transaksi', 'Transaksi::kelola');

    // FITUR KONFIRMASI
    $routes->get('transaksi/konfirmasi_bayar/(:num)', 'Transaksi::konfirmasi_bayar/$1');

    // FITUR UPDATE STATUS CEPAT
    $routes->get('transaksi/updateStatus/(:num)/(:any)', 'Transaksi::updateStatus/$1/$2');

    // FITUR LUNASKAN DENDA
    $routes->get('transaksi/lunaskan_denda/(:num)', 'Transaksi::lunaskan_denda/$1');

    // FITUR EDIT & DELETE
    $routes->get('transaksi/edit/(:num)', 'Transaksi::edit/$1');
    $routes->post('transaksi/update/(:num)', 'Transaksi::update/$1');
    $routes->get('transaksi/delete/(:num)', 'Transaksi::delete/$1');

    // FITUR PENDUKUNG
    $routes->get('transaksi/markAsRead/(:num)', 'Transaksi::markAsRead/$1');
    $routes->get('transaksi/hitungDenda/(:num)', 'Transaksi::hitungDenda/$1');
});
