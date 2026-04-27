<!-- ================================================================
     MODAL DE LOGIN – Carytel
================================================================ -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered kinder-login-dialog">
        <div class="modal-content kinder-login-content">

            <!-- Botón cerrar flotante -->
            <button type="button"
                    class="kinder-close-btn"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar">
                <i class="fas fa-times"></i>
            </button>

            <!-- Panel izquierdo decorativo -->
            <div class="kinder-login-panel">
                <div class="kinder-login-panel-inner">
                    <div class="kinder-login-logo" style="font-size:2.5rem;background:rgba(255,255,255,.15);border-radius:16px;width:60px;height:60px;display:flex;align-items:center;justify-content:center;margin:0 auto .8rem;">
                        <i class="fa-solid fa-cube" style="font-size:1.5rem;"></i>
                    </div>
                    <h3>Carytel</h3>
                    <p>Portal de administración empresarial</p>
                    <div class="kinder-panel-shapes">
                        <div class="kp-shape kp-s1"></div>
                        <div class="kp-shape kp-s2"></div>
                        <div class="kp-shape kp-s3"></div>
                    </div>
                </div>
            </div>

            <!-- Panel derecho – formulario -->
            <div class="kinder-login-form-panel">
                <div class="kinder-form-header">
                    <h5 id="loginModalLabel">Bienvenido</h5>
                    <span>Ingresa tus credenciales para continuar</span>
                </div>

                <form id="loginForm" action="<?= base_url('/login') ?>" method="POST" novalidate>

                    <!-- Usuario -->
                    <div class="kinder-field">
                        <label for="username">Usuario o correo</label>
                        <div class="kinder-input-wrap">
                            <i class="fas fa-user kinder-field-icon"></i>
                            <input type="text"
                                   id="username"
                                   name="username"
                                   placeholder="usuario@ejemplo.com"
                                   autocomplete="username"
                                   required>
                        </div>
                    </div>

                    <!-- Contraseña -->
                    <div class="kinder-field">
                        <label for="password">Contraseña</label>
                        <div class="kinder-input-wrap">
                            <i class="fas fa-lock kinder-field-icon"></i>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   placeholder="••••••••"
                                   autocomplete="current-password"
                                   required>
                            <button type="button" id="togglePassword" class="kinder-eye-btn" tabindex="-1" aria-label="Ver contraseña">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Recordarme + Olvidé -->
                    <div class="kinder-remember-row">
                        <label class="kinder-check-label">
                            <input type="checkbox" id="remember" name="remember">
                            <span class="kinder-checkmark"></span>
                            Recordarme
                        </label>
                        <a href="#"
                           class="kinder-forgot"
                           data-bs-dismiss="modal"
                           data-bs-toggle="modal"
                           data-bs-target="#resetModal">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <!-- Error -->
                    <div class="kinder-error d-none" id="loginError" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <span class="kinder-error-text"></span>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="kinder-submit-btn" id="loginSubmitBtn">
                        <span class="kinder-btn-text">
                            <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                        </span>
                        <span class="kinder-btn-loading d-none">
                            <span class="kinder-spinner"></span> Verificando…
                        </span>
                    </button>

                </form>
            </div>

        </div>
    </div>
</div>

<!-- ================================================================
     ESTILOS DEL MODAL
