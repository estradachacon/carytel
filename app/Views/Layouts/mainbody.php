<?php $session = session(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title><?= esc(setting('company_name') ?? 'Sistema') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="csrf-header" content="<?= csrf_header() ?>">

    <?php
    $favicon = setting('favicon');
    $faviconUrl = ($favicon && file_exists(FCPATH . 'upload/settings/' . $favicon))
        ? base_url('upload/settings/' . $favicon) : base_url('favicon.ico');
    ?>
    <link rel="shortcut icon" href="<?= esc($faviconUrl) ?>">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" crossorigin="anonymous">

    <!-- Plugins -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.11.1/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@icon/themify-icons@1.0.6/themify-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.9.1/dist/themes/classic.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- App CSS (SB Admin base, sin Bootstrap 4) -->
    <link href="<?= base_url('backend/assets/css/styles.css') ?>" rel="stylesheet">
    <link href="<?= base_url('backend/assets/css/helper.css') ?>" rel="stylesheet">
    <link href="<?= base_url('backend/assets/css/timeline.css?v=1.0') ?>" rel="stylesheet">

    <!-- jQuery (antes de todo) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>

    <style>
        /* ============================================================
           VARIABLES CORPORATIVAS
        ============================================================ */
        :root {
            --corp-dark: #0f172a;
            --corp-dark-2: #1e293b;
            --corp-primary: #1a56db;
            --corp-sky: #0ea5e9;
            --corp-glow: rgba(26, 86, 219, .28);
            --corp-border: rgba(255, 255, 255, .07);
            --sb-w: 64px;
            /* rail ancho */
            --nb-h: 54px;
            /* navbar alto */
        }

        /* ============================================================
           RESET GLOBAL
        ============================================================ */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body,
        button,
        input,
        select,
        textarea {
            font-family: 'Inter', sans-serif !important;
        }

        /* ============================================================
           PRELOADER
        ============================================================ */
        #preloader {
            position: fixed;
            inset: 0;
            background: var(--corp-dark);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 14px;
        }

        .corp-loader {
            width: 40px;
            height: 40px;
            border: 3px solid rgba(255, 255, 255, .08);
            border-top-color: var(--corp-primary);
            border-radius: 50%;
            animation: corp-spin .85s linear infinite;
        }

        @keyframes corp-spin {
            to {
                transform: rotate(360deg);
            }
        }

        .corp-loader-text {
            color: rgba(255, 255, 255, .3);
            font-size: .78rem;
            letter-spacing: 1px;
        }

        body.sb-hidden #layoutSidenav_content {
            margin-left: 0 !important;
        }

        /* ============================================================
           LAYOUT BASE – override SB Admin
        ============================================================ */
        body {
            background: #f1f5f9 !important;
        }

        /* Navbar fija arriba */
        .sb-topnav {
            position: fixed !important;
            top: 0;
            left: 0;
            right: 0;
            height: var(--nb-h);
            z-index: 1040 !important;
            background: linear-gradient(90deg, var(--corp-dark) 0%, var(--corp-dark-2) 100%) !important;
            border-bottom: 1px solid var(--corp-border) !important;
            box-shadow: 0 2px 20px rgba(0, 0, 0, .4) !important;
            padding: 0 1.25rem !important;
            display: flex;
            align-items: center;
        }

        /* Contenedor principal desplazado por navbar */
        #layoutSidenav {
            padding-top: var(--nb-h) !important;
            min-height: 100vh;
            display: flex;
        }

        /* Área de contenido */
        #layoutSidenav_content {
            flex: 1;
            background: #f1f5f9 !important;
            min-width: 0;
            overflow-x: hidden;
        }

        #layoutSidenav_content main {
            padding: 1.5rem;
        }

        /* ============================================================
           NAVBAR
        ============================================================ */
        /* Brand */
        .corp-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none !important;
            color: white !important;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif !important;
            font-weight: 800;
            font-size: 1rem;
        }

        .corp-brand-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--corp-primary), var(--corp-sky));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .85rem;
            color: white;
            box-shadow: 0 4px 12px var(--corp-glow);
        }

        .corp-brand-logo {
            height: 28px;
            border-radius: 6px;
            object-fit: contain;
        }

        /* Toggle button */
        #sidebarToggle {
            background: transparent !important;
            border: none !important;
            color: rgba(255, 255, 255, .45) !important;
            padding: .35rem .5rem !important;
            border-radius: 8px;
            cursor: pointer;
            transition: all .2s ease;
            line-height: 1;
        }

        #sidebarToggle:hover {
            background: rgba(255, 255, 255, .08) !important;
            color: white !important;
        }

        #sidebarToggle .bar {
            display: block;
            width: 18px;
            height: 2px;
            background: currentColor;
            border-radius: 2px;
            margin: 5px 0;
            transition: .25s;
        }

        /* Username chip */
        .corp-user-chip {
            background: rgba(255, 255, 255, .09);
            border: 1px solid rgba(255, 255, 255, .13);
            color: rgba(255, 255, 255, .8);
            border-radius: 8px;
            font-size: .78rem;
            font-weight: 500;
            padding: .3rem .75rem;
            white-space: nowrap;
        }

        /* Avatar */
        .corp-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, .18);
            transition: border-color .2s ease;
            cursor: pointer;
        }

        .corp-avatar:hover {
            border-color: var(--corp-primary);
        }

        /* Dropdown */
        .dropdown-menu {
            background: var(--corp-dark-2) !important;
            border: 1px solid rgba(255, 255, 255, .09) !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .5) !important;
            border-radius: 12px !important;
            padding: 6px !important;
            min-width: 175px !important;
        }

        .dropdown-item {
            color: rgba(255, 255, 255, .65) !important;
            font-size: .845rem !important;
            padding: .5rem .85rem !important;
            border-radius: 8px !important;
            transition: all .18s ease !important;
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            background: rgba(255, 255, 255, .07) !important;
            color: white !important;
        }

        .dropdown-item.text-danger-soft {
            color: #f87171 !important;
        }

        .dropdown-divider {
            border-color: rgba(255, 255, 255, .07) !important;
            margin: 4px 0 !important;
        }

        /* Notif dropdown */
        .notif-dropdown-menu {
            width: 360px !important;
            max-height: 380px !important;
            overflow-y: auto;
            padding: 0 !important;
        }

        .notif-dropdown-menu::-webkit-scrollbar {
            width: 3px;
        }

        .notif-dropdown-menu::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, .12);
            border-radius: 3px;
        }

        .notif-dropdown-header {
            padding: .65rem .9rem .35rem;
            color: rgba(255, 255, 255, .3);
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            border-bottom: 1px solid rgba(255, 255, 255, .07);
        }

        .notif-card {
            display: flex;
            gap: 10px;
            padding: 10px 12px;
            border-bottom: 1px solid rgba(255, 255, 255, .05);
            transition: .2s;
        }

        .notif-card:hover {
            background: rgba(255, 255, 255, .04);
            transform: translateX(3px);
        }

        .notif-icon {
            color: var(--corp-sky);
            font-size: 1rem;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .notif-title {
            font-weight: 600;
            font-size: .83rem;
            color: rgba(255, 255, 255, .82);
        }

        .notif-msg {
            font-size: .75rem;
            color: rgba(255, 255, 255, .38);
            word-break: break-word;
        }

        .notif-see-all {
            display: block;
            text-align: center;
            padding: .55rem;
            color: var(--corp-sky) !important;
            font-size: .83rem;
            font-weight: 600;
            border-top: 1px solid rgba(255, 255, 255, .07);
            text-decoration: none !important;
            transition: background .2s;
        }

        .notif-see-all:hover {
            background: rgba(255, 255, 255, .04);
        }

        /* Bell animation */
        .bell-alert {
            color: #f59e0b !important;
            animation: bellShake 1s ease-in-out 3;
        }

        @keyframes bellShake {

            0%,
            100% {
                transform: rotate(0)
            }

            15% {
                transform: rotate(-15deg)
            }

            30% {
                transform: rotate(10deg)
            }

            45% {
                transform: rotate(-10deg)
            }

            60% {
                transform: rotate(6deg)
            }

            75% {
                transform: rotate(-4deg)
            }
        }

        .bell-glow {
            text-shadow: 0 0 6px rgba(245, 158, 11, .7), 0 0 14px rgba(245, 158, 11, .4);
        }

        /* Alert strip */
        #main_alert {
            display: none;
            margin: 0 0 1rem;
            border-radius: 10px;
        }
    </style>
