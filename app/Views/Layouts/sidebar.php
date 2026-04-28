<style>
    /* ===================================================================
   ICON RAIL SIDEBAR — CORPORATE DARK THEME
   Mini (body.sb-mini) : 64 px, icons only, flyout on hover
   Expanded            : 240 px, full accordion
=================================================================== */

    /* ─── DIMENSIONS ──────────────────────────────────────────────────── */
    /* Override flex layout so fixed sidebar + margin-left approach works */

    #layoutSidenav_nav {
        position: fixed !important;
        top: var(--nb-h, 54px);
        left: 0;
        bottom: 0;
        width: 64px !important;
        background: #0f172a !important;
        border-right: 1px solid rgba(255, 255, 255, .06) !important;
        box-shadow: 2px 0 20px rgba(0, 0, 0, .25) !important;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        /* no clipping — collapse panels + flyouts must escape */
        transition: transform .28s cubic-bezier(.4, 0, .2, 1);
        z-index: 1020;
    }

    body.sb-mini #layoutSidenav_nav {
        width: 64px !important;
    }

    body.sb-mini #layoutSidenav_nav {
        width: 64px;
    }

    /* Content area shifts with sidebar */
    #layoutSidenav_content {
        margin-left: 240px !important;
        transition: margin-left .28s cubic-bezier(.4, 0, .2, 1) !important;
    }

    body.sb-mini #layoutSidenav_content {
        margin-left: 64px !important;
    }

    /* ─── SIDENAV INNER ────────────────────────────────────────────────── */
    .sb-sidenav {
        background: transparent !important;
        color: rgba(255, 255, 255, .6) !important;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    /* ─── BRAND HEADER ─────────────────────────────────────────────────── */
    .sidebar-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0 13px;
        height: var(--nb-h, 54px);
        flex-shrink: 0;
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 65%, #1a56db 100%);
        border-bottom: 1px solid rgba(255, 255, 255, .07);
        text-decoration: none !important;
        overflow: hidden;
        position: relative;
    }

    .sidebar-brand::after {
        content: '';
        position: absolute;
        width: 120px;
        height: 120px;
        background: rgba(14, 165, 233, .12);
        border-radius: 50%;
        top: -40px;
        right: -30px;
        filter: blur(30px);
        pointer-events: none;
    }

    .sidebar-brand-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        background: rgba(255, 255, 255, .15);
        border: 1px solid rgba(255, 255, 255, .2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .95rem;
        color: white;
        flex-shrink: 0;
    }

    .sidebar-brand img.logo {
        height: 36px;
        border-radius: 8px;
        object-fit: contain;
        flex-shrink: 0;
    }

    .sidebar-brand-text {
        display: flex;
        flex-direction: column;
        min-width: 0;
        opacity: 1;
        transition: opacity .18s ease;
    }

    body.sb-mini .sidebar-brand-text {
        opacity: 0;
        pointer-events: none;
    }

    /* Hide entire brand header in mini mode — menu starts right below navbar */
    body.sb-mini .sidebar-brand {
        display: none !important;
    }

    .sidebar-brand-name {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif !important;
        font-size: .9rem;
        font-weight: 800;
        color: white;
        white-space: nowrap;
        line-height: 1.2;
    }

    .sidebar-branch {
        font-size: .68rem;
        color: rgba(255, 255, 255, .45);
        white-space: nowrap;
        margin-top: 1px;
    }

    /* ─── MENU SCROLL AREA ─────────────────────────────────────────────── */
    .sb-sidenav-menu {
        flex: 1;
        height: 0;
        min-height: 0;
        overflow-y: auto;
        overflow-x: hidden;
        padding: .4rem 0 1rem;
        background: #0f172a;
        /* own bg so it covers the nav's visible overflow */
        position: relative;
        z-index: 1;
    }

    .sb-sidenav-menu::-webkit-scrollbar {
        width: 3px;
    }

    .sb-sidenav-menu::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, .1);
        border-radius: 3px;
    }

    /* ─── SECTION HEADINGS ─────────────────────────────────────────────── */
    .sb-sidenav-menu-heading {
        font-size: .6rem !important;
        font-weight: 700 !important;
        letter-spacing: 1.6px !important;
        text-transform: uppercase !important;
        color: rgba(255, 255, 255, .22) !important;
        padding: .9rem .9rem .25rem !important;
        white-space: nowrap;
        overflow: hidden;
    }

    body.sb-mini .sb-sidenav-menu-heading {
        display: none !important;
    }

    /* ─── LEVEL-1 NAV LINKS ────────────────────────────────────────────── */
    .sb-sidenav-menu>.nav>.nav-link {
        display: flex !important;
        align-items: center !important;
        gap: 9px !important;
        color: rgba(255, 255, 255, .55) !important;
        font-size: .855rem !important;
        font-weight: 500 !important;
        padding: .5rem .75rem !important;
        margin: 1px .4rem !important;
        border-radius: 9px !important;
        transition: all .18s ease !important;
        text-decoration: none !important;
        white-space: nowrap;
        overflow: hidden;
    }

    .sb-sidenav-menu>.nav>.nav-link:hover {
        color: rgba(255, 255, 255, .9) !important;
        background: rgba(255, 255, 255, .06) !important;
    }

    .sb-sidenav-menu>.nav>.nav-link.active {
        color: #fff !important;
        background: linear-gradient(135deg, #1a56db, #0ea5e9) !important;
        box-shadow: 0 4px 14px rgba(26, 86, 219, .35) !important;
    }

    /* Mini: center icon pill */
    body.sb-mini .sb-sidenav-menu>.nav>.nav-link {
        padding: .55rem !important;
        margin: 2px 10px !important;
        width: 44px !important;
        justify-content: center !important;
        gap: 0 !important;
        border-radius: 10px !important;
    }

    /* ─── ICON ─────────────────────────────────────────────────────────── */
    .sb-nav-link-icon {
        width: 20px !important;
        text-align: center !important;
        font-size: .9rem !important;
        flex-shrink: 0 !important;
        opacity: .7;
        transition: opacity .18s ease;
    }

    .sb-sidenav-menu>.nav>.nav-link:hover .sb-nav-link-icon,
    .sb-sidenav-menu>.nav>.nav-link.active .sb-nav-link-icon {
        opacity: 1 !important;
    }

    /* ─── LABEL & ARROW ────────────────────────────────────────────────── */
    .nav-link-label {
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sb-sidenav-collapse-arrow {
        margin-left: auto !important;
        font-size: .65rem !important;
        opacity: .4 !important;
        transition: transform .25s ease, opacity .18s ease !important;
        flex-shrink: 0;
    }

    .sb-sidenav-menu>.nav>.nav-link[aria-expanded="true"] .sb-sidenav-collapse-arrow {
        transform: rotate(180deg) !important;
        opacity: .65 !important;
    }

    body.sb-mini .nav-link-label,
    body.sb-mini .sb-sidenav-collapse-arrow {
        display: none !important;
    }

    /* ─── HIDE COLLAPSES IN MINI ───────────────────────────────────────── */
    body.sb-mini .sb-sidenav-menu .collapse {
        display: none !important;
    }

    /* ─── NESTED NAV ───────────────────────────────────────────────────── */
    .sb-sidenav-menu-nested {
        background: rgba(0, 0, 0, .18) !important;
        border-radius: 8px !important;
        margin: 2px .4rem !important;
        padding: 3px 0 !important;
    }

    .sb-sidenav-menu-nested .nav-link {
        display: flex !important;
        align-items: center !important;
        gap: 7px !important;
        font-size: .82rem !important;
        font-weight: 400 !important;
        color: rgba(255, 255, 255, .42) !important;
        padding: .4rem .7rem .4rem 2rem !important;
        margin: 0 !important;
        border-radius: 7px !important;
        transition: all .18s ease !important;
        text-decoration: none !important;
        white-space: nowrap;
    }

    .sb-sidenav-menu-nested .nav-link:hover {
        color: rgba(255, 255, 255, .82) !important;
        background: rgba(255, 255, 255, .05) !important;
    }

    .sb-sidenav-menu-nested .nav-link.active {
        color: #38bdf8 !important;
        background: rgba(26, 86, 219, .14) !important;
        font-weight: 600 !important;
    }

    .sb-sidenav-menu-nested .sb-sidenav-menu-nested .nav-link {
        padding-left: 2.8rem !important;
    }

    .sb-sidenav-menu-nested .sb-sidenav-menu-heading {
        padding: .8rem .75rem .25rem 2rem !important;
        font-size: .58rem !important;
    }

    /* ─── FLYOUT PANELS (appended to body by JS — no stacking-context parent) */
    .sb-flyout {
        position: fixed;
        left: 64px;
        background: #1a2540;
        border: 1px solid rgba(255, 255, 255, .09);
        border-left: 2px solid #1a56db;
        border-radius: 0 10px 10px 0;
        min-width: 200px;
        box-shadow: 6px 8px 32px rgba(0, 0, 0, .55);
        z-index: 9990;
        /* above everything — lives on body, global stacking */
        display: none;
        padding: 6px 0 8px;
    }

    .sb-flyout.sb-flyout-on {
        display: block;
    }

    body:not(.sb-mini) .sb-flyout {
        display: none !important;
    }

    .sb-flyout-title {
        font-size: .67rem;
        font-weight: 700;
        letter-spacing: 1.3px;
        text-transform: uppercase;
        color: rgba(255, 255, 255, .3);
        padding: 6px 14px 5px;
        border-bottom: 1px solid rgba(255, 255, 255, .07);
        margin-bottom: 4px;
        font-family: 'Inter', sans-serif;
    }

    .sb-flyout-sub-label {
        font-size: .6rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: rgba(255, 255, 255, .2);
        padding: 5px 14px 2px;
        margin-top: 2px;
    }

    .sb-flyout .nav-link {
        display: flex !important;
        align-items: center !important;
        gap: 7px !important;
        font-size: .83rem !important;
        font-weight: 400 !important;
        color: rgba(255, 255, 255, .52) !important;
        padding: .38rem 1rem !important;
        margin: 1px .4rem !important;
        border-radius: 7px !important;
        text-decoration: none !important;
        white-space: nowrap;
        transition: all .15s ease !important;
    }

    .sb-flyout .nav-link:hover {
        color: rgba(255, 255, 255, .9) !important;
        background: rgba(255, 255, 255, .07) !important;
    }

    .sb-flyout .nav-link.active {
        color: #38bdf8 !important;
        background: rgba(26, 86, 219, .18) !important;
        font-weight: 600 !important;
    }

    /* Shared label tooltip for direct links — also appended to body by JS */
    #sb-tip {
        position: fixed;
        left: 70px;
        background: #1a2540;
        color: rgba(255, 255, 255, .85);
        font-size: .8rem;
        font-weight: 500;
        padding: 5px 12px;
        border-radius: 6px;
        border: 1px solid rgba(255, 255, 255, .09);
        border-left: 2px solid #1a56db;
        box-shadow: 4px 4px 20px rgba(0, 0, 0, .45);
        z-index: 9991;
        display: none;
        white-space: nowrap;
        pointer-events: none;
        font-family: 'Inter', sans-serif;
    }

    /* ─── MOBILE CLOSE BUTTON ──────────────────────────────────────────── */
    .close-mobile-nav {
        display: none;
        position: absolute;
        top: .85rem;
        right: .85rem;
        color: rgba(255, 255, 255, .4);
        cursor: pointer;
        font-size: 1.1rem;
        z-index: 10;
        transition: color .2s ease;
    }

    .close-mobile-nav:hover {
        color: white;
    }

    /* ─── RESPONSIVE ────────────────────────────────────────────────────── */
    @media (max-width: 768px) {
        #layoutSidenav_nav {
            left: -240px !important;
            width: 240px !important;
            top: 0 !important;
            transition: left .3s ease !important;
            z-index: 1025;
        }

        #layoutSidenav_nav.sb-open {
            left: 0 !important;
            box-shadow: 8px 0 40px rgba(0, 0, 0, .55) !important;
        }

        body.sb-mini #layoutSidenav_nav {
            width: 240px !important;
        }

        #layoutSidenav_content {
            margin-left: 0 !important;
        }

        .close-mobile-nav {
            display: block;
        }
    }