================================================================ -->
<style>
    /* Dialog size */
    .kinder-login-dialog {
        max-width: 780px;
        margin: 1.5rem auto;
    }

    /* Card container – dos columnas */
    .kinder-login-content {
        display: flex;
        flex-direction: row;
        border: none;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 30px 80px rgba(0,0,0,.22), 0 0 0 1px rgba(255,255,255,.06);
        position: relative;
        min-height: 480px;
    }

    /* ── Close button ─────────────────────────── */
    .kinder-close-btn {
        position: absolute;
        top: 14px; right: 14px;
        z-index: 10;
        width: 36px; height: 36px;
        border-radius: 50%;
        background: rgba(255,255,255,.18);
        border: 1px solid rgba(255,255,255,.35);
        color: white;
        font-size: .9rem;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        backdrop-filter: blur(6px);
        transition: all .25s ease;
    }
    .kinder-close-btn:hover {
        background: rgba(255,255,255,.35);
        transform: rotate(90deg);
    }

    /* ── Decorative left panel ────────────────── */
    .kinder-login-panel {
        width: 42%;
        background: linear-gradient(160deg, #0f172a 0%, #1e3a8a 45%, #1a56db 100%);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .kinder-login-panel-inner {
        text-align: center;
        color: white;
        position: relative;
        z-index: 2;
        padding: 2rem 1.5rem;
    }

    .kinder-login-logo {
        font-size: 3.5rem;
        margin-bottom: .8rem;
        animation: float-logo 3s ease-in-out infinite;
    }
    @keyframes float-logo {
        0%,100% { transform: translateY(0);   }
        50%      { transform: translateY(-8px);}
    }

    .kinder-login-panel-inner h3 {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 1.7rem; font-weight: 800;
        margin-bottom: .4rem;
    }
    .kinder-login-panel-inner p {
        font-size: .85rem;
        opacity: .78;
        margin: 0;
        line-height: 1.5;
    }

    /* Animated shapes inside panel */
    .kinder-panel-shapes { position: absolute; inset: 0; pointer-events: none; }
    .kp-shape {
        position: absolute;
        border-radius: 50%;
        background: rgba(255,255,255,.1);
        animation: kp-float 5s ease-in-out infinite;
    }
    .kp-s1 { width:180px;height:180px; top:-60px; left:-60px; animation-delay:0s;   }
    .kp-s2 { width:120px;height:120px; bottom:-40px;right:-40px;animation-delay:1.5s;}
    .kp-s3 { width: 80px;height: 80px; bottom:30%; left:10%;   animation-delay:.8s; }
    @keyframes kp-float {
        0%,100% { transform: translate(0,0) scale(1); }
        50%      { transform: translate(6px,-12px) scale(1.06); }
    }

    /* ── Right form panel ─────────────────────── */
    .kinder-login-form-panel {
        flex: 1;
        padding: 2.5rem 2.2rem;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .kinder-form-header {
        margin-bottom: 1.8rem;
    }
    .kinder-form-header h5 {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 1.6rem; font-weight: 800;
        color: #1e293b;
        margin-bottom: .25rem;
    }
    .kinder-form-header span {
        font-size: .85rem;
        color: #94a3b8;
    }

    /* ── Fields ───────────────────────────────── */
    .kinder-field { margin-bottom: 1.2rem; }
    .kinder-field label {
        display: block;
        font-size: .8rem; font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: .6px;
        margin-bottom: .45rem;
    }

    .kinder-input-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }

    .kinder-field-icon {
        position: absolute;
        left: 14px;
        color: #1a56db;
        font-size: .95rem;
        pointer-events: none;
        transition: color .3s ease;
        z-index: 1;
    }

    .kinder-input-wrap input {
        width: 100%;
        padding: .75rem 3rem .75rem 2.6rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-family: 'Nunito', sans-serif;
        font-size: .95rem;
        color: #1e293b;
        background: #f8fafc;
        transition: all .25s ease;
        outline: none;
    }
    .kinder-input-wrap input:focus {
        border-color: #1a56db;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(26,86,219,.12);
    }
    .kinder-input-wrap input:focus + .kinder-field-icon,
    .kinder-input-wrap:focus-within .kinder-field-icon {
        color: #1e40af;
    }
    .kinder-input-wrap input::placeholder { color: #cbd5e1; }

    /* Eye toggle */
    .kinder-eye-btn {
        position: absolute;
        right: 10px;
        background: none;
        border: none;
        padding: .3rem .5rem;
        color: #94a3b8;
        cursor: pointer;
        font-size: .9rem;
        transition: color .2s ease;
        border-radius: 8px;
    }
    .kinder-eye-btn:hover { color: #1a56db; background: rgba(26,86,219,.08); }

    /* ── Remember + forgot ────────────────────── */
    .kinder-remember-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.2rem;
        flex-wrap: wrap;
        gap: .5rem;
    }

    .kinder-check-label {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-size: .85rem;
        color: #64748b;
        cursor: pointer;
        user-select: none;
    }
    .kinder-check-label input[type="checkbox"] {
        width: 16px; height: 16px;
        accent-color: #1a56db;
        cursor: pointer;
    }

    .kinder-forgot {
        font-size: .82rem;
        color: #1a56db;
        text-decoration: none;
        font-weight: 600;
        transition: color .2s ease;
    }
    .kinder-forgot:hover { color: #1e40af; text-decoration: underline; }

    /* ── Error ────────────────────────────────── */
    .kinder-error {
        display: flex;
        align-items: center;
        gap: .6rem;
        background: #fff1f2;
        border: 1px solid #fecdd3;
        border-radius: 10px;
        padding: .7rem 1rem;
        font-size: .85rem;
        color: #be123c;
        margin-bottom: 1.2rem;
    }
    .kinder-error i { font-size: 1rem; flex-shrink: 0; }

    /* ── Submit button ────────────────────────── */
    .kinder-submit-btn {
        width: 100%;
        padding: .85rem;
        border: none;
        border-radius: 14px;
        background: linear-gradient(135deg, #1a56db, #0ea5e9);
        color: white;
        font-family: 'Inter', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        cursor: pointer;
        transition: all .3s ease;
        box-shadow: 0 6px 20px rgba(118,75,162,.35);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        position: relative;
        overflow: hidden;
    }
    .kinder-submit-btn::before {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(135deg, #1e40af, #0891b2);
        opacity: 0;
        transition: opacity .35s ease;
    }
    .kinder-submit-btn:hover::before { opacity: 1; }
    .kinder-submit-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(26,86,219,.4); }
    .kinder-submit-btn:active { transform: translateY(0); }
    .kinder-submit-btn .kinder-btn-text,
    .kinder-submit-btn .kinder-btn-loading { position: relative; z-index: 1; display: flex; align-items: center; }

    /* Spinner */
    .kinder-spinner {
        width: 16px; height: 16px;
        border: 2px solid rgba(255,255,255,.35);
        border-top-color: white;
        border-radius: 50%;
        animation: ks-spin .7s linear infinite;
        display: inline-block;
        margin-right: .4rem;
    }
    @keyframes ks-spin { to { transform: rotate(360deg); } }

    /* ── Modal backdrop ───────────────────────── */
    .modal-backdrop {
        background-color: rgba(15,23,42,.65);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
    }
    .modal-backdrop.show { opacity: 1; }

    /* ── Responsive: stack on mobile ─────────── */
    @media (max-width: 600px) {
        .kinder-login-dialog  { margin: .75rem; max-width: calc(100% - 1.5rem); }
        .kinder-login-content { flex-direction: column; min-height: unset; }
        .kinder-login-panel   { width: 100%; padding: 2rem 1.5rem; min-height: 160px; border-radius: 0; }
        .kinder-login-panel-inner h3   { font-size: 1.3rem; }
        .kinder-login-panel-inner p    { display: none; }
        .kp-s1,.kp-s2,.kp-s3          { display: none; }
        .kinder-login-form-panel { padding: 1.8rem 1.5rem; }
        .kinder-close-btn { background: rgba(0,0,0,.18); color: white; border-color: rgba(0,0,0,.1); top: 10px; right: 10px; }
    }
</style>

<!-- ================================================================
     LÓGICA DEL FORMULARIO (sin cambios respecto al original)
================================================================ -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Toggle de contraseña ────────────────────────────────────────
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput  = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
        const isPassword = passwordInput.getAttribute('type') === 'password';
        passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    // ── Submit con AJAX ─────────────────────────────────────────────
    const loginForm    = document.getElementById('loginForm');
    const submitBtn    = document.getElementById('loginSubmitBtn');
    const btnText      = submitBtn.querySelector('.kinder-btn-text');
    const btnLoading   = submitBtn.querySelector('.kinder-btn-loading');
    const errorDiv     = document.getElementById('loginError');
    const errorText    = errorDiv.querySelector('.kinder-error-text');

    loginForm.addEventListener('submit', function (e) {
        e.preventDefault();

        // Estado de carga
        btnText.classList.add('d-none');
        btnLoading.classList.remove('d-none');
        submitBtn.disabled = true;
        errorDiv.classList.add('d-none');

        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            // ── Actualizar token CSRF dinámicamente ──────────────
            if (data.csrf) {
                let csrfInput = loginForm.querySelector('input[name="' + data.csrf.tokenName + '"]');
                if (!csrfInput) {
                    loginForm.querySelectorAll('input[name^="<?= csrf_token() ?>"]').forEach(el => el.remove());
                    csrfInput = document.createElement('input');
                    csrfInput.type  = 'hidden';
                    csrfInput.name  = data.csrf.tokenName;
                    loginForm.appendChild(csrfInput);
                }
                csrfInput.value = data.csrf.hash;
            }

            // ── Procesar respuesta ───────────────────────────────
            if (data.success) {
                errorDiv.classList.add('d-none');
                window.location.href = data.redirect || '<?= base_url() ?>';
            } else {
                errorText.textContent = data.message || 'Credenciales incorrectas. Intenta de nuevo.';
                errorDiv.classList.remove('d-none');
                // Shake animation
                loginForm.style.animation = 'none';
                loginForm.offsetHeight; // reflow
                loginForm.style.animation = 'login-shake .4s ease';
            }
        })
        .catch(() => {
            errorText.textContent = 'Error de conexión. Verifica tu red e intenta de nuevo.';
            errorDiv.classList.remove('d-none');
        })
        .finally(() => {
            btnText.classList.remove('d-none');
            btnLoading.classList.add('d-none');
            submitBtn.disabled = false;
        });
    });
});
</script>

<style>
    @keyframes login-shake {
        0%,100% { transform: translateX(0);   }
        20%,60% { transform: translateX(-6px); }
        40%,80% { transform: translateX( 6px); }
    }
</style>