</head>

<body>

    <!-- Preloader -->
    <div id="preloader">
        <div class="corp-loader"></div>
        <div class="corp-loader-text">Cargando...</div>
    </div>

    <!-- ================================================================
         NAVBAR
    ================================================================ -->
    <nav class="sb-topnav">

        <!-- Brand -->
        <a href="/dashboard" class="corp-brand me-2">
            <?php if (setting('logo') && file_exists(FCPATH . 'upload/settings/' . setting('logo'))): ?>
                <img class="corp-brand-logo" src="<?= base_url('upload/settings/' . setting('logo')) ?>" alt="logo">
            <?php else: ?>
                <div class="corp-brand-icon"><i class="fa-solid fa-cube"></i></div>
            <?php endif; ?>
            <span class="d-none d-md-inline"><?= esc(setting('company_name') ?? 'Sistema') ?></span>
        </a>

        <!-- Sidebar toggle -->
        <button id="sidebarToggle" title="Menú">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>

        <!-- Right side -->
        <div class="ms-auto d-flex align-items-center gap-2">

            <!-- Notificaciones -->
            <div class="dropdown">
                <button class="btn p-1 border-0 position-relative"
                    style="background:transparent;color:rgba(255,255,255,.5);"
                    data-bs-toggle="dropdown" aria-expanded="false" id="notifToggle">
                    <i id="notifBell" class="fa-solid fa-bell" style="font-size:1rem;"></i>
                    <span id="notifCount"
                        class="position-absolute badge rounded-pill bg-danger"
                        style="top:0;right:0;font-size:.6rem;padding:2px 4px;display:none;">
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu-end notif-dropdown-menu" aria-labelledby="notifToggle">
                    <div class="notif-dropdown-header">
                        <i class="fa-solid fa-bell me-1" style="color:var(--corp-sky)"></i> Notificaciones
                    </div>
                    <div id="notifList">
                        <div style="padding:1rem;color:rgba(255,255,255,.3);font-size:.83rem;text-align:center;">
                            Sin notificaciones
                        </div>
                    </div>
                    <a href="<?= base_url('notifications') ?>" class="notif-see-all">Ver todas</a>
                </div>
            </div>

            <!-- Username -->
            <span class="corp-user-chip d-none d-md-inline">
                <?= esc($session->get('user_name') ?? 'Usuario') ?>
            </span>

            <!-- Avatar -->
            <div class="dropdown">
                <?php
                $foto = session('foto');
                $fotoPath = ($foto && file_exists(FCPATH . 'upload/perfiles/' . $foto))
                    ? base_url('upload/perfiles/' . $foto)
                    : base_url('upload/profile/user.jpg');
                ?>
                <img src="<?= esc($fotoPath) ?>"
                    class="corp-avatar"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    alt="avatar">
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="<?= base_url('perfil') ?>">
                            <i class="fa-regular fa-user"></i> Mi perfil
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item text-danger-soft" href="/logout">
                            <i class="fa-solid fa-power-off"></i> Cerrar sesión
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </nav>

    <!-- ================================================================
         LAYOUT
    ================================================================ -->
    <div id="layoutSidenav">
        <?php include('sidebar.php'); ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="alert alert-success alert-dismissible" id="main_alert" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <span class="msg"></span>
                </div>
                <?= $this->renderSection('content') ?>
            </main>

            <?php if (session()->getFlashdata('permiso_error')): ?>
                <div class="position-fixed top-0 end-0 p-3" style="z-index:2000;">
                    <div id="permToast" class="toast align-items-center text-bg-danger border-0 show" role="alert">
                        <div class="d-flex">
                            <div class="toast-body"><?= session()->getFlashdata('permiso_error') ?></div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var t = new bootstrap.Toast(document.getElementById('permToast'), {
                            delay: 4500
                        });
                        t.show();
                    });
                </script>
            <?php endif; ?>
        </div>
    </div>

    <!-- ================================================================
         SCRIPTS
    ================================================================ -->
    <!-- Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Plugins -->
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.11.1/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.9.1/dist/pickr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/i18n/es.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pace-js@1.2.4/pace.min.js"></script>
    <script src="<?= base_url('backend/assets/js/scripts.js') ?>"></script>

    <script>
        (function($) {
            "use strict";
            const color = "#1a56db",
                text_color = "#ffffff";
            document.documentElement.style.setProperty('--tab-active-bg', color);
            document.documentElement.style.setProperty('--tab-active-color', text_color);
        })(jQuery);
    </script>

    <script>
        /* ---- Preloader ---- */
        window.addEventListener('load', function() {
            const pre = document.getElementById('preloader');
            if (pre) {
                pre.style.transition = 'opacity .4s';
                pre.style.opacity = '0';
                setTimeout(() => pre.remove(), 400);
            }
        });

        /* ---- Sidebar toggle (persiste en localStorage) ---- */