</style>

<div id="layoutSidenav_nav">
    <span class="close-mobile-nav" id="closeMobileNav"><i class="fa-solid fa-xmark"></i></span>

    <nav class="sb-sidenav accordion" id="sidenavAccordion">

        <!-- ── MENU ── -->
        <div class="sb-sidenav-menu">
            <div class="nav flex-column">

                <!-- FINANZAS -->
                <?php if (tienePermiso('ver_transacciones') || tienePermiso('ver_cajas') || tienePermiso('crear_caja') || tienePermiso('ver_cuentas')): ?>
                    <a class="nav-link collapsed" href="#"
                        data-bs-toggle="collapse" data-bs-target="#cash"
                        aria-expanded="false" aria-controls="cash"
                        data-flyout-id="cash">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-wallet"></i></div>
                        <span class="nav-link-label">Finanzas</span>
                        <div class="sb-sidenav-collapse-arrow"><i class="fa-solid fa-angle-down"></i></div>
                    </a>
                <?php endif; ?>

                <!-- BackUps Automaticos -->
                <?php if (tienePermiso('ver_backups')): ?>
                    <a class="nav-link collapsed" href="#"
                        data-bs-toggle="collapse" data-bs-target="#backups"
                        aria-expanded="false" aria-controls="backups"
                        data-flyout-id="backups">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-database"></i></div>
                        <span class="nav-link-label">BackUps Automaticos</span>
                        <div class="sb-sidenav-collapse-arrow"><i class="fa-solid fa-angle-down"></i></div>
                    </a>
                <?php endif; ?>

                <!-- REPORTERÍA -->
                <?php if (tienePermiso('ver_reportes')): ?>
                    <a class="nav-link" href="/reports" data-label="Reportería">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-chart-line"></i></div>
                        <span class="nav-link-label">Reportería</span>
                    </a>
                <?php endif; ?>

                <!-- BITÁCORA -->
                <?php if (tienePermiso('ver_bitacora')): ?>
                    <a class="nav-link" href="/logs" data-label="Bitácora">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-book"></i></div>
                        <span class="nav-link-label">Bitácora</span>
                    </a>
                <?php endif; ?>

                <!-- SISTEMA -->
                <?php if (tienePermiso('ver_configuracion') || tienePermiso('ver_sucursales') || tienePermiso('ver_usuarios') || tienePermiso('ver_roles')): ?>
                    <div class="sb-sidenav-menu-heading">Sistema</div>
                    <a class="nav-link collapsed" href="#"
                        data-bs-toggle="collapse" data-bs-target="#company_settings"
                        aria-expanded="false" aria-controls="company_settings"
                        data-flyout-id="company_settings">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-gear"></i></div>
                        <span class="nav-link-label">Ajustes del sistema</span>
                        <div class="sb-sidenav-collapse-arrow"><i class="fa-solid fa-angle-down"></i></div>
                    </a>
                <?php endif; ?>

            </div>
        </div>
    </nav>
    <!-- Finanzas -->
    <?php if (tienePermiso('ver_transacciones') || tienePermiso('ver_cajas') || tienePermiso('crear_caja') || tienePermiso('ver_cuentas')): ?>
        <div class="sb-flyout" id="flyout-cash">
            <div class="sb-flyout-title">Finanzas</div>
            <?php if (tienePermiso('ver_cajas') || tienePermiso('crear_caja') || tienePermiso('ver_historicos_de_caja')): ?>
                <div class="sb-flyout-sub-label">Cajas</div>
                <?php if (tienePermiso('ver_cajas')): ?><a class="nav-link" href="/cashiers">Lista de Cajas</a><?php endif; ?>
                <?php if (tienePermiso('crear_caja')): ?><a class="nav-link" href="/cashiers/new">Creación de caja</a><?php endif; ?>
                <?php if (tienePermiso('ver_historicos_de_caja')): ?><a class="nav-link" href="/cashier/transactions">Movimientos de caja</a><?php endif; ?>
            <?php endif; ?>
            <?php if (tienePermiso('ver_transacciones')): ?><a class="nav-link" href="/transactions">Movimientos históricos</a><?php endif; ?>
            <?php if (tienePermiso('ver_cuentas')): ?><a class="nav-link" href="/accounts">Cuentas</a><?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- BackUps Automaticos-->
    <?php if (tienePermiso('ver_backups')): ?>
        <div class="sb-flyout" id="flyout-backups">
            <div class="sb-flyout-title">BackUps Automaticos</div>
            <?php if (tienePermiso('ver_backups')): ?><a class="nav-link" href="/backups_automaticos">Lista de BackUps</a><?php endif; ?>
            <?php if (tienePermiso('ver_clientes')): ?><a class="nav-link" href="/clientes">Lista de Clientes</a><?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Paquetería -->
    <?php if (tienePermiso('crear_paquetes') || tienePermiso('ver_paquetes') || tienePermiso('ver_tracking')): ?>
        <div class="sb-flyout" id="flyout-sales">
            <div class="sb-flyout-title">Paquetería</div>
            <?php if (tienePermiso('crear_paquetes')): ?><a class="nav-link" href="/packages/new">Registrar paquete</a><?php endif; ?>
            <?php if (tienePermiso('ver_paquetes')): ?><a class="nav-link" href="/packages">Lista de paquetes</a><?php endif; ?>
            <?php if (tienePermiso('ver_tracking')): ?><a class="nav-link" href="/tracking">Seguimiento</a><?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Remuneraciones -->
    <?php if (tienePermiso('remunerar_paquetes') || tienePermiso('devolver_paquetes') || tienePermiso('remunerar_paquetes_por_cuenta')): ?>
        <div class="sb-flyout" id="flyout-treasury">
            <div class="sb-flyout-title">Remuneraciones</div>
            <?php if (tienePermiso('remunerar_paquetes')): ?><a class="nav-link" href="/remu/create">Remunerar paquetes</a><?php endif; ?>
            <?php if (tienePermiso('remunerar_paquetes_por_cuenta')): ?><a class="nav-link" href="/remuaccount/create">Remunerar por cuenta</a><?php endif; ?>
            <?php if (tienePermiso('devolver_paquetes')): ?><a class="nav-link" href="/packages/return">Devolución de paquetes</a><?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Puntos fijos y Rutas -->
    <?php if (tienePermiso('ver_puntosfijos') || tienePermiso('ver_rutas') || tienePermiso('ver_colonias')): ?>
        <div class="sb-flyout" id="flyout-settledpoint">
            <div class="sb-flyout-title">Puntos fijos y Rutas</div>
            <?php if (tienePermiso('ver_puntosfijos')): ?><a class="nav-link" href="/settledpoint">Puntos fijos</a><?php endif; ?>
            <?php if (tienePermiso('ver_rutas')): ?><a class="nav-link" href="/routes">Rutas</a><?php endif; ?>
            <?php if (tienePermiso('ver_colonias') || tienePermiso('ver_casilleros_externos')): ?>
                <div class="sb-flyout-sub-label">Mantenimientos</div>
                <?php if (tienePermiso('ver_colonias')): ?><a class="nav-link" href="/colonias">Colonias</a><?php endif; ?>
                <?php if (tienePermiso('ver_casilleros_externos')): ?><a class="nav-link" href="/external-locations">Casilleros externos</a><?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Ajustes del sistema -->
    <?php if (tienePermiso('ver_configuracion') || tienePermiso('ver_sucursales') || tienePermiso('ver_usuarios') || tienePermiso('ver_roles')): ?>
        <div class="sb-flyout" id="flyout-company_settings">
            <div class="sb-flyout-title">Ajustes del sistema</div>
            <?php if (tienePermiso('ver_usuarios')): ?><a class="nav-link" href="/users">Lista de usuarios</a><?php endif; ?>
            <?php if (tienePermiso('ver_roles')): ?><a class="nav-link" href="/roles">Roles</a><?php endif; ?>
            <?php if (tienePermiso('ver_sucursales')): ?><a class="nav-link" href="/branches">Sucursales</a><?php endif; ?>
            <?php if (tienePermiso('ajustes_multimedia')): ?><a class="nav-link" href="/content">Multimedia</a><?php endif; ?>
            <?php if (tienePermiso('ver_configuracion')): ?><a class="nav-link" href="/settings">Configuración</a><?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Shared label tooltip for direct-link items in mini mode -->
    <div id="sb-tip"></div>

