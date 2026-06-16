<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <title>Barangay Official - DIGIBARANGAY</title>
  <link rel="icon" type="image/png" href="{{ asset('img/logo_zed.png') }}" />
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
  <style>
    .admin-dashboard {
      position: relative;
    }

    .admin-dashboard::before {
      content: '';
      position: fixed;
      left: 50%;
      top: 54%;
      width: 460px;
      height: 460px;
      transform: translate(-50%, -50%);
      background: url('{{ asset('img/Barangay Official Logo.png') }}') center/contain no-repeat;
      opacity: .07;
      filter: grayscale(100%) blur(2px);
      pointer-events: none;
      z-index: 0;
    }

    .adm-layout {
      position: relative;
      z-index: 1;
    }

    .barangay-page {
      padding: 1rem 1rem 1.5rem;
    }

    .barangay-page-inner {
      width: 100%;
    }

    .barangay-form-shell {
      width: 100%;
      max-width: none;
    }

    .barangay-form-card {
      padding: 0;
      overflow: hidden;
    }

    .barangay-form-head {
      background: linear-gradient(180deg, #0b66c3 0%, #2563eb 100%);
      color: #fff;
      padding: 1.25rem 1.5rem 1.4rem;
    }

    .barangay-form-head h3 {
      margin: 0;
      font-size: 1.45rem;
      font-weight: 900;
      letter-spacing: .01em;
    }

    .barangay-form-head p {
      margin: .45rem 0 0;
      color: rgba(255, 255, 255, .92);
      line-height: 1.6;
      font-size: .98rem;
    }

    .barangay-form-body {
      padding: 2rem;
    }

    .barangay-banner {
      margin-bottom: 1rem;
      padding: .9rem 1rem;
      border-radius: 14px;
      background: linear-gradient(180deg, #eff6ff 0%, #ffffff 100%);
      border: 1px solid #dbeafe;
      color: #1e3a8a;
      font-weight: 700;
      box-shadow: 0 8px 18px rgba(37, 99, 235, .08);
    }

    .barangay-alert {
      border-radius: 12px;
      padding: .85rem 1rem;
      margin-bottom: .9rem;
      display: none;
    }

    .barangay-alert.success {
      background: #e6f9ef;
      color: #064e3b;
    }

    .barangay-alert.error {
      background: #ffecec;
      color: #c9302c;
    }

    .barangay-form {
      display: grid;
      gap: 1rem;
    }

    .barangay-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 1.15rem;
    }

    .barangay-field {
      display: grid;
      gap: .45rem;
    }

    .barangay-field.full {
      grid-column: 1 / -1;
    }

    .barangay-field label {
      margin: 0;
      font-weight: 800;
      font-size: .92rem;
      color: #0f172a;
    }

    .barangay-field input,
    .barangay-field select {
      width: 100%;
      padding: .95rem 1rem;
      border: 1px solid #dbe3ee;
      border-radius: 14px;
      background: #f8fafc;
      font-size: .98rem;
      outline: none;
      transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
    }

    .barangay-field input:focus,
    .barangay-field select:focus {
      border-color: #60a5fa;
      background: #fff;
      box-shadow: 0 0 0 4px rgba(96, 165, 250, .14);
    }

    .barangay-actions {
      display: flex;
      justify-content: flex-end;
      margin-top: .25rem;
    }

    .barangay-actions .create-btn {
      min-width: 180px;
      border-radius: 14px;
      padding: 1rem 1.2rem;
      font-size: 1rem;
      box-shadow: 0 14px 26px rgba(11, 102, 195, .18);
    }

    @media (max-width: 720px) {
      .barangay-form-shell {
        max-width: 100%;
      }

      .barangay-form-body {
        padding: 1rem;
      }

      .barangay-form-head {
        padding: 1rem 1rem 1.1rem;
      }

      .barangay-form-head h3 {
        font-size: 1.2rem;
      }

      .barangay-grid {
        grid-template-columns: 1fr;
      }

      .barangay-actions {
        justify-content: stretch;
      }

      .barangay-actions .create-btn {
        width: 100%;
        min-width: 0;
      }
    }

    @media (min-width: 1100px) {
      .barangay-page {
        padding-left: 1.5rem;
        padding-right: 1.5rem;
      }

      .barangay-form-body {
        padding: 2.25rem;
      }
    }
  </style>
</head>
<body class="admin-dashboard">
  <div class="adm-layout">
    <button class="adm-sidebar-overlay" id="admSidebarOverlay" type="button" aria-label="Close menu"></button>
    <aside class="adm-sidebar">
      <div class="adm-brand">
        <img src="{{ asset('img/logo_zed.png') }}" alt="DIGIBARANGAY logo" />
        <div>
          <strong>DIGIBARANGAY</strong>
          <small>Smart Clearance System</small>
        </div>
      </div>

      @php($isAdmin = session('admin_role') === 'admin')
      <nav class="adm-nav" id="admSidebarNav" aria-label="Admin navigation">
        <a href="/dashs"><span class="ico">🏠</span><span>Dashboard</span></a>
        <a href="/certificate"><span class="ico">📄</span><span>Certificate Template</span></a>
        <a href="/resident"><span class="ico">👥</span><span>Resident Records</span></a>
        <a href="/rest-acc"><span class="ico">🔐</span><span>Resident Accounts</span></a>
        @if ($isAdmin)
          <a href="/dashboard"><span class="ico">🧭</span><span>Admin Dashboard</span></a>
          <a class="active" href="/barangay"><span class="ico">👤</span><span>Barangay Official</span></a>
        @endif
      </nav>

      <div class="adm-sidebar-footer">
        <button class="adm-logout" type="button" id="adminLogout">
          <span class="ico">⎋</span><span>Logout</span>
        </button>
      </div>
    </aside>

    <main class="adm-main">
      <header class="adm-topbar">
        <button class="adm-menu-toggle" id="admMenuToggle" type="button" aria-label="Toggle menu" aria-expanded="false" aria-controls="admSidebarNav">
          <span class="bars" aria-hidden="true"><span></span><span></span><span></span></span>
        </button>
        <div class="role">
          <strong>CHAIRMAN</strong>
          <span>Barangay Administrator</span>
        </div>
        <div class="top-icons">
          <span class="bubble" title="Profile">👤</span>
          <span class="bubble" title="Notifications">🔔</span>
        </div>
      </header>

      <section class="adm-content barangay-page">
        <div class="barangay-page-inner">
          <div class="adm-title">Barangay Official</div>
          <div class="adm-subtitle">Create and manage barangay officer accounts</div>

          <div class="adm-card barangay-form-shell barangay-form-card">
            <div class="barangay-form-head">
              <h3>Create Barangay Official</h3>
              <p>Add a new barangay official account. The form adapts to mobile and desktop widths.</p>
            </div>
            <div class="barangay-form-body">
              <div id="msgSuccess" class="barangay-alert success"></div>
              <div id="msgError" class="barangay-alert error"></div>

              <div class="barangay-banner">
                Fill in the official's details below. All required fields are marked by the browser.
              </div>

              <form id="createForm" class="barangay-form">
                <div class="barangay-field full">
                  <label>Full name</label>
                  <input name="fullname" required />
                </div>

                <div class="barangay-grid">
                  <div class="barangay-field">
                    <label>Email</label>
                    <input name="email" type="email" placeholder="Email" required />
                  </div>
                  <div class="barangay-field">
                    <label>Username</label>
                    <input name="username" placeholder="Username" required />
                  </div>
                </div>

                <div class="barangay-grid">
                  <div class="barangay-field">
                    <label>Password</label>
                    <input name="password" type="password" minlength="6" placeholder="Password" required />
                  </div>
                  <div class="barangay-field">
                    <label>Confirm Password</label>
                    <input name="passwordConfirm" type="password" minlength="6" placeholder="Confirm Password" required />
                  </div>
                </div>

                <div class="barangay-grid">
                  <div class="barangay-field">
                    <label>Contact number</label>
                    <input name="contact" placeholder="09XXXXXXXXX" />
                  </div>
                  <div class="barangay-field">
                    <label>Role</label>
                    <select name="role">
                      <option value="admin" selected>Admin</option>
                      <option value="official">Official</option>
                    </select>
                  </div>
                </div>

                <div class="barangay-field full">
                  <label>Complete address</label>
                  <input name="address" />
                </div>

                <div class="barangay-actions">
                  <button type="submit" class="create-btn">Create Account</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>

  <script>
    document.getElementById('adminLogout').addEventListener('click', async () => {
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

      try {
        await fetch('/loginadmin/logout', {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
      } catch (err) {
        console.error('Logout error', err);
      }

      window.location.replace('/');
    });

    window.addEventListener('pageshow', (event) => {
      if (event.persisted) {
        window.location.reload();
      }
    });

    document.getElementById('createForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const msgError = document.getElementById('msgError');
      const msgSuccess = document.getElementById('msgSuccess');
      const submitBtn = e.target.querySelector('button[type="submit"]');
      msgError.style.display = 'none';
      msgSuccess.style.display = 'none';

      const f = e.target;
      const data = {
        username: f.username.value.trim(),
        email: f.email.value.trim(),
        password: f.password.value,
        password_confirmation: f.passwordConfirm.value,
        fullname: f.fullname.value.trim(),
        contact: f.contact.value.trim() || null,
        address: f.address.value.trim() || null,
        role: f.role.value || 'admin'
      };

      if (!data.username || !data.email || !data.password) {
        msgError.textContent = 'Please fill required fields.';
        msgError.style.display = 'block';
        return;
      }
      if (data.password !== data.password_confirmation) {
        msgError.textContent = 'Passwords do not match.';
        msgError.style.display = 'block';
        return;
      }

      try {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Saving...';

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const res = await fetch('/api/officers/register', {
          method: 'POST',
          credentials: 'same-origin',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify(data)
        });
        let body = {};
        try {
          body = await res.json();
        } catch (e) {
          body = {};
        }
        if (res.ok) {
          msgSuccess.textContent = 'Staff account created successfully!';
          msgSuccess.style.display = 'block';
          f.reset();
          setTimeout(() => {
            window.location.href = '/dashs';
          }, 1500);
        } else {
          let errMsg = body.message || body.error || 'Failed to create account.';
          if (body.errors && typeof body.errors === 'object') {
            errMsg = Object.values(body.errors).flat().join(' ');
          }
          msgError.textContent = errMsg;
          msgError.style.display = 'block';
        }
      } catch (err) {
        console.error('create admin err', err);
        msgError.textContent = 'Network error. Make sure backend is running.';
        msgError.style.display = 'block';
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Create Account';
      }
    });

    const admMenuToggle = document.getElementById('admMenuToggle');
    const admSidebarOverlay = document.getElementById('admSidebarOverlay');
    const admNav = document.querySelector('.adm-nav');
    function setMobileMenu(open) {
      document.body.classList.toggle('adm-menu-open', !!open);
      if (admMenuToggle) admMenuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    }
    if (admMenuToggle) {
      admMenuToggle.addEventListener('click', () => {
        const willOpen = !document.body.classList.contains('adm-menu-open');
        setMobileMenu(willOpen);
      });
    }
    if (admSidebarOverlay) {
      admSidebarOverlay.addEventListener('click', () => setMobileMenu(false));
    }
    if (admNav) {
      admNav.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
          if (window.innerWidth <= 720) setMobileMenu(false);
        });
      });
    }
    window.addEventListener('resize', () => {
      if (window.innerWidth > 720) setMobileMenu(false);
    });
  </script>
</body>
</html>
