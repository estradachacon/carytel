<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Carytel – Sistemas Empresariales en la Nube</title>
    <meta name="description" content="Carytel – Soluciones tecnológicas para empresas. Sistemas en la nube con acceso internacional, gestión de usuarios y soporte dedicado.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">

    <style {csp-style-nonce}>
        /* ============================================================
           ROOT VARIABLES – Corporate Tech Palette
        ============================================================ */
        :root {
            --primary:       #1a56db;
            --primary-dark:  #1e40af;
            --primary-glow:  rgba(26,86,219,.35);
            --secondary:     #0ea5e9;
            --accent:        #8b5cf6;
            --success:       #10b981;
            --dark:          #0f172a;
            --dark-2:        #1e293b;
            --dark-3:        #334155;
            --gray:          #64748b;
            --light:         #f0f7ff;
            --white:         #ffffff;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            background: var(--white);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* ============================================================
           NAVBAR
        ============================================================ */
        .navbar-corp {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 9999;
            padding: 1.1rem 0;
            transition: all .4s ease;
        }
        .navbar-corp.scrolled {
            background: rgba(15,23,42,.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: .65rem 0;
            box-shadow: 0 1px 0 rgba(255,255,255,.06), 0 8px 32px rgba(0,0,0,.35);
        }

        .navbar-brand-corp {
            display: flex; align-items: center; gap: 12px;
            text-decoration: none;
        }
        .brand-icon {
            width: 42px; height: 42px; border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; color: white;
            box-shadow: 0 4px 15px var(--primary-glow);
            flex-shrink: 0;
        }
        .brand-text-main {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.35rem; font-weight: 800;
            color: var(--white); line-height: 1.1;
        }
        .brand-text-sub {
            font-size: .65rem; font-weight: 500; letter-spacing: 1.5px;
            text-transform: uppercase; color: rgba(255,255,255,.5);
        }

        .nav-link-corp {
            color: rgba(255,255,255,.75) !important;
            font-weight: 500; font-size: .9rem;
            padding: .4rem .9rem !important;
            border-radius: 8px;
            transition: all .25s ease;
            letter-spacing: .2px;
        }
        .nav-link-corp:hover { color: var(--white) !important; background: rgba(255,255,255,.08); }

        .btn-nav-login {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: var(--white) !important;
            border: none; border-radius: 10px !important;
            padding: .5rem 1.3rem !important;
            font-weight: 600 !important; font-size: .88rem !important;
            box-shadow: 0 4px 14px var(--primary-glow);
            transition: all .3s ease !important;
        }
        .btn-nav-login:hover { transform: translateY(-2px) !important; box-shadow: 0 8px 20px var(--primary-glow) !important; }

        .navbar-toggler-corp {
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 8px; width: 40px; height: 40px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all .3s ease;
        }
        .navbar-toggler-corp:hover { background: rgba(255,255,255,.15); }

        .offcanvas-corp { background: var(--dark); }
        .offcanvas-corp .offcanvas-header { border-bottom: 1px solid rgba(255,255,255,.08); }
        .offcanvas-corp .nav-link {
            color: rgba(255,255,255,.75); font-weight: 500;
            padding: .75rem 1rem; border-radius: 8px;
            transition: all .25s ease;
        }
        .offcanvas-corp .nav-link:hover { background: rgba(255,255,255,.06); color: var(--white); }

        /* ============================================================
           HERO
        ============================================================ */
        .hero-section {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 45%, #1e3a8a 75%, #1a56db 100%);
            position: relative;
            display: flex; align-items: center;
            overflow: hidden;
        }

        /* Grid pattern overlay */
        .hero-section::before {
            content: '';
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* Glow orbs */
        .hero-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            animation: orb-pulse 8s ease-in-out infinite;
        }
        .orb-1 { width:500px;height:500px;background:rgba(26,86,219,.2);  top:-200px;right:-100px;animation-delay:0s; }
        .orb-2 { width:350px;height:350px;background:rgba(139,92,246,.15); bottom:-100px;left:-80px;animation-delay:3s; }
        .orb-3 { width:250px;height:250px;background:rgba(14,165,233,.12); top:30%;left:30%;animation-delay:1.5s; }

        @keyframes orb-pulse {
            0%,100% { transform:scale(1); opacity:.6; }
            50%      { transform:scale(1.15); opacity:1; }
        }

        .hero-content { position:relative; z-index:2; color:var(--white); }

        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.15);
            color: var(--secondary);
            padding: .4rem 1.1rem;
            border-radius: 50px; font-size: .82rem;
            font-weight: 600; letter-spacing: .5px;
            margin-bottom: 1.8rem;
            backdrop-filter: blur(10px);
            animation: fadeInDown .8s ease forwards;
        }
        .hero-badge-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: var(--success);
            box-shadow: 0 0 0 3px rgba(16,185,129,.25);
            animation: blink 2s ease-in-out infinite;
        }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }
        @keyframes fadeInDown { from{opacity:0;transform:translateY(-20px)} to{opacity:1;transform:translateY(0)} }
        @keyframes fadeInUp   { from{opacity:0;transform:translateY(30px)}  to{opacity:1;transform:translateY(0)} }
        @keyframes fadeInLeft { from{opacity:0;transform:translateX(-40px)} to{opacity:1;transform:translateX(0)} }
        @keyframes fadeInRight{ from{opacity:0;transform:translateX(40px)}  to{opacity:1;transform:translateX(0)} }

        .hero-title {
            font-size: clamp(2.4rem, 5.5vw, 4rem);
            font-weight: 800; line-height: 1.1;
            margin-bottom: 1.3rem;
            animation: fadeInLeft .9s ease .1s both;
        }
        .hero-title .gradient-text {
            background: linear-gradient(135deg, var(--secondary), #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-subtitle {
            font-size: 1.1rem; color: rgba(255,255,255,.7);
            max-width: 520px; line-height: 1.8; margin-bottom: 2.2rem;
            animation: fadeInLeft .9s ease .25s both;
        }
        .hero-cta {
            display: flex; gap: 1rem; flex-wrap: wrap;
            animation: fadeInLeft .9s ease .4s both;
        }

        .btn-hero-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: var(--white) !important;
            border: none; padding: .9rem 2rem; border-radius: 12px;
            font-weight: 700; font-size: 1rem;
            text-decoration: none; display:inline-flex; align-items:center; gap:10px;
            box-shadow: 0 8px 25px var(--primary-glow);
            transition: all .3s ease;
        }
        .btn-hero-primary:hover { transform:translateY(-4px); box-shadow:0 16px 40px var(--primary-glow); }

        .btn-hero-outline {
            background: rgba(255,255,255,.06);
            color: var(--white) !important;
            border: 1.5px solid rgba(255,255,255,.25);
            padding: .9rem 2rem; border-radius: 12px;
            font-weight: 600; font-size: 1rem;
            text-decoration: none; display:inline-flex; align-items:center; gap:10px;
            backdrop-filter: blur(8px);
            transition: all .3s ease;
        }
        .btn-hero-outline:hover { background:rgba(255,255,255,.12); transform:translateY(-4px); }

        /* Hero Visual – Dashboard mockup */
        .hero-visual { position:relative; animation: fadeInRight 1s ease .3s both; }

        .dashboard-mockup {
            background: var(--dark-2);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 16px;
            padding: 1.2rem;
            box-shadow: 0 40px 100px rgba(0,0,0,.6), 0 0 0 1px rgba(255,255,255,.06);
            position: relative;
            overflow: hidden;
        }
        .mockup-topbar {
            display: flex; align-items: center; gap: 6px;
            margin-bottom: 1rem;
        }
        .mockup-dot { width:10px;height:10px;border-radius:50%; }
        .mockup-dot.red   { background:#ef4444; }
        .mockup-dot.yellow{ background:#f59e0b; }
        .mockup-dot.green { background:#10b981; }
        .mockup-title-bar {
            flex: 1; height: 10px; background: rgba(255,255,255,.06);
            border-radius: 5px; margin-left:.5rem;
        }

        .mockup-grid { display:grid; grid-template-columns:1fr 1fr 1fr; gap:.7rem; margin-bottom:.7rem; }
        .mockup-stat-card {
            background: var(--dark-3);
            border-radius: 10px; padding: .8rem;
            border: 1px solid rgba(255,255,255,.06);
        }
        .mockup-stat-label { height:6px;background:rgba(255,255,255,.12);border-radius:3px;width:60%;margin-bottom:.5rem; }
        .mockup-stat-value { height:14px;background:linear-gradient(90deg,var(--primary),var(--secondary));border-radius:3px;width:80%; }

        .mockup-chart {
            background: var(--dark-3); border-radius:10px; padding:.8rem;
            border:1px solid rgba(255,255,255,.06); height:80px;
            display:flex; align-items:flex-end; gap:6px;
        }
        .chart-bar {
            flex:1; border-radius:4px 4px 0 0;
            background:linear-gradient(to top, var(--primary), var(--secondary));
            opacity:.8; animation: bar-grow .8s ease forwards;
        }
        @keyframes bar-grow { from{height:0} }

        .mockup-row { display:grid;grid-template-columns:2fr 1fr;gap:.7rem; }
        .mockup-table {
            background:var(--dark-3);border-radius:10px;padding:.8rem;
            border:1px solid rgba(255,255,255,.06);
        }
        .mockup-table-row { display:flex;gap:.5rem;margin-bottom:.4rem;align-items:center; }
        .mockup-avatar { width:18px;height:18px;border-radius:4px;background:linear-gradient(135deg,var(--accent),var(--primary));flex-shrink:0; }
        .mockup-line { height:6px;border-radius:3px;background:rgba(255,255,255,.1);flex:1; }
        .mockup-line.short { flex:.5; }
        .mockup-mini-card {
            background:var(--dark-3);border-radius:10px;padding:.8rem;
            border:1px solid rgba(255,255,255,.06);
        }
        .mockup-icon-big {
            width:32px;height:32px;border-radius:8px;
            background:linear-gradient(135deg,var(--success),#059669);
            display:flex;align-items:center;justify-content:center;
            font-size:.9rem;color:white;margin-bottom:.5rem;
        }

        /* Floating badges on mockup */
        .floating-badge {
            position:absolute;
            background:var(--white);
            border-radius:12px;padding:.6rem 1rem;
            box-shadow:0 10px 30px rgba(0,0,0,.3);
            display:flex;align-items:center;gap:8px;
            font-weight:700;font-size:.78rem;color:var(--dark);
            animation: float-badge 4s ease-in-out infinite;
        }
        .floating-badge.fb-1 { top:-20px;left:-30px;animation-delay:0s; }
        .floating-badge.fb-2 { bottom:-15px;right:-25px;animation-delay:1.5s; }
        .fb-icon { width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:.85rem;color:white;flex-shrink:0; }
        .fb-icon.blue  { background:linear-gradient(135deg,var(--primary),var(--secondary)); }
        .fb-icon.green { background:linear-gradient(135deg,var(--success),#059669); }
        @keyframes float-badge { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }

        /* ============================================================
           STATS
        ============================================================ */
        .stats-section {
            background: var(--dark-2);
            padding: 4rem 0;
            position: relative;
        }
        .stats-section::after {
            content:'';
            position:absolute;bottom:0;left:0;right:0;height:1px;
            background:linear-gradient(90deg,transparent,rgba(255,255,255,.08),transparent);
        }

        .stat-item { text-align:center; padding:1rem; }
        .stat-number {
            font-family:'Plus Jakarta Sans',sans-serif;
            font-size:2.8rem;font-weight:800;line-height:1;
            background:linear-gradient(135deg,var(--secondary),var(--accent));
            -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
        }
        .stat-label { color:rgba(255,255,255,.5);font-size:.85rem;font-weight:500;letter-spacing:.5px;margin-top:.5rem;text-transform:uppercase; }
        .stat-divider {
            width:1px;background:rgba(255,255,255,.08);
            margin:0 auto;
        }

        /* ============================================================
           SECTION COMMONS
        ============================================================ */
        .section-tag {
            display:inline-flex;align-items:center;gap:6px;
            background:rgba(26,86,219,.08);border:1px solid rgba(26,86,219,.2);
            color:var(--primary);padding:.35rem 1rem;border-radius:50px;
            font-size:.8rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase;
            margin-bottom:1rem;
        }
        .section-tag i { font-size:.7rem; }
        .section-title {
            font-size:clamp(1.7rem,3.5vw,2.5rem);font-weight:800;
            color:var(--dark);line-height:1.2;margin-bottom:1rem;
        }
        .section-title .hl { color:var(--primary); }
        .section-desc { color:var(--gray);font-size:1rem;max-width:580px;margin:0 auto;line-height:1.75; }

        /* ============================================================
           SERVICES
        ============================================================ */
        .services-section { padding:6rem 0;background:var(--white); }

        .service-card {
            background:var(--white);border-radius:16px;padding:2rem;
            border:1px solid #e2e8f0;
            transition:all .35s ease;height:100%;
            position:relative;overflow:hidden;
        }
        .service-card::before {
            content:'';
            position:absolute;top:0;left:0;right:0;height:3px;
            background:linear-gradient(90deg,var(--primary),var(--secondary));
            transform:scaleX(0);transform-origin:left;
            transition:transform .35s ease;
        }
        .service-card:hover { transform:translateY(-8px);box-shadow:0 20px 60px rgba(26,86,219,.1);border-color:rgba(26,86,219,.2); }
        .service-card:hover::before { transform:scaleX(1); }

        .service-icon {
            width:56px;height:56px;border-radius:14px;
            display:flex;align-items:center;justify-content:center;
            font-size:1.3rem;color:white;margin-bottom:1.2rem;
        }
        .service-card h5 {
            font-family:'Plus Jakarta Sans',sans-serif;
            font-size:1.05rem;font-weight:700;color:var(--dark);margin-bottom:.5rem;
        }
        .service-card p { font-size:.9rem;color:var(--gray);line-height:1.7;margin:0; }
        .service-tag {
            display:inline-block;margin-top:1rem;padding:.25rem .75rem;
            background:var(--light);color:var(--primary);
            border-radius:20px;font-size:.75rem;font-weight:600;
        }

        /* ============================================================
           FEATURES / ABOUT
        ============================================================ */
        .features-section { padding:6rem 0;background:var(--light); }

        .feature-visual {
            background:linear-gradient(135deg,var(--dark),var(--dark-2));
            border-radius:20px;padding:2.5rem;
            box-shadow:0 30px 80px rgba(0,0,0,.2);
            border:1px solid rgba(255,255,255,.06);
        }
        .feature-visual-title {
            color:rgba(255,255,255,.9);font-weight:700;font-size:1rem;margin-bottom:1.5rem;
            display:flex;align-items:center;gap:8px;
        }
        .fv-badge {
            display:inline-flex;align-items:center;gap:5px;
            background:rgba(16,185,129,.15);border:1px solid rgba(16,185,129,.3);
            color:#34d399;padding:.2rem .7rem;border-radius:20px;font-size:.72rem;font-weight:600;
        }
        .fv-dot { width:5px;height:5px;border-radius:50%;background:#10b981;animation:blink 2s infinite; }

        .fv-item {
            display:flex;align-items:center;gap:.8rem;
            padding:.75rem 1rem;border-radius:10px;
            background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);
            margin-bottom:.6rem;transition:all .25s ease;
        }
        .fv-item:hover { background:rgba(255,255,255,.08); }
        .fv-item-icon {
            width:34px;height:34px;border-radius:9px;
            display:flex;align-items:center;justify-content:center;
            font-size:.85rem;color:white;flex-shrink:0;
        }
        .fv-item-label { color:rgba(255,255,255,.8);font-size:.88rem;font-weight:500;flex:1; }
        .fv-item-status {
            font-size:.72rem;font-weight:700;padding:.2rem .55rem;border-radius:20px;
        }
        .fv-item-status.active { background:rgba(16,185,129,.15);color:#34d399; }
        .fv-item-status.cloud  { background:rgba(14,165,233,.15);color:#38bdf8; }

        .feature-list-item {
            display:flex;gap:1rem;margin-bottom:1.5rem;
        }
        .fli-icon {
            width:48px;height:48px;min-width:48px;border-radius:12px;
            display:flex;align-items:center;justify-content:center;
            font-size:1.1rem;color:white;
        }
        .fli-body h5 { font-family:'Plus Jakarta Sans',sans-serif;font-size:1rem;font-weight:700;color:var(--dark);margin-bottom:.3rem; }
        .fli-body p  { font-size:.9rem;color:var(--gray);margin:0;line-height:1.65; }

        .experience-badge {
            display:inline-flex;align-items:center;gap:12px;
            background:linear-gradient(135deg,var(--primary),var(--secondary));
            border-radius:14px;padding:1rem 1.5rem;
            color:white;margin-bottom:2rem;
            box-shadow:0 8px 25px var(--primary-glow);
        }
        .exp-num { font-size:2.5rem;font-weight:800;line-height:1; }
        .exp-text { font-size:.8rem;opacity:.85;line-height:1.4; }

        /* ============================================================
           PROCESS
        ============================================================ */
        .process-section { padding:6rem 0;background:var(--white); }

        .process-step {
            text-align:center;position:relative;
            padding:2rem 1.5rem;
        }
        .process-step:not(:last-child)::after {
            content:'';
            position:absolute;top:40px;left:calc(50% + 40px);
            width:calc(100% - 80px);height:1px;
            background:linear-gradient(90deg,var(--primary),transparent);
            border-top:2px dashed rgba(26,86,219,.3);
        }
        .step-number {
            width:72px;height:72px;border-radius:50%;
            background:linear-gradient(135deg,var(--primary),var(--secondary));
            display:flex;align-items:center;justify-content:center;
            font-size:1.4rem;font-weight:800;color:white;
            margin:0 auto 1.2rem;
            box-shadow:0 8px 25px var(--primary-glow);
            position:relative;
        }
        .step-number::before {
            content:'';position:absolute;inset:-4px;border-radius:50%;
            border:2px solid rgba(26,86,219,.2);
            animation:ring-pulse 2s ease-in-out infinite;
        }
        @keyframes ring-pulse { 0%,100%{transform:scale(1);opacity:.6} 50%{transform:scale(1.08);opacity:1} }

        .process-step h5 { font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:1.05rem;color:var(--dark);margin-bottom:.5rem; }
        .process-step p  { font-size:.9rem;color:var(--gray);line-height:1.65; }

        /* ============================================================
           CTA BANNER
        ============================================================ */
        .cta-section {
            padding:5rem 0;
            background:linear-gradient(135deg,var(--dark) 0%,var(--dark-2) 40%,#1e3a8a 70%,var(--primary) 100%);
            position:relative;overflow:hidden;text-align:center;
        }
        .cta-section::before {
            content:'';position:absolute;inset:0;
            background-image:
                linear-gradient(rgba(255,255,255,.02) 1px,transparent 1px),
                linear-gradient(90deg,rgba(255,255,255,.02) 1px,transparent 1px);
            background-size:50px 50px;
        }
        .cta-title { font-size:clamp(1.7rem,3.5vw,2.5rem);font-weight:800;color:white;margin-bottom:1rem;position:relative; }
        .cta-subtitle { font-size:1rem;color:rgba(255,255,255,.7);margin-bottom:2.5rem;max-width:480px;margin-left:auto;margin-right:auto;position:relative;line-height:1.75; }

        .btn-cta-white {
            background:white;color:var(--primary) !important;
            border:none;padding:1rem 2.2rem;border-radius:12px;
            font-weight:700;font-size:1rem;
            text-decoration:none;display:inline-flex;align-items:center;gap:10px;
            box-shadow:0 10px 30px rgba(0,0,0,.25);transition:all .3s ease;position:relative;
        }
        .btn-cta-white:hover { transform:translateY(-4px) scale(1.02);box-shadow:0 20px 50px rgba(0,0,0,.3); }

        .btn-cta-outline {
            background:rgba(255,255,255,.06);color:white !important;
            border:1.5px solid rgba(255,255,255,.25);padding:1rem 2.2rem;border-radius:12px;
            font-weight:600;font-size:1rem;
            text-decoration:none;display:inline-flex;align-items:center;gap:10px;
            backdrop-filter:blur(8px);transition:all .3s ease;position:relative;
        }
        .btn-cta-outline:hover { background:rgba(255,255,255,.12);transform:translateY(-4px); }

        /* ============================================================
           CONTACT
        ============================================================ */
        .contact-section { padding:6rem 0;background:var(--light); }

        .contact-card {
            background:white;border-radius:16px;padding:2rem;
            border:1px solid #e2e8f0;
            transition:all .3s ease;height:100%;
            display:flex;flex-direction:column;
        }
        .contact-card:hover { transform:translateY(-6px);box-shadow:0 20px 60px rgba(26,86,219,.1);border-color:rgba(26,86,219,.2); }
        .contact-icon {
            width:56px;height:56px;border-radius:14px;
            display:flex;align-items:center;justify-content:center;
            font-size:1.3rem;color:white;margin-bottom:1rem;
        }
        .contact-card h5 { font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:1rem;color:var(--dark);margin-bottom:.4rem; }
        .contact-card p  { color:var(--gray);font-size:.9rem;margin:0;line-height:1.6; }
        .contact-card a  { color:var(--primary);font-weight:600;text-decoration:none;font-size:.95rem; }
        .contact-card a:hover { text-decoration:underline; }

        /* ============================================================
           FOOTER
        ============================================================ */
        .footer-section { background:var(--dark);color:rgba(255,255,255,.65);padding:4rem 0 1.5rem; }
        .footer-brand-name { font-family:'Plus Jakarta Sans',sans-serif;color:white;font-size:1.6rem;font-weight:800;margin-bottom:.5rem; }
        .footer-brand-desc { font-size:.88rem;opacity:.55;max-width:280px;line-height:1.65; }

        .footer-heading {
            color:white;font-family:'Plus Jakarta Sans',sans-serif;
            font-size:.95rem;font-weight:700;margin-bottom:1.2rem;
            padding-bottom:.6rem;position:relative;
        }
        .footer-heading::after { content:'';position:absolute;bottom:0;left:0;width:24px;height:2px;background:var(--primary);border-radius:2px; }

        .footer-links { list-style:none;padding:0; }
        .footer-links li { margin-bottom:.55rem; }
        .footer-links a {
            color:rgba(255,255,255,.5);text-decoration:none;font-size:.88rem;
            transition:all .25s ease;display:flex;align-items:center;gap:6px;
        }
        .footer-links a:hover { color:var(--secondary);padding-left:4px; }

        .footer-social { display:flex;gap:.65rem;margin-top:1.5rem; }
        .social-icon {
            width:36px;height:36px;border-radius:9px;
            display:flex;align-items:center;justify-content:center;
            background:rgba(255,255,255,.07);color:rgba(255,255,255,.6);
            text-decoration:none;font-size:.95rem;transition:all .25s ease;
            border:1px solid rgba(255,255,255,.08);
        }
        .social-icon:hover { background:var(--primary);color:white;border-color:var(--primary);transform:translateY(-3px); }

        .footer-bottom {
            border-top:1px solid rgba(255,255,255,.07);
            padding-top:1.5rem;margin-top:3rem;
            display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.5rem;
            font-size:.82rem;opacity:.45;
        }

        /* ============================================================
           FLOATING BUTTONS
        ============================================================ */
        .scroll-top {
            position:fixed;bottom:2rem;right:2rem;
            width:44px;height:44px;border-radius:12px;
            background:linear-gradient(135deg,var(--primary),var(--secondary));
            color:white;border:none;font-size:1rem;cursor:pointer;
            box-shadow:0 8px 25px var(--primary-glow);
            transition:all .3s ease;opacity:0;visibility:hidden;z-index:9998;
            display:flex;align-items:center;justify-content:center;
        }
        .scroll-top.visible { opacity:1;visibility:visible; }
        .scroll-top:hover   { transform:translateY(-4px) scale(1.05); }

        .whatsapp-float {
            position:fixed;bottom:2rem;left:2rem;
            width:52px;height:52px;border-radius:14px;
            background:#25D366;color:white;border:none;font-size:1.5rem;
            cursor:pointer;box-shadow:0 8px 25px rgba(37,211,102,.4);
            transition:all .3s ease;z-index:9998;
            display:flex;align-items:center;justify-content:center;text-decoration:none;
        }
        .whatsapp-float:hover { transform:scale(1.12);box-shadow:0 12px 35px rgba(37,211,102,.5);color:white; }
        .pulse-ring {
            position:absolute;width:52px;height:52px;border-radius:14px;
            background:rgba(37,211,102,.35);animation:pulse-ring 1.8s ease-out infinite;
        }
        @keyframes pulse-ring { from{transform:scale(1);opacity:1} to{transform:scale(1.6);opacity:0} }

        /* ============================================================
           RESPONSIVE
        ============================================================ */
        @media (max-width:991px) {
            .hero-visual { display:none; }
            .process-step:not(:last-child)::after { display:none; }
        }
        @media (max-width:768px) {
            .hero-title { font-size:2rem; }
        }
    </style>
</head>

<body>

    <!-- ================================================================
         NAVBAR
    ================================================================ -->
    <nav class="navbar-corp" id="mainNavbar">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between w-100">

                <a href="<?= base_url() ?>" class="navbar-brand-corp">
                    <div class="brand-icon"><i class="fa-solid fa-cube"></i></div>
                    <div>
                        <div class="brand-text-main">Carytel</div>
                        <div class="brand-text-sub">Software Empresarial</div>
                    </div>
                </a>

                <div class="d-none d-lg-flex align-items-center gap-1">
                    <a href="#inicio"    class="nav-link-corp">Inicio</a>
                    <a href="#servicios" class="nav-link-corp">Servicios</a>
                    <a href="#nosotros"  class="nav-link-corp">Nosotros</a>
                    <a href="#proceso"   class="nav-link-corp">Proceso</a>
                    <a href="#contacto"  class="nav-link-corp">Contacto</a>
                    <a href="#" class="nav-link-corp btn-nav-login ms-2"
                       data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Acceso
                    </a>
                </div>

                <button class="navbar-toggler-corp d-lg-none"
                        data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-label="Menú">
                    <i class="fas fa-bars" style="color:rgba(255,255,255,.8);font-size:1rem;"></i>
                </button>
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-end offcanvas-corp" id="mobileMenu" tabindex="-1">
        <div class="offcanvas-header">
            <div class="d-flex align-items-center gap-2">
                <div class="brand-icon" style="width:34px;height:34px;font-size:.9rem;"><i class="fa-solid fa-cube"></i></div>
                <span style="color:white;font-weight:700;font-size:1rem;">Carytel</span>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <nav class="d-flex flex-column gap-1">
                <a href="#inicio"    class="nav-link" data-bs-dismiss="offcanvas">Inicio</a>
                <a href="#servicios" class="nav-link" data-bs-dismiss="offcanvas">Servicios</a>
                <a href="#nosotros"  class="nav-link" data-bs-dismiss="offcanvas">Nosotros</a>
                <a href="#proceso"   class="nav-link" data-bs-dismiss="offcanvas">Proceso</a>
                <a href="#contacto"  class="nav-link" data-bs-dismiss="offcanvas">Contacto</a>
                <a href="#" class="nav-link btn-nav-login text-center mt-3"
                   data-bs-dismiss="offcanvas"
                   data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Acceso al Sistema
                </a>
            </nav>
        </div>
    </div>

    <!-- ================================================================
         HERO
    ================================================================ -->
    <section class="hero-section" id="inicio">
        <div class="hero-orb orb-1"></div>
        <div class="hero-orb orb-2"></div>
        <div class="hero-orb orb-3"></div>

        <div class="container">
            <div class="row align-items-center min-vh-100 py-5 g-5">

                <div class="col-lg-6 hero-content">
                    <div class="hero-badge">
                        <span class="hero-badge-dot"></span>
                        Sistemas activos — Acceso internacional 24/7
                    </div>
                    <h1 class="hero-title">
                        Sistemas Empresariales<br>
                        <span class="gradient-text">en la Nube</span>
                    </h1>
                    <p class="hero-subtitle">
                        Soluciones tecnológicas a medida para impulsar tu empresa. Acceso desde cualquier parte del mundo, gestión multi-usuario y soporte dedicado.
                    </p>
                    <div class="hero-cta">
                        <a href="#servicios" class="btn-hero-primary">
                            <i class="fas fa-rocket"></i> Ver Servicios
                        </a>
                        <a href="#contacto" class="btn-hero-outline">
                            <i class="fas fa-comments"></i> Habla con nosotros
                        </a>
                    </div>
                </div>

                <div class="col-lg-6 hero-visual">
                    <div class="position-relative" style="padding:30px 30px 30px 10px;">
                        <div class="dashboard-mockup">
                            <div class="mockup-topbar">
                                <div class="mockup-dot red"></div>
                                <div class="mockup-dot yellow"></div>
                                <div class="mockup-dot green"></div>
                                <div class="mockup-title-bar"></div>
                            </div>
                            <div class="mockup-grid">
                                <div class="mockup-stat-card"><div class="mockup-stat-label"></div><div class="mockup-stat-value"></div></div>
                                <div class="mockup-stat-card"><div class="mockup-stat-label"></div><div class="mockup-stat-value" style="background:linear-gradient(90deg,var(--accent),var(--secondary));width:65%;"></div></div>
                                <div class="mockup-stat-card"><div class="mockup-stat-label"></div><div class="mockup-stat-value" style="background:linear-gradient(90deg,var(--success),#059669);width:90%;"></div></div>
                            </div>
                            <div class="mockup-chart">
                                <div class="chart-bar" style="height:40%;animation-delay:.1s;"></div>
                                <div class="chart-bar" style="height:65%;animation-delay:.2s;"></div>
                                <div class="chart-bar" style="height:55%;animation-delay:.3s;"></div>
                                <div class="chart-bar" style="height:80%;animation-delay:.4s;"></div>
                                <div class="chart-bar" style="height:70%;animation-delay:.5s;"></div>
                                <div class="chart-bar" style="height:90%;animation-delay:.6s;"></div>
                                <div class="chart-bar" style="height:75%;animation-delay:.7s;"></div>
                                <div class="chart-bar" style="height:100%;animation-delay:.8s;"></div>
                            </div>
                            <div class="mockup-row mt-2">
                                <div class="mockup-table">
                                    <?php for($i=0;$i<4;$i++): ?>
                                    <div class="mockup-table-row">
                                        <div class="mockup-avatar" style="<?= $i%2==0?'':'background:linear-gradient(135deg,var(--success),#059669)' ?>"></div>
                                        <div class="mockup-line"></div>
                                        <div class="mockup-line short"></div>
                                    </div>
                                    <?php endfor; ?>
                                </div>
                                <div class="mockup-mini-card">
                                    <div class="mockup-icon-big"><i class="fas fa-check"></i></div>
                                    <div class="mockup-stat-label" style="width:80%"></div>
                                    <div class="mockup-stat-value mt-2" style="height:8px;background:linear-gradient(90deg,var(--success),#059669);width:70%;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="floating-badge fb-1">
                            <div class="fb-icon blue"><i class="fas fa-globe"></i></div>
                            <div>
                                <div style="color:#64748b;font-size:.65rem;">Acceso</div>
                                <div>Internacional</div>
                            </div>
                        </div>
                        <div class="floating-badge fb-2">
                            <div class="fb-icon green"><i class="fas fa-shield-halved"></i></div>
                            <div>
                                <div style="color:#64748b;font-size:.65rem;">Sistema</div>
                                <div>Seguro</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================================================================
         STATS
    ================================================================ -->
    <section class="stats-section">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="stat-item">
                        <div class="stat-number" data-count="2">0</div>
                        <div class="stat-label">Años de experiencia</div>
                    </div>
                </div>
                <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-item">
                        <div class="stat-number">∞</div>
                        <div class="stat-label">Acceso Internacional</div>
                    </div>
                </div>
                <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Disponibilidad Online</div>
                    </div>
                </div>
                <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-item">
                        <div class="stat-number" data-count="100">0</div>
                        <div class="stat-label">% en la nube</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================================================================
         SERVICES
    ================================================================ -->
    <section class="services-section" id="servicios">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <div class="section-tag"><i class="fas fa-layer-group"></i> Nuestros Servicios</div>
                <h2 class="section-title">Soluciones para cada <span class="hl">necesidad</span></h2>
                <p class="section-desc">Desarrollamos sistemas completos adaptados a tu modelo de negocio, con la tecnología más moderna.</p>
            </div>
            <div class="row g-4">

                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="service-card">
                        <div class="service-icon" style="background:linear-gradient(135deg,#1a56db,#0ea5e9)"><i class="fas fa-briefcase"></i></div>
                        <h5>Gestión Empresarial</h5>
                        <p>Sistema integral para administrar operaciones, clientes, proveedores y todos los procesos core de tu empresa desde un solo lugar.</p>
                        <span class="service-tag">ERP Completo</span>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="80">
                    <div class="service-card">
                        <div class="service-icon" style="background:linear-gradient(135deg,#7c3aed,#8b5cf6)"><i class="fas fa-boxes-stacked"></i></div>
                        <h5>Control de Inventario</h5>
                        <p>Gestiona tu stock en tiempo real, recibe alertas automáticas, genera reportes y controla entradas y salidas con precisión.</p>
                        <span class="service-tag">Tiempo Real</span>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="160">
                    <div class="service-card">
                        <div class="service-icon" style="background:linear-gradient(135deg,#059669,#10b981)"><i class="fas fa-cash-register"></i></div>
                        <h5>Punto de Venta</h5>
                        <p>Sistema de caja rápido y confiable, con control de sesiones, cortes, movimientos y reportes de cierre al instante.</p>
                        <span class="service-tag">POS Cloud</span>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="service-card">
                        <div class="service-icon" style="background:linear-gradient(135deg,#d97706,#f59e0b)"><i class="fas fa-users-gear"></i></div>
                        <h5>Gestión de Usuarios</h5>
                        <p>Control granular de accesos con roles y permisos personalizados. Cada usuario ve y hace exactamente lo que necesita.</p>
                        <span class="service-tag">Multi-usuario</span>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="80">
                    <div class="service-card">
                        <div class="service-icon" style="background:linear-gradient(135deg,#db2777,#ec4899)"><i class="fas fa-chart-line"></i></div>
                        <h5>Reportes y Analytics</h5>
                        <p>Paneles de control con indicadores clave, gráficos interactivos y exportación a Excel/PDF para decisiones basadas en datos.</p>
                        <span class="service-tag">Dashboards</span>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="160">
                    <div class="service-card">
                        <div class="service-icon" style="background:linear-gradient(135deg,#0891b2,#06b6d4)"><i class="fas fa-route"></i></div>
                        <h5>Logística y Rastreo</h5>
                        <p>Seguimiento en tiempo real de paquetes, rutas y entregas. Ideal para empresas de mensajería, distribución y comercio.</p>
                        <span class="service-tag">Tracking</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================================================================
         FEATURES / ABOUT
    ================================================================ -->
    <section class="features-section" id="nosotros">
        <div class="container">
            <div class="row align-items-center g-5">

                <div class="col-lg-5" data-aos="fade-right">
                    <div class="feature-visual">
                        <div class="feature-visual-title">
                            <span>Estado del sistema</span>
                            <span class="fv-badge ms-auto"><span class="fv-dot"></span> Online</span>
                        </div>
                        <?php
                        $fvItems = [
                            ['fas fa-cloud',       'background:linear-gradient(135deg,#1a56db,#0ea5e9)', 'Almacenamiento Cloud',     'cloud'],
                            ['fas fa-lock',         'background:linear-gradient(135deg,#7c3aed,#8b5cf6)', 'Cifrado de datos SSL',    'active'],
                            ['fas fa-globe',        'background:linear-gradient(135deg,#059669,#10b981)', 'Acceso Internacional',    'active'],
                            ['fas fa-users',        'background:linear-gradient(135deg,#d97706,#f59e0b)', 'Multi-usuario y roles',   'active'],
                            ['fas fa-database',     'background:linear-gradient(135deg,#db2777,#ec4899)', 'Backups automáticos',     'active'],
                            ['fas fa-headset',      'background:linear-gradient(135deg,#0891b2,#06b6d4)', 'Soporte dedicado',        'active'],
                        ];
                        foreach ($fvItems as [$icon, $bg, $label, $status]):
                        ?>
                        <div class="fv-item">
                            <div class="fv-item-icon" style="<?= $bg ?>"><i class="<?= $icon ?>"></i></div>
                            <span class="fv-item-label"><?= $label ?></span>
                            <span class="fv-item-status <?= $status ?>"><?= $status === 'cloud' ? 'Cloud' : 'Activo' ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="col-lg-7" data-aos="fade-left">
                    <div class="section-tag"><i class="fas fa-star"></i> ¿Por qué elegirnos?</div>
                    <h2 class="section-title">Tecnología que trabaja <span class="hl">para tu empresa</span></h2>

                    <div class="experience-badge">
                        <div class="exp-num">2+</div>
                        <div class="exp-text">años desarrollando<br>sistemas empresariales</div>
                    </div>

                    <div class="feature-list-item" data-aos="fade-left" data-aos-delay="100">
                        <div class="fli-icon" style="background:linear-gradient(135deg,#1a56db,#0ea5e9)"><i class="fas fa-cloud-arrow-up"></i></div>
                        <div class="fli-body">
                            <h5>100% en la Nube</h5>
                            <p>Tus datos y tu sistema siempre disponibles desde cualquier dispositivo, en cualquier lugar del mundo. Sin instalaciones, sin limitaciones geográficas.</p>
                        </div>
                    </div>
                    <div class="feature-list-item" data-aos="fade-left" data-aos-delay="200">
                        <div class="fli-icon" style="background:linear-gradient(135deg,#7c3aed,#8b5cf6)"><i class="fas fa-sliders"></i></div>
                        <div class="fli-body">
                            <h5>A la medida de tu negocio</h5>
                            <p>Cada sistema se diseña y adapta a tus procesos específicos, no al revés. Módulos configurables, flujos personalizados y escalabilidad garantizada.</p>
                        </div>
                    </div>
                    <div class="feature-list-item" data-aos="fade-left" data-aos-delay="300">
                        <div class="fli-icon" style="background:linear-gradient(135deg,#059669,#10b981)"><i class="fas fa-shield-halved"></i></div>
                        <div class="fli-body">
                            <h5>Seguridad y Confiabilidad</h5>
                            <p>Cifrado SSL, backups automáticos y control de acceso por roles. Tu información siempre protegida con los más altos estándares de seguridad.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================================================================
         PROCESS
    ================================================================ -->
    <section class="process-section" id="proceso">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <div class="section-tag"><i class="fas fa-arrows-rotate"></i> Cómo Trabajamos</div>
                <h2 class="section-title">Tu sistema listo en <span class="hl">3 pasos</span></h2>
                <p class="section-desc">Un proceso claro y transparente para que tu empresa esté operando con su nuevo sistema lo antes posible.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="process-step">
                        <div class="step-number">1</div>
                        <h5>Consulta y Análisis</h5>
                        <p>Conversamos sobre tus procesos, necesidades y objetivos. Definimos el alcance del sistema y te presentamos una propuesta clara.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="150">
                    <div class="process-step">
                        <div class="step-number">2</div>
                        <h5>Desarrollo e Implementación</h5>
                        <p>Construimos tu sistema con metodología ágil, con entregas incrementales para que puedas ver el avance y dar retroalimentación.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="process-step">
                        <div class="step-number">3</div>
                        <h5>Capacitación y Soporte</h5>
                        <p>Te entrenamos a ti y a tu equipo, y seguimos contigo después del lanzamiento con soporte continuo y mejoras constantes.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================================================================
         CTA BANNER
    ================================================================ -->
    <section class="cta-section" data-aos="fade-up">
        <div class="container position-relative" style="z-index:2;">
            <h2 class="cta-title">¿Listo para digitalizar<br>tu empresa?</h2>
            <p class="cta-subtitle">Hablemos hoy. Te mostramos cómo un sistema en la nube puede transformar la eficiencia de tu negocio.</p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="#contacto" class="btn-cta-white">
                    <i class="fas fa-envelope"></i> Contáctanos ahora
                </a>
                <a href="https://wa.me/50377663903?text=Hola!%20Me%20interesa%20conocer%20sus%20sistemas%20empresariales."
                   target="_blank" class="btn-cta-outline">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
            </div>
        </div>
    </section>

    <!-- ================================================================
         CONTACT
    ================================================================ -->
    <section class="contact-section" id="contacto">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <div class="section-tag"><i class="fas fa-headset"></i> Contacto</div>
                <h2 class="section-title">Estamos para <span class="hl">ayudarte</span></h2>
                <p class="section-desc">Cuéntanos sobre tu proyecto. Respondemos a la brevedad posible.</p>
            </div>
            <div class="row g-4 justify-content-center">

                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="contact-card">
                        <div class="contact-icon" style="background:linear-gradient(135deg,#25D366,#128C7E)"><i class="fab fa-whatsapp"></i></div>
                        <h5>WhatsApp</h5>
                        <p>Escríbenos directamente para una respuesta rápida.</p>
                        <a href="https://wa.me/50377663903?text=Hola!%20Me%20interesa%20conocer%20sus%20sistemas%20empresariales." target="_blank" class="d-block mt-2">
                            +503 7766-3903
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="contact-card">
                        <div class="contact-icon" style="background:linear-gradient(135deg,var(--primary),var(--secondary))"><i class="fas fa-envelope"></i></div>
                        <h5>Correo Electrónico</h5>
                        <p>Envíanos tus consultas o propuestas de proyecto.</p>
                        <a href="mailto:cestrada_chacon@outlook.com" class="d-block mt-2">
                            cestrada_chacon@outlook.com
                        </a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="contact-card">
                        <div class="contact-icon" style="background:linear-gradient(135deg,var(--accent),#7c3aed)"><i class="fas fa-lock"></i></div>
                        <h5>Acceso al Sistema</h5>
                        <p>¿Ya eres cliente? Ingresa a tu panel de administración.</p>
                        <a href="#" class="d-block mt-2"
                           data-bs-toggle="modal" data-bs-target="#loginModal">
                            Iniciar sesión &rarr;
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <?= $this->renderSection('content') ?>

    <!-- ================================================================
         FOOTER
    ================================================================ -->
    <footer class="footer-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="brand-icon"><i class="fa-solid fa-cube"></i></div>
                        <div class="footer-brand-name">Carytel</div>
                    </div>
                    <p class="footer-brand-desc">Sistemas empresariales en la nube. Soluciones tecnológicas a medida con acceso internacional y soporte dedicado.</p>
                    <div class="footer-social">
                        <a href="https://wa.me/50377663903" target="_blank" class="social-icon" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        <a href="mailto:cestrada_chacon@outlook.com" class="social-icon" title="Email"><i class="fas fa-envelope"></i></a>
                        <a href="#" class="social-icon" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="footer-heading">Navegación</h6>
                    <ul class="footer-links">
                        <li><a href="#inicio"><i class="fas fa-chevron-right" style="font-size:.65rem;"></i> Inicio</a></li>
                        <li><a href="#servicios"><i class="fas fa-chevron-right" style="font-size:.65rem;"></i> Servicios</a></li>
                        <li><a href="#nosotros"><i class="fas fa-chevron-right" style="font-size:.65rem;"></i> Nosotros</a></li>
                        <li><a href="#proceso"><i class="fas fa-chevron-right" style="font-size:.65rem;"></i> Proceso</a></li>
                        <li><a href="#contacto"><i class="fas fa-chevron-right" style="font-size:.65rem;"></i> Contacto</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="footer-heading">Servicios</h6>
                    <ul class="footer-links">
                        <li><a href="#servicios"><i class="fas fa-chevron-right" style="font-size:.65rem;"></i> Gestión Empresarial</a></li>
                        <li><a href="#servicios"><i class="fas fa-chevron-right" style="font-size:.65rem;"></i> Control de Inventario</a></li>
                        <li><a href="#servicios"><i class="fas fa-chevron-right" style="font-size:.65rem;"></i> Punto de Venta</a></li>
                        <li><a href="#servicios"><i class="fas fa-chevron-right" style="font-size:.65rem;"></i> Reportes y Analytics</a></li>
                        <li><a href="#servicios"><i class="fas fa-chevron-right" style="font-size:.65rem;"></i> Logística y Rastreo</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="footer-heading">Contacto</h6>
                    <ul class="footer-links">
                        <li><a href="https://wa.me/50377663903" target="_blank"><i class="fab fa-whatsapp"></i> +503 7766-3903</a></li>
                        <li><a href="mailto:cestrada_chacon@outlook.com"><i class="fas fa-envelope"></i> cestrada_chacon@outlook.com</a></li>
                        <li><a href="#" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="fas fa-lock"></i> Acceso al Sistema</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <span>© <?= date('Y') ?> Carytel. Todos los derechos reservados.</span>
                <span>Sistemas empresariales en la nube</span>
            </div>
        </div>
    </footer>

    <!-- Scroll top -->
    <button class="scroll-top" id="scrollTop" title="Subir" onclick="window.scrollTo({top:0,behavior:'smooth'})">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- WhatsApp -->
    <a href="https://wa.me/50377663903?text=Hola!%20Me%20interesa%20conocer%20sus%20sistemas%20empresariales."
       target="_blank" class="whatsapp-float" title="Escríbenos por WhatsApp">
        <div class="pulse-ring"></div>
        <i class="fab fa-whatsapp"></i>
    </a>

    <?= $this->include('modals/login') ?>
    <?= $this->include('modals/reset1') ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    <script {csp-script-nonce}>
        AOS.init({ duration: 650, once: true, offset: 50, easing: 'ease-out-cubic' });

        // Navbar scroll
        const navbar = document.getElementById('mainNavbar');
        const scrollTopBtn = document.getElementById('scrollTop');
        window.addEventListener('scroll', () => {
            const y = window.scrollY;
            navbar.classList.toggle('scrolled', y > 60);
            scrollTopBtn.classList.toggle('visible', y > 350);
        });

        // Counter animation
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting && e.target.dataset.count) {
                    const target = +e.target.dataset.count;
                    let current = 0;
                    const inc = Math.ceil(target / 60);
                    const t = setInterval(() => {
                        current = Math.min(current + inc, target);
                        e.target.textContent = current + (e.target.dataset.suffix || '');
                        if (current >= target) clearInterval(t);
                    }, 25);
                    io.unobserve(e.target);
                }
            });
        }, { threshold: .5 });
        document.querySelectorAll('.stat-number[data-count]').forEach(el => io.observe(el));

        <?php if (session()->getFlashdata('alert')): ?>
            <?php $alert = session()->getFlashdata('alert'); ?>
            Swal.fire({
                icon: '<?= $alert['type'] ?>',
                title: '<?= $alert['title'] ?>',
                text: '<?= $alert['message'] ?>',
                toast: true, position: 'top-end',
                showConfirmButton: false, timer: 3500, timerProgressBar: true
            });
        <?php endif; ?>
    </script>

</body>
</html>