(function () {
    const MINI   = 'sb-mini';
    const HIDDEN = 'sb-hidden';
    const body   = document.body;
    const btn    = document.getElementById('sidebarToggle');

    let saved = localStorage.getItem('sidebarState') || 'mini';

    body.classList.remove(MINI, HIDDEN);

    if (saved === 'hidden') {
        body.classList.add(HIDDEN);
    } else {
        body.classList.add(MINI);
    }

    btn.addEventListener('click', function () {

        if (body.classList.contains(MINI)) {
            body.classList.replace(MINI, HIDDEN);
            localStorage.setItem('sidebarState', 'hidden');

        } else {
            body.classList.replace(HIDDEN, MINI);
            localStorage.setItem('sidebarState', 'mini');
        }

    });
})();

        /* ---- Notificaciones ---- */
        let notifActivas = 0;

        function cargarNotificaciones() {
            fetch("<?= base_url('notifications/ultimas') ?>")
                .then(r => r.json())
                .then(data => {
                    const bell = document.getElementById('notifBell');
                    const badge = document.getElementById('notifCount');
                    const list = document.getElementById('notifList');
                    if (data.length === 0) {
                        badge.style.display = 'none';
                        bell.classList.remove('bell-alert', 'bell-glow');
                        notifActivas = 0;
                        list.innerHTML = `<div style="padding:1rem;color:rgba(255,255,255,.3);font-size:.83rem;text-align:center;">Sin notificaciones</div>`;
                    } else {
                        badge.textContent = data.length;
                        badge.style.display = 'inline-block';
                        if (data.length !== notifActivas) {
                            bell.classList.add('bell-alert', 'bell-glow');
                        }
                        notifActivas = data.length;
                        list.innerHTML = data.map(n => `
                            <a class="notif-card text-decoration-none notif-item" data-id="${n.id}" href="${n.link ?? '#'}">
                                <div class="notif-icon"><i class="fa-solid fa-circle-exclamation"></i></div>
                                <div>
                                    <div class="notif-title">${n.titulo}</div>
                                    <div class="notif-msg">${n.mensaje ?? ''}</div>
                                </div>
                            </a>`).join('');
                    }
                }).catch(e => console.warn('Notificaciones:', e));
        }

        document.addEventListener('click', function(e) {
            const item = e.target.closest('.notif-item');
            if (!item) return;
            e.preventDefault();
            const id = item.dataset.id;
            item.remove();
            fetch("<?= base_url('notifications/leer/') ?>" + id).then(() => cargarNotificaciones());
        });

        cargarNotificaciones();
        setInterval(cargarNotificaciones, 15000);
    </script>

    <?= $this->include('Layouts/toast') ?>
</body>

</html>