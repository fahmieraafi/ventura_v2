<!doctype html>

<html lang="en">



<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dashboard - Ventura_v2</title>



    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">

    <link href="<?= base_url('assets/bootstrap-icons-1.13.1/bootstrap-icons.css') ?>" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">



    <style>
        body {

            /* Perbaikan: Menggunakan base_url agar path gambar tidak pecah */

            background: linear-gradient(rgba(15, 23, 42, 0.75), rgba(15, 23, 42, 0.75)),

                url('<?= base_url("assets/img/86c59eea10149bb460d73440425e5d01.jpg") ?>');

            background-size: cover;

            background-position: center;

            background-repeat: no-repeat;

            background-attachment: fixed;

            font-family: "Poppins", sans-serif;

            color: #e4e4e4;

            margin: 0;

            min-height: 100vh;

        }



        .navbar-ventura {

            background: rgba(15, 23, 42, 0.9);

            backdrop-filter: blur(15px);

            border-bottom: 1px solid rgba(255, 255, 255, 0.1);

            padding: 10px 40px;

            position: sticky;

            top: 0;

            z-index: 1050;

        }



        .navbar-brand-ventura {

            font-size: 24px;

            font-weight: 700;

            color: #fff !important;

            display: flex;

            align-items: center;

            gap: 10px;

            text-decoration: none;

        }



        .nav-link-ventura {

            color: rgba(255, 255, 255, 0.7) !important;

            font-weight: 500;

            padding: 10px 20px !important;

            transition: 0.3s;

            border-radius: 8px;

        }



        .nav-link-ventura:hover,

        .nav-link-ventura.active {

            color: #fff !important;

            background: rgba(255, 255, 255, 0.1);

        }



        .user-avatar {

            width: 38px;

            height: 38px;

            border-radius: 50%;

            object-fit: cover;

            border: 2px solid rgba(255, 255, 255, 0.2);

        }



        .main-container {

            padding: 40px 20px;

            max-width: 1300px;

            margin: 0 auto;

        }



        .content-card {

            background: rgba(255, 255, 255, 0.05);

            padding: 30px;

            border-radius: 20px;

            border: 1px solid rgba(255, 255, 255, 0.1);

            animation: fadeIn 0.8s ease;

        }



        .dropdown-menu-dark {

            background-color: #0f172a;

            border: 1px solid rgba(255, 255, 255, 0.1);

        }



        .notif-badge {

            font-size: 9px;

            padding: 2px 5px;

            border: 2px solid #0f172a;

        }



        .icon-circle {

            width: 35px;

            height: 35px;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

        }



        @keyframes fadeIn {

            from {

                opacity: 0;

                transform: translateY(20px);

            }



            to {

                opacity: 1;

                transform: translateY(0);

            }

        }
    </style>

</head>