</div><!-- /layoutSidenav_nav -->

<script>
    document.addEventListener('DOMContentLoaded', function() {

        /* ── MOVE FLYOUTS + TOOLTIP TO BODY (escape stacking context) ─ */
        document.querySelectorAll('.sb-flyout, #sb-tip').forEach(function(el) {
            document.body.appendChild(el);
        });

        /* ── ACTIVE LINK DETECTION ──────────────────────────────────── */
        let currentPath = window.location.pathname;
        if (currentPath === '/') currentPath = '/dashboard';
        currentPath = currentPath.replace(/^\//, '').split('?')[0].split('#')[0];

        document.querySelectorAll('#layoutSidenav_nav .nav-link').forEach(function(link) {
            const href = link.getAttribute('href');
            if (!href || href === '#') return;
            const normalized = href.replace(/^\//, '').split('?')[0].split('#')[0];
            if (currentPath === normalized) {
                link.classList.add('active');
                // Open parent collapses (Bootstrap 5)
                let col = link.closest('.collapse');
                while (col) {
                    col.classList.add('show');
                    const trigger = document.querySelector('[data-bs-target="#' + col.id + '"]');
                    if (trigger) {
                        trigger.classList.remove('collapsed');
                        trigger.setAttribute('aria-expanded', 'true');
                    }
                    col = col.parentElement ? col.parentElement.closest('.collapse') : null;
                }
                // Mirror active state in flyout panels (now in body)
                document.querySelectorAll('.sb-flyout .nav-link[href="' + href + '"]')
                    .forEach(function(fl) {
                        fl.classList.add('active');
                    });
            }
        });

        /* ── FLYOUT PANELS ──────────────────────────────────────────── */
        let hideTimer = null;

        function hideFlyouts() {
            document.querySelectorAll('.sb-flyout.sb-flyout-on')
                .forEach(function(f) {
                    f.classList.remove('sb-flyout-on');
                });
        }

        function positionAndShow(trigger, flyout) {
            if (!document.body.classList.contains('sb-mini')) return;
            clearTimeout(hideTimer);
            hideFlyouts();
            const rect = trigger.getBoundingClientRect();
            const nbh = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--nb-h')) || 54;
            let top = Math.max(nbh, rect.top);
            // Clamp so flyout doesn't overflow below viewport
            flyout.style.display = 'block'; // reveal briefly to read offsetHeight
            const fh = flyout.offsetHeight;
            flyout.style.display = '';
            const maxTop = window.innerHeight - fh - 8;
            if (maxTop > nbh) top = Math.min(top, maxTop);
            flyout.style.top = top + 'px';
            flyout.classList.add('sb-flyout-on');
        }

        document.querySelectorAll('[data-flyout-id]').forEach(function(trigger) {
            const flyout = document.getElementById('flyout-' + trigger.dataset.flyoutId);
            if (!flyout) return;

            // Hover: show flyout
            trigger.addEventListener('mouseenter', function() {
                positionAndShow(trigger, flyout);
            });
            trigger.addEventListener('mouseleave', function() {
                hideTimer = setTimeout(hideFlyouts, 150);
            });
            flyout.addEventListener('mouseenter', function() {
                clearTimeout(hideTimer);
            });
            flyout.addEventListener('mouseleave', function() {
                hideTimer = setTimeout(hideFlyouts, 150);
            });

            // Click in mini mode: toggle flyout instead of Bootstrap collapse
            trigger.addEventListener('click', function(e) {
                if (!document.body.classList.contains('sb-mini')) return;
                e.preventDefault();
                e.stopImmediatePropagation();
                if (flyout.classList.contains('sb-flyout-on')) {
                    hideFlyouts();
                } else {
                    positionAndShow(trigger, flyout);
                }
            }, true);
        });

        /* ── DIRECT-LINK TOOLTIP ────────────────────────────────────── */
        const tip = document.getElementById('sb-tip');

        document.querySelectorAll('.sb-sidenav-menu > .nav > .nav-link[data-label]').forEach(function(link) {
            link.addEventListener('mouseenter', function() {
                if (!document.body.classList.contains('sb-mini')) return;
                const rect = link.getBoundingClientRect();
                tip.textContent = link.dataset.label;
                tip.style.top = (rect.top + rect.height / 2 - 13) + 'px';
                tip.style.display = 'block';
            });
            link.addEventListener('mouseleave', function() {
                tip.style.display = 'none';
            });
        });

        /* ── MOBILE CLOSE BUTTON ────────────────────────────────────── */
        const closeBtn = document.getElementById('closeMobileNav');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                document.getElementById('layoutSidenav_nav').classList.remove('sb-open');
            });
        }
        const sidebarToggle = document.getElementById('sidebarToggle');
        if (sidebarToggle && window.innerWidth <= 768) {
            sidebarToggle.addEventListener('click', function() {
                document.getElementById('layoutSidenav_nav').classList.toggle('sb-open');
            });
        }
    });
</script>