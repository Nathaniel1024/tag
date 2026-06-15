<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <title>High Admin Dashboard</title>
  <link rel="icon" type="image/png" href="{{ asset('img/logo_zed.png') }}" />
  <link rel="stylesheet" href="./styles.css" />
  <style>
    /* LAYOUT */
    body {
      margin: 0;
      background: #f5f5f5;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
    
    .layout {
      display: flex;
      min-height: 100vh;
    }
    
    /* SIDEBAR */
    .sidebar {
      width: 300px;
      background: #0f5668;
      color: #fff;
      padding: 20px;
      box-sizing: border-box;
      display: flex;
      flex-direction: column;
    }
    
    .brand {
      display: flex;
      gap: 12px;
      align-items: flex-start;
      margin-bottom: 30px;
    }
    
    .brand img {
      height: 50px;
      width: 50px;
    }
    
    .brand-text strong {
      display: block;
      font-size: 15px;
      line-height: 1.2;
      font-weight: 700;
    }
    
    .brand-text small {
      display: block;
      font-size: 12px;
      opacity: 0.8;
      margin-top: 4px;
    }
    
    .nav {
      flex: 1;
    }
    
    .nav a {
      display: flex;
      gap: 12px;
      align-items: center;
      padding: 12px;
      color: #fff;
      text-decoration: none;
      border-radius: 6px;
      margin-bottom: 8px;
      font-size: 14px;
      transition: background 0.2s;
    }
    
    .nav a:hover {
      background: rgba(255, 255, 255, 0.15);
    }
    
    .nav a.active {
      background: rgba(0, 0, 0, 0.3);
    }
    
    .logout-btn {
      display: flex;
      gap: 12px;
      align-items: center;
      padding: 12px;
      color: #fff;
      text-decoration: none;
      border: none;
      background: none;
      cursor: pointer;
      border-top: 1px solid rgba(255, 255, 255, 0.2);
      font-size: 14px;
      width: 100%;
      font-weight: 600;
      transition: background 0.2s;
    }
    
    .logout-btn:hover {
      background: rgba(255, 255, 255, 0.1);
    }
    
    /* MAIN CONTENT */
    .main {
      flex: 1;
      display: flex;
      flex-direction: column;
    }
    
    .topbar {
      background: #7fa8b4;
      color: #fff;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .topbar-title strong {
      display: block;
      font-size: 15px;
      font-weight: 700;
    }
    
    .topbar-title span {
      display: block;
      font-size: 12px;
      opacity: 0.9;
      margin-top: 4px;
    }
    
    .top-icons span {
      margin-left: 15px;
      font-size: 18px;
      cursor: pointer;
    }
    
    .content {
      flex: 1;
      padding: 40px;
      overflow-y: auto;
    }
    
    .card {
      background: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    /* TABLE STYLES */
    table {
      width: 100%;
      border-collapse: collapse;
    }
    
    thead tr {
      background: #0b66c2;
      color: #fff;
    }
    
    th {
      padding: 14px;
      text-align: left;
      font-weight: 600;
      font-size: 14px;
    }
    
    th:last-child {
      text-align: center;
    }
    
    td {
      padding: 14px;
      border-bottom: 1px solid #eee;
      font-size: 14px;
    }
    
    td:last-child {
      text-align: center;
    }
    
    tbody tr:hover {
      background: #f9f9f9;
    }
    
    /* BUTTONS */
    .btn {
      padding: 8px 14px;
      border-radius: 4px;
      border: none;
      cursor: pointer;
      font-weight: 600;
      font-size: 12px;
      margin-right: 6px;
      transition: all 0.2s;
      display: inline-block;
    }
    
    .btn:last-child {
      margin-right: 0;
    }
    
    .btn-view {
      background: #6c757d;
      color: #fff;
    }
    
    .btn-view:hover {
      background: #5a6268;
    }
    
    .btn-edit {
      background: #ffc107;
      color: #333;
    }
    
    .btn-edit:hover {
      background: #e0a800;
    }
    
    .btn-approve {
      background: #28a745;
      color: #fff;
    }
    
    .btn-approve:hover {
      background: #218838;
    }
    
    .btn-reject {
      background: #dc3545;
      color: #fff;
    }
    
    .btn-reject:hover {
      background: #c82333;
    }
    
    .btn-change-pw {
      background: #0b66c2;
      color: #fff;
    }
    
    .btn-change-pw:hover {
      background: #084a99;
    }
    
    .btn-delete {
      background: #dc3545;
      color: #fff;
    }
    
    .btn-delete:hover {
      background: #c82333;
    }
    
    /* STATUS INDICATORS */
    .status-indicator {
      display: inline-block;
      width: 12px;
      height: 12px;
      border-radius: 50%;
      margin: 0 auto;
      vertical-align: middle;
    }
    
    .status-online {
      background: #28a745;
    }
    
    .status-offline {
      background: #dc3545;
    }
    
    .status-cell {
      text-align: center;
    }
    
    .status-label {
      font-size: 13px;
      color: #374151;
      margin-left: 8px;
      vertical-align: middle;
    }
    
    /* MODALS */
    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.45);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 1000;
    }
    
    .modal-overlay.show {
      display: flex;
    }
    
    .modal-box {
      background: #fff;
      width: min(92vw, 420px);
      border-radius: 10px;
      box-shadow: 0 12px 28px rgba(0, 0, 0, 0.25);
      padding: 20px 18px;
      text-align: center;
      animation: slideUp 0.3s ease-out;
    }
    
    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .modal-box h3 {
      margin: 0 0 10px;
      color: #0f5668;
      font-size: 18px;
    }
    
    .modal-box p {
      margin: 0 0 16px;
      color: #374151;
      font-size: 14px;
    }
    
    .modal-ok {
      background: #0b66c2;
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 9px 18px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s;
    }
    
    .modal-ok:hover {
      background: #084a99;
    }
    
    .modal-input {
      width: 100%;
      box-sizing: border-box;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 6px;
      border: 1px solid #ccc;
      display: block;
      font-size: 14px;
      font-family: inherit;
    }
    
    .modal-input:focus {
      outline: none;
      border-color: #0b66c2;
      box-shadow: 0 0 0 3px rgba(11, 102, 194, 0.1);
    }
    
    .empty-row {
      text-align: center;
      color: #666;
      padding: 40px 14px;
    }
    
    .error-row {
      text-align: center;
      color: #c9302c;
      padding: 40px 14px;
      font-weight: 500;
    }
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

      <nav class="nav">
        <a href="/dashs"><span>🏠</span><span>Dashboard</span></a>
        <a href="/certificate"><span>📄</span><span>Certificate Template</span></a>
        <a href="/resident"><span>👥</span><span>Residents record</span></a>
        <a href="/rest-acc"><span>🔐</span><span>Resident Accounts</span></a>
        <a class="active" href="/dashboard"><span>🧭</span><span>Admin Dashboard</span></a>
        <a href="/barangay"><span>👤</span><span>Barangay Official</span></a>
      </nav>

      <button class="logout-btn" id="logoutBtn">
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

  <!-- MODALS -->
  <div id="deleteSuccessModal" class="modal-overlay" aria-hidden="true">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="deleteSuccessTitle">
      <h3 id="deleteSuccessTitle">Account Deleted</h3>
      <p id="deleteSuccessMessage">Account has been deleted successfully.</p>
      <button type="button" class="modal-ok" id="deleteSuccessOk">OK</button>
    </div>
  </div>

  <div id="confirmDeleteModal" class="modal-overlay" aria-hidden="true">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="confirmDeleteTitle">
      <h3 id="confirmDeleteTitle">Confirm Delete</h3>
      <p id="confirmDeleteMessage">Do you want to delete this account?</p>
      <button class="btn btn-delete" id="confirmYes">Yes</button>
      <button class="btn btn-view" id="confirmNo">No</button>
    </div>
  </div>

  <div id="changePasswordModal" class="modal-overlay" aria-hidden="true">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="changePasswordTitle">
      <h3 id="changePasswordTitle">Change Password</h3>
      <p id="changePasswordEmail">Enter new password</p>
      <input
        type="password"
        id="newPasswordInput"
        class="modal-input"
        placeholder="Enter new password"
        autocomplete="new-password"
      />
      <button class="btn btn-approve" id="savePasswordBtn">Save</button>
      <button class="btn btn-view" id="cancelPasswordBtn">Cancel</button>
    </div>
  </div>

  <script>
    // ============================================================================
    // CONFIGURATION & STATE
    // ============================================================================
    const CONFIG = {
      apiBaseUrl: '{{ url("/api") }}',
      apiOfficersUrl: '{{ url("/api/officers") }}',
      apiUpdateLastSeenUrl: '{{ url("/api/officers/update-last-seen") }}',
      logoutUrl: '{{ url("/loginadmin/logout") }}',
      refreshInterval: 15000
    };

    const STATE = {
      selectedDeleteId: null,
      selectedDeleteName: null,
      selectedUserId: null,
      selectedUserEmail: null
    };

    // Helper URLs
    const getOfficerUrl = (id) => `${CONFIG.apiBaseUrl}/officers/${id}`;
    const getPasswordUrl = (id) => `${CONFIG.apiBaseUrl}/officers/${id}/password`;

    // ============================================================================
    // UTILITY FUNCTIONS
    // ============================================================================
    function getCsrfToken() {
      return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    function escapeHtml(text) {
      const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
      };
      return text.replace(/[&<>"']/g, m => map[m]);
    }

    // ============================================================================
    // MODAL FUNCTIONS
    // ============================================================================
    function showSuccessModal(title, message) {
      const modal = document.getElementById('deleteSuccessModal');
      const titleEl = document.getElementById('deleteSuccessTitle');
      const msgEl = document.getElementById('deleteSuccessMessage');
      
      titleEl.textContent = title || 'Success';
      msgEl.textContent = message || 'Operation completed successfully.';
      modal.classList.add('show');
      modal.setAttribute('aria-hidden', 'false');
    }

    function hideDeleteSuccessModal() {
      const modal = document.getElementById('deleteSuccessModal');
      modal.classList.remove('show');
      modal.setAttribute('aria-hidden', 'true');
    }

    function showConfirmDeleteModal(id, name) {
      STATE.selectedDeleteId = id;
      STATE.selectedDeleteName = name;
      document.getElementById('confirmDeleteMessage').textContent = `Do you want to delete ${name}?`;
      
      const modal = document.getElementById('confirmDeleteModal');
      modal.classList.add('show');
      modal.setAttribute('aria-hidden', 'false');
    }

    function hideConfirmDeleteModal() {
      const modal = document.getElementById('confirmDeleteModal');
      modal.classList.remove('show');
      modal.setAttribute('aria-hidden', 'true');
    }

    function showChangePasswordModal(id, email) {
      STATE.selectedUserId = id;
      STATE.selectedUserEmail = email;
      document.getElementById('changePasswordEmail').textContent = `Change password for ${email}`;
      document.getElementById('newPasswordInput').value = '';
      
      const modal = document.getElementById('changePasswordModal');
      modal.classList.add('show');
      modal.setAttribute('aria-hidden', 'false');
    }

    function hideChangePasswordModal() {
      const modal = document.getElementById('changePasswordModal');
      modal.classList.remove('show');
      modal.setAttribute('aria-hidden', 'true');
    }

    // ============================================================================
    // MODAL EVENT LISTENERS
    // ============================================================================
    document.getElementById('deleteSuccessOk').addEventListener('click', hideDeleteSuccessModal);
    
    document.getElementById('deleteSuccessModal').addEventListener('click', (e) => {
      if (e.target.id === 'deleteSuccessModal') hideDeleteSuccessModal();
    });

    document.getElementById('confirmNo').addEventListener('click', hideConfirmDeleteModal);
    
    document.getElementById('confirmDeleteModal').addEventListener('click', (e) => {
      if (e.target.id === 'confirmDeleteModal') hideConfirmDeleteModal();
    });

    document.getElementById('cancelPasswordBtn').addEventListener('click', hideChangePasswordModal);
    
    document.getElementById('changePasswordModal').addEventListener('click', (e) => {
      if (e.target.id === 'changePasswordModal') hideChangePasswordModal();
    });

    // ============================================================================
    // API FUNCTIONS
    // ============================================================================
    async function updateLastSeen() {
      try {
        const csrfToken = getCsrfToken();
        await fetch(CONFIG.apiUpdateLastSeenUrl, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          credentials: 'same-origin'
        });
      } catch (err) {
        console.warn('Update last seen error:', err);
      }
    }

    async function loadStaff() {
      updateLastSeen();

      try {
        const csrfToken = getCsrfToken();
        const res = await fetch(CONFIG.apiOfficersUrl, {
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          credentials: 'same-origin'
        });

        if (!res.ok) {
          let errorText = 'Unknown error';
          try {
            errorText = await res.text();
          } catch (e) {
            errorText = 'Unknown error';
          }
          console.error('Failed to load officers. Status:', res.status, 'Response:', errorText);
          document.querySelector('#staffTable tbody').innerHTML =
            `<tr><td colspan="5" class="error-row">Failed to load staff accounts. (Error ${res.status})</td></tr>`;
          return;
        }

        const body = await res.json();
        const staff = body.data || [];

        if (staff.length === 0) {
          document.querySelector('#staffTable tbody').innerHTML =
            '<tr><td colspan="5" class="empty-row">No staff accounts yet.</td></tr>';
          return;
        }

        const rows = staff.map(s => `
          <tr>
            <td>${escapeHtml(s.fullname || '-')}</td>
            <td>${escapeHtml(s.username || '-')}</td>
            <td>${escapeHtml(s.email || '-')}</td>
            <td>${escapeHtml(s.contact || '-')}</td>
            <td>
              <button class="btn btn-change-pw" data-id="${s.id}" data-email="${escapeHtml(s.email)}">Change Password</button>
              <button class="btn btn-delete" data-id="${s.id}" data-name="${escapeHtml(s.fullname)}">Delete</button>
            </td>
          </tr>
        `).join('');

        document.querySelector('#staffTable tbody').innerHTML = rows;
        attachEventHandlers();
      } catch (err) {
        console.error('Load staff error:', err);
        document.querySelector('#staffTable tbody').innerHTML =
          '<tr><td colspan="5" class="error-row">Network error.</td></tr>';
      }
    }

    function attachEventHandlers() {
      // Delete button handlers
      document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
          const id = this.getAttribute('data-id');
          const name = this.getAttribute('data-name');
          showConfirmDeleteModal(id, name);
        });
      });

      // Change password button handlers
      document.querySelectorAll('.btn-change-pw').forEach(btn => {
        btn.addEventListener('click', function() {
          const id = this.getAttribute('data-id');
          const email = this.getAttribute('data-email');
          showChangePasswordModal(id, email);
        });
      });
    }

    async function deleteStaff() {
      if (!STATE.selectedDeleteId) {
        console.warn('No selected staff ID');
        return;
      }

      const csrfToken = getCsrfToken();

      try {
        const res = await fetch(getOfficerUrl(STATE.selectedDeleteId), {
          method: 'DELETE',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          credentials: 'same-origin'
        });

        if (res.ok) {
          hideConfirmDeleteModal();
          showSuccessModal('Account Deleted', `Account for ${STATE.selectedDeleteName} has been removed.`);
          loadStaff();
        } else {
          const errorText = await res.text();
          console.error('Delete failed:', res.status, errorText);
          alert('Failed to delete account. Please try again.');
        }
      } catch (err) {
        console.error('Delete error:', err);
        alert('Error deleting account');
      }
    }

    async function updatePassword() {
      const newPassword = document.getElementById('newPasswordInput').value;

      if (!newPassword.trim()) {
        alert('Please enter a password');
        return;
      }

      const csrfToken = getCsrfToken();

      try {
        const res = await fetch(getPasswordUrl(STATE.selectedUserId), {
          method: 'PUT',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          credentials: 'same-origin',
          body: JSON.stringify({ password: newPassword })
        });

        if (res.ok) {
          hideChangePasswordModal();
          showSuccessModal('Password Updated', `Password for ${STATE.selectedUserEmail} has been changed successfully.`);
          loadStaff();
        } else {
          const errorText = await res.text();
          console.error('Password update failed:', res.status, errorText);
          alert('Failed to update password. Please try again.');
        }
      } catch (err) {
        console.error('Password update error:', err);
        alert('Error updating password');
      }
    }

    // ============================================================================
    // EVENT LISTENERS
    // ============================================================================
    document.getElementById('confirmYes').addEventListener('click', deleteStaff);
    document.getElementById('savePasswordBtn').addEventListener('click', updatePassword);

    document.getElementById('logoutBtn').addEventListener('click', async () => {
      const csrfToken = getCsrfToken();

      try {
        await fetch(CONFIG.logoutUrl, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
      } catch (err) {
        console.error('Logout error:', err);
      }

      window.location.replace('/loginadmin');
    });

    window.addEventListener('pageshow', (event) => {
      if (event.persisted) {
        window.location.reload();
      }
    });

    // ============================================================================
    // INITIALIZATION
    // ============================================================================
    document.addEventListener('DOMContentLoaded', () => {
      loadStaff();
      setInterval(loadStaff, CONFIG.refreshInterval);
    });
  </script>
</body>
</html>