<body>




    <nav class="navbar navbar-expand-lg navbar-ventura">

        <div class="container-fluid">

            <a class="navbar-brand-ventura" href="<?= base_url('dashboard') ?>">

                <i class="bi bi-mountain"></i> ventura_v2

            </a>



            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">

                <ul class="navbar-nav gap-2">

                    <li class="nav-item">

                        <a class="nav-link nav-link-ventura" href="<?= base_url('dashboard') ?>">

                            <i class="bi bi-house-door me-2"></i>Dashboard

                        </a>

                    </li>



                    <li class="nav-item">

                        <a class="nav-link nav-link-ventura" href="<?= base_url('barang') ?>">

                            <i class="bi bi-box-seam me-2"></i>Data Barang

                        </a>

                    </li>


                    <li class="nav-item">
                        <a class="nav-link  nav-link-ventura text-info" <?= (uri_string() == 'gunung') ? 'active' : '' ?>" href="<?= base_url('gunung') ?>">
                            <i class="bi bi-geo-alt me-2"></i> <span>Explore</span>
                        </a>
                    </li>

                    <?php if (session()->get('role') == 'admin' || session()->get('role') == 'Admin') : ?>

                        <li class="nav-item">

                            <a class="nav-link nav-link-ventura text-info" href="<?= base_url('admin/transaksi') ?>">

                                <i class="bi bi-receipt me-2"></i>Kelola Transaksi

                            </a>

                        </li>

                        <li class="nav-item">

                            <a class="nav-link nav-link-ventura" href="<?= base_url('users') ?>">

                                <i class="bi bi-people me-2"></i>Users

                            </a>

                        </li>

                    <?php endif; ?>



                    <?php if (session()->get('role') == 'user' || session()->get('role') == 'User') : ?>

                        <li class="nav-item">

                            <a class="nav-link nav-link-ventura text-warning" href="<?= base_url('riwayat') ?>">

                                <i class="bi bi-clock-history me-2"></i>Riwayat Pinjam

                            </a>

                        </li>

                    <?php endif; ?>

                </ul>

            </div>



            <div class="d-flex align-items-center gap-4">





                <div class="dropdown">

                    <a class="text-white text-decoration-none position-relative" href="#" role="button" data-bs-toggle="dropdown">

                        <i class="bi bi-bell fs-5"></i>

                        <?php

                        // Menghitung total angka di badge (notif baru + terlambat)

                        $total_badge = $notif_count + (isset($total_terlambat) ? $total_terlambat : 0);

                        if ($total_badge > 0) : ?>

                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">

                                <?= $total_badge ?>

                            </span>

                        <?php endif; ?>

                    </a>



                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark p-2 animate__animated animate__fadeIn" style="width: 300px;">



                        <?php if (session()->get('role') == 'admin' || session()->get('role') == 'Admin') : ?>

                            <li class="px-3 py-2 border-bottom border-secondary mb-2">

                                <h6 class="mb-0 small fw-bold text-info">Pesanan Baru</h6>

                            </li>

                            <?php if ($notif_count > 0) : ?>

                                <?php foreach ($notif_list as $n) : ?>

                                    <li class="p-2 border-bottom border-secondary">

                                        <div class="d-flex justify-content-between align-items-start">

                                            <div>

                                                <small class="d-block fw-bold text-info"><?= $n['nama_user'] ?></small>

                                                <small class="text-muted">Melakukan penyewaan baru.</small>

                                            </div>

                                            <a href="<?= base_url('admin/transaksi/markAsRead/' . $n['id_transaksi']) ?>" class="btn btn-sm btn-outline-light py-0 px-1" title="Tandai Dibaca">

                                                <i class="bi bi-check2"></i>

                                            </a>

                                        </div>

                                    </li>

                                <?php endforeach; ?>

                            <?php else : ?>

                                <li class="text-center py-2 text-muted small">Tidak ada pesanan baru</li>

                            <?php endif; ?>



                            <li class="px-3 py-2 border-bottom border-secondary my-2">

                                <h6 class="mb-0 small fw-bold text-danger">Keterlambatan</h6>

                            </li>

                            <?php if (isset($total_terlambat) && $total_terlambat > 0) : ?>

                                <li class="p-2 border-bottom border-secondary bg-danger bg-opacity-10">

                                    <div class="d-flex align-items-center">

                                        <i class="bi bi-exclamation-triangle-fill text-danger me-3 fs-5"></i>

                                        <div>

                                            <small class="d-block fw-bold text-danger">Waktu Kembali Lewat!</small>

                                            <small class="text-white">Ada <?= $total_terlambat ?> barang belum kembali.</small>

                                        </div>

                                    </div>

                                </li>

                            <?php else : ?>

                                <li class="text-center py-2 text-muted small">Semua pengembalian tepat waktu</li>

                            <?php endif; ?>



                        <?php else : ?>

                            <li class="px-3 py-2 border-bottom border-secondary mb-2">

                                <h6 class="mb-0 small fw-bold">Pemberitahuan</h6>

                            </li>

                            <?php if ($notif_count > 0) : ?>

                                <li class="p-2">

                                    <div class="d-flex align-items-center">

                                        <i class="bi bi-exclamation-circle text-danger me-2"></i>

                                        <div>

                                            <small class="d-block fw-bold">Perhatian!</small>

                                            <small class="text-muted">Kamu memiliki <?= $notif_count ?> transaksi dengan denda yang belum dibayar.</small>

                                        </div>

                                    </div>

                                </li>

                            <?php else : ?>

                                <li class="text-center py-3 text-muted small">Tidak ada notifikasi denda</li>

                            <?php endif; ?>

                        <?php endif; ?>



                    </ul>

                </div>



                <div class="dropdown">

                    <a class="d-flex align-items-center gap-3 text-white text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">

                        <div class="text-end d-none d-sm-block">

                            <small class="d-block text-muted" style="font-size: 10px;">Masuk sebagai:</small>

                            <span class="fw-bold"><?= session()->get('username') ?></span>

                        </div>

                        <img src="<?= base_url('uploads/users/' . (session()->get('foto') ?: 'default.png')) ?>" class="user-avatar" alt="User">

                    </a>

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">

                        <li><a class="dropdown-item" href="<?= base_url('users/edit/' . session()->get('id_user')) ?>"><i class="bi bi-person me-2"></i>Profil</a></li>

                        <li>

                            <hr class="dropdown-divider border-secondary">

                        </li>

                        <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>

                    </ul>

                </div>

            </div>

        </div>

    </nav>



    <div class="main-container">

        <div class="content-card">

            <?= $this->renderSection('content') ?>

        </div>

    </div>



    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>

</body>



</html>