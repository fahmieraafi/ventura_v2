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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
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

        /* --- Style Chat Widget --- */
        #chat-window {
            bottom: 90px;
            right: 20px;
            width: 350px;
            height: 450px;
            z-index: 2000;
            border-radius: 15px;
            background: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
        }

        #chat-body {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
            background: rgba(15, 23, 42, 0.5);
        }

        .ai-msg,
        .user-msg {
            margin-bottom: 15px;
            max-width: 85%;
            padding: 10px;
            font-size: 0.85rem;
        }

        .ai-msg {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 15px 15px 15px 0;
            align-self: flex-start;
        }

        .user-msg {
            background: #0ea5e9;
            color: #fff;
            border-radius: 15px 15px 0 15px;
            align-self: flex-end;
            margin-left: auto;
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
                        <a class="nav-link nav-link-ventura text-info" href="<?= base_url('gunung') ?>">
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
                        $total_badge = $notif_count + (isset($total_terlambat) ? $total_terlambat : 0);
                        if ($total_badge > 0) : ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                                <?= $total_badge ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark p-2 animate__animated animate__fadeIn" style="width: 300px;">
                        <li class="px-3 py-2 border-bottom border-secondary mb-2">
                            <h6 class="mb-0 small fw-bold text-info">Notifikasi</h6>
                        </li>
                        <li class="text-center py-2 text-muted small">Cek detail di menu transaksi</li>
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

    <button id="chat-toggle" class="btn btn-info shadow-lg text-white rounded-circle position-fixed animate__animated animate__bounceInUp"
        style="bottom: 20px; right: 20px; width: 60px; height: 60px; z-index: 2000;">
        <i class="bi bi-robot fs-3"></i>
    </button>

    <div id="chat-window" class="card shadow-lg position-fixed d-none animate__animated animate__fadeInUp">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center" style="border-radius: 15px 15px 0 0;">
            <span class="fw-bold small"><i class="bi bi-stars me-2"></i>Ventura AI Assistant</span>
            <button type="button" class="btn-close btn-close-white" id="chat-close"></button>
        </div>
        <div class="card-body d-flex flex-column" id="chat-body">
            <div class="ai-msg">
                Halo <?= session()->get('username') ?>! Ada yang bisa saya bantu soal pendakian atau stok barang hari ini?
            </div>
        </div>
        <div class="card-footer bg-transparent border-top border-secondary">
            <div class="input-group">
                <input type="text" id="chat-input" class="form-control border-0 bg-dark text-white small" placeholder="Tanyakan stok tenda...">
                <button class="btn btn-info text-white" id="chat-send"><i class="bi bi-send"></i></button>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>

    <script>
        $(document).ready(function() {
            $('#chat-toggle, #chat-close').click(function() {
                $('#chat-window').toggleClass('d-none');
            });

            function scrollToBottom() {
                $('#chat-body').scrollTop($('#chat-body')[0].scrollHeight);
            }

            function sendChat() {
                let pesan = $('#chat-input').val();
                if (pesan.trim() === '') return;

                $('#chat-body').append(`<div class="user-msg">${pesan}</div>`);
                $('#chat-input').val('');
                scrollToBottom();

                $('#chat-body').append(`<div id="ai-loading" class="ai-msg italic small">Sedang berpikir...</div>`);
                scrollToBottom();

                $.ajax({
                    url: "<?= base_url('chat/tanyaAi') ?>",
                    method: "POST",
                    data: {
                        pesan: pesan,
                        // TAMBAHAN TOKEN KEAMANAN (CSRF)
                        "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
                    },
                    success: function(response) {
                        $('#ai-loading').remove();
                        $('#chat-body').append(`<div class="ai-msg">${response.jawaban}</div>`);
                        scrollToBottom();
                    },
                    error: function() {
                        $('#ai-loading').text("Maaf, koneksi terputus.");
                    }
                });
            }

            $('#chat-send').click(sendChat);
            $('#chat-input').keypress(function(e) {
                if (e.which == 13) sendChat();
            });
        });
    </script>
</body>

</html>