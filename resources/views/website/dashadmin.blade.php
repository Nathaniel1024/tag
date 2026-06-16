<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <title>Admin Dashboard - DIGIBARANGAY</title>
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
          <a class="active" href="/dashboard"><span class="ico">🧭</span><span>Admin Dashboard</span></a>
          <a href="/barangay"><span class="ico">👤</span><span>Barangay Official</span></a>
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

      <section class="adm-content">
        <div class="adm-title">Admin Dashboard</div>
        <div class="adm-subtitle">Manage barangay officers and account access</div>

        <div class="adm-card" style="padding:1rem">
          <table id="staffTable" class="adm-table" aria-label="Staff accounts table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="5" class="empty-row">Loading staff accounts...</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>

  <div id="passwordModal" class="modal-overlay" hidden>
    <div class="modal" style="max-width:400px;">
      <button class="modal-close" id="passwordClose" type="button">✕</button>
      <div class="modal-header">
        <h2>Change Password</h2>
      </div>
      <div class="modal-body">
        <div style="margin-bottom:12px;color:#374151;font-size:14px;">Enter a new password for this account.</div>
        <input id="passwordInput" type="password" placeholder="New password" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;margin-bottom:12px;" />
        <input id="passwordConfirmInput" type="password" placeholder="Confirm new password" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;margin-bottom:12px;" />
        <div id="passwordError" style="color:#dc2626;font-size:14px;display:none;margin-bottom:10px;"></div>
        <button id="passwordSaveBtn" class="btn btn-approve" style="width:100%;">Save Password</button>
      </div>
    </div>
  </div>

  <script>
    async function loadStaff() {
      try {
        const res = await fetch('/api/officers', {
          headers: { 'Accept': 'application/json' }
        });
        if (!res.ok) {
          document.querySelector('#staffTable tbody').innerHTML = '<tr><td colspan="5" style="text-align:center;color:#c9302c">Failed to load staff accounts.</td></tr>';
          return;
        }
        const body = await res.json();
        const staff = body.data || [];
        if (staff.length === 0) {
          document.querySelector('#staffTable tbody').innerHTML = '<tr><td colspan="5" style="text-align:center;color:#666">No staff accounts yet.</td></tr>';
          return;
        }

        const rows = staff.map(s => `
          <tr>
            <td>${s.fullname || '-'}</td>
            <td>${s.username || '-'}</td>
            <td>${s.email || '-'}</td>
            <td>${s.contact || '-'}</td>
            <td>
              <button class="btn btn-edit" type="button" data-action="password" data-id="${s.id}">Change Password</button>
              <button class="btn btn-reject" type="button" data-action="delete" data-id="${s.id}">Delete</button>
            </td>
          </tr>
        `).join('');

        document.querySelector('#staffTable tbody').innerHTML = rows;
      } catch (err) {
        console.error('Load staff error:', err);
        document.querySelector('#staffTable tbody').innerHTML =
          '<tr><td colspan="5" class="error-row">Network error.</td></tr>';
      }
    }

    document.querySelector('#staffTable tbody').addEventListener('click', async (event) => {
      const button = event.target.closest('button[data-action]');
      if (!button) return;
      const action = button.dataset.action;
      const staffId = button.dataset.id;
      if (!staffId) return;

      if (action === 'delete') {
        if (!confirm('Delete this account? This cannot be undone.')) {
          return;
        }
        await deleteStaff(staffId);
        return;
      }

      if (action === 'password') {
        openPasswordModal(staffId);
      }
    });

    const passwordModal = document.getElementById('passwordModal');
    const passwordClose = document.getElementById('passwordClose');
    const passwordInput = document.getElementById('passwordInput');
    const passwordConfirmInput = document.getElementById('passwordConfirmInput');
    const passwordError = document.getElementById('passwordError');
    const passwordSaveBtn = document.getElementById('passwordSaveBtn');
    let selectedStaffId = null;

    function openPasswordModal(staffId) {
      selectedStaffId = staffId;
      passwordInput.value = '';
      passwordConfirmInput.value = '';
      passwordError.style.display = 'none';
      passwordModal.hidden = false;
      passwordModal.classList.add('open');
      passwordInput.focus();
    }

    function closePasswordModal() {
      selectedStaffId = null;
      passwordModal.hidden = true;
      passwordModal.classList.remove('open');
    }

    passwordClose.addEventListener('click', closePasswordModal);
    passwordModal.addEventListener('click', (event) => {
      if (event.target === passwordModal) {
        closePasswordModal();
      }
    });

    passwordSaveBtn.addEventListener('click', async () => {
      const password = passwordInput.value.trim();
      const passwordConfirm = passwordConfirmInput.value.trim();
      if (!password || password.length < 6) {
        passwordError.textContent = 'Password must be at least 6 characters.';
        passwordError.style.display = 'block';
        return;
      }
      if (password !== passwordConfirm) {
        passwordError.textContent = 'Passwords do not match.';
        passwordError.style.display = 'block';
        return;
      }
      if (!selectedStaffId) return;
      passwordSaveBtn.disabled = true;
      passwordSaveBtn.textContent = 'Saving...';
      try {
        await updateStaffPassword(selectedStaffId, password);
        closePasswordModal();
        alert('Password updated successfully.');
      } catch (err) {
        passwordError.textContent = err.message || 'Failed to update password.';
        passwordError.style.display = 'block';
      } finally {
        passwordSaveBtn.disabled = false;
        passwordSaveBtn.textContent = 'Save Password';
      }
    });

    async function deleteStaff(id) {
      try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const res = await fetch('/api/officers/' + encodeURIComponent(id), {
          method: 'DELETE',
          credentials: 'same-origin',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
        if (!res.ok) {
          const body = await res.json().catch(() => ({}));
          throw new Error(body.message || 'Failed to delete account.');
        }
        await loadStaff();
      } catch (err) {
        alert(err.message || 'Unable to delete account.');
      }
    }

    async function updateStaffPassword(id, password) {
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
      const res = await fetch('/api/officers/' + encodeURIComponent(id) + '/password', {
        method: 'PUT',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ password })
      });
      if (!res.ok) {
        const body = await res.json().catch(() => ({}));
        throw new Error(body.message || 'Failed to update password.');
      }
    }

    loadStaff();

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
