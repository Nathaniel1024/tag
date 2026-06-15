<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>High Admin Dashboard</title>
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
  <style>
    body{margin:0;background:#f5f5f5}
    .layout{display:flex;min-height:100vh}
    .sidebar{width:300px;background:#0f5668;color:#fff;padding:20px;box-sizing:border-box;display:flex;flex-direction:column}
    .brand{display:flex;gap:12px;align-items:flex-start;margin-bottom:30px}
    .brand img{height:50px;width:50px}
    .brand-text strong{display:block;font-size:15px;line-height:1.2}
    .brand-text small{display:block;font-size:12px;opacity:0.8}
    .nav{flex:1}
    .nav a{display:flex;gap:12px;align-items:center;padding:12px;color:#fff;text-decoration:none;border-radius:6px;margin-bottom:8px;font-size:14px}
    .nav a.active{background:rgba(0,0,0,0.3)}
    .logout-btn{display:flex;gap:12px;align-items:center;padding:12px;color:#fff;text-decoration:none;border:none;background:none;cursor:pointer;border-top:1px solid rgba(255,255,255,0.2);font-size:14px;width:100%}
    .main{flex:1;display:flex;flex-direction:column}
    .topbar{background:#7fa8b4;color:#fff;padding:15px 30px;display:flex;justify-content:space-between;align-items:center}
    .topbar-title strong{display:block;font-size:15px;font-weight:700}
    .topbar-title span{display:block;font-size:12px;opacity:0.9}
    .top-icons span{margin-left:15px;font-size:18px;cursor:pointer}
    .content{flex:1;padding:40px;overflow-y:auto}
    .card{background:#fff;padding:30px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1)}
    table{width:100%;border-collapse:collapse}
    thead tr{background:#0b66c2;color:#fff}
    th{padding:14px;text-align:left;font-weight:600;font-size:14px}
    td{padding:14px;border-bottom:1px solid #eee;font-size:14px}
    .btn{padding:6px 12px;border-radius:4px;border:none;cursor:pointer;font-weight:600;font-size:12px;margin-right:4px}
    .btn-view{background:#6c757d;color:#fff}
    .btn-edit{background:#ffc107;color:#333}
    .btn-approve{background:#28a745;color:#fff}
    .btn-reject{background:#dc3545;color:#fff}
    #passwordModal .modal-body{overflow:visible;}
    #passwordModal .modal{overflow:visible;}
  </style>
</head>
<body>
  <div class="layout">
    <!-- SIDEBAR -->
    <aside class="sidebar">
      <div class="brand">
        <img src="{{ asset('img/logo_zed.png') }}" alt="DIGIBARANGAY" />
        <div class="brand-text">
          <strong>DIGIBARANGAY</strong>
          <small>Smart Clearance System</small>
        </div>
      </div>

      @php
        $role = session('admin_role');
        $isAdmin = $role === 'admin';
        $isOfficial = $role === 'official';
      @endphp
      <nav class="nav">
        <!-- Dashboard visible to both admin and official -->
        <a href="/dashs"><span class="ico">🏠</span><span>Dashboard</span></a>

        <!-- Certificate, Residents and Resident Accounts visible to both roles -->
        <a href="/certificate"><span class="ico">📄</span><span>Certificate Template</span></a>
        <a href="/resident"><span class="ico">👥</span><span>Residents record</span></a>
        <a href="/rest-acc"><span class="ico">🔐</span><span>Resident Accounts</span></a>

        <!-- Admin-only links -->
        @if ($isAdmin)
          <a class="active" href="/dashboard"><span class="ico">🧭</span><span>Admin Dashboard</span></a>
          <a href="/barangay"><span class="ico">👤</span><span>Barangay Official</span></a>
        @endif
      </nav>

      <button class="logout-btn">
        <span>⎋</span><span>Logout</span>
      </button>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main">
      <header class="topbar">
        <div class="topbar-title">
          <strong>CHAIRMAN</strong>
          <span>Barangay Administrator</span>
        </div>
        <div class="top-icons">
          <span>👤</span>
          <span>🔔</span>
        </div>
      </header>

      <section class="content">
        <div class="card">
          <table id="staffTable">
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
      <button class="modal-close" id="passwordClose">✕</button>
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
    // Load staff accounts from backend
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

        // parse response JSON once
        // (body/staff already obtained above)

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

    // Attach actions for delete and password
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

    // Load on page load
    loadStaff();
  </script>
</body>
</html>
