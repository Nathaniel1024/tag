<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <title>Registration Requests - DIGIBARANGAY</title>
  <link rel="icon" type="image/png" href="{{ asset('img/logo_zed.png') }}" />
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
  <style>
    .admin-dashboard { position: relative; }
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
    .adm-layout { position: relative; z-index: 1; }
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
        <a class="active" href="/resident-requests"><span class="ico">📝</span><span>Registration Requests</span></a>
        <a href="/rest-acc"><span class="ico">🔐</span><span>Resident Accounts</span></a>
        @if ($isAdmin)
          <a href="/dashboard"><span class="ico">🧭</span><span>Admin Dashboard</span></a>
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
        <div class="adm-title">Registration Requests</div>
        <div class="adm-subtitle">Review resident applications before allowing login access</div>

        <div class="adm-card" style="padding:1rem">
          <div class="adm-toolbar">
            <div class="search" aria-label="Search">
              <span style="opacity:.7">🔎</span>
              <input id="q" type="text" placeholder="Search name, email, or username" />
            </div>
            <select id="statusFilter" aria-label="Filter by status">
              <option value="">All</option>
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="declined">Declined</option>
            </select>
          </div>

          <div class="adm-table-wrap">
            <table class="adm-table" aria-label="Registration requests table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Username</th>
                  <th>Status</th>
                  <th>Submitted</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="requestRows">
                <tr><td colspan="6" style="padding:1rem;color:#6b7280">Loading requests...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>
    </main>
  </div>

  <div id="requestModal" class="modal-overlay" hidden>
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="requestTitle" style="max-width:760px;">
      <button class="modal-close" id="requestClose" aria-label="Close">✕</button>
      <div class="modal-header"><h2 id="requestTitle">Registration Request</h2></div>
      <div class="modal-body">
        <div id="requestMeta" class="muted" style="margin-bottom:1rem"></div>
        <div style="display:grid;grid-template-columns:1fr 280px;gap:1rem;align-items:start;">
          <div>
            <div id="requestDetails" style="display:grid;gap:.6rem;"></div>
            <div id="decisionReasonWrap" style="margin-top:1rem;display:none;">
              <label style="display:block;font-weight:600;margin-bottom:.4rem;">Decline reason</label>
              <textarea id="decisionReason" rows="3" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;"></textarea>
            </div>
          </div>
          <div>
            <div style="font-weight:700;margin-bottom:.5rem;">Uploaded Image</div>
            <img id="requestImage" alt="Uploaded image" style="width:100%;max-height:280px;object-fit:contain;border-radius:12px;border:1px solid #e5e7eb;background:#fff;" />
            <div id="requestImageEmpty" class="muted" style="margin-top:.5rem;">No image uploaded.</div>
          </div>
        </div>
        <div class="form-actions" style="margin-top:1.2rem;justify-content:flex-end;gap:.75rem;">
          <button class="btn" id="declineBtn" type="button">Decline</button>
          <button class="btn primary" id="approveBtn" type="button">Approve</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const rowsEl = document.getElementById('requestRows');
    const qEl = document.getElementById('q');
    const statusFilterEl = document.getElementById('statusFilter');
    const requestModal = document.getElementById('requestModal');
    const requestClose = document.getElementById('requestClose');
    const requestTitle = document.getElementById('requestTitle');
    const requestMeta = document.getElementById('requestMeta');
    const requestDetails = document.getElementById('requestDetails');
    const requestImage = document.getElementById('requestImage');
    const requestImageEmpty = document.getElementById('requestImageEmpty');
    const declineBtn = document.getElementById('declineBtn');
    const approveBtn = document.getElementById('approveBtn');
    const decisionReasonWrap = document.getElementById('decisionReasonWrap');
    const decisionReason = document.getElementById('decisionReason');
    let allRequests = [];
    let selectedRequest = null;

    function normalize(value) {
      return String(value || '').trim().toLowerCase();
    }

    function badge(status) {
      const s = normalize(status);
      if (s === 'approved') return '<span class="badge approved">Approved</span>';
      if (s === 'declined') return '<span class="badge rejected">Declined</span>';
      return '<span class="badge pending">Pending</span>';
    }

    function escapeHtml(value) {
      return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
    }

    async function loadRequests() {
      const res = await fetch('/resident-registration-requests', {
        headers: { 'Accept': 'application/json' },
        credentials: 'same-origin'
      });
      if (!res.ok) throw new Error('Failed to load requests.');
      const body = await res.json();
      allRequests = Array.isArray(body.data) ? body.data : [];
      renderRequests();
    }

    function renderRequests() {
      const q = normalize(qEl.value);
      const statusFilter = normalize(statusFilterEl.value);
      const filtered = allRequests.filter((item) => {
        const haystack = normalize([item.fullname, item.email, item.username].filter(Boolean).join(' '));
        const matchesQ = !q || haystack.includes(q);
        const matchesStatus = !statusFilter || normalize(item.status) === statusFilter;
        return matchesQ && matchesStatus;
      });

      if (!filtered.length) {
        rowsEl.innerHTML = '<tr><td colspan="6" style="padding:1rem;color:#6b7280">No registration requests found.</td></tr>';
        return;
      }

      rowsEl.innerHTML = filtered.map((item) => {
        const submitted = item.created_at || '-';
        return '<tr>'
          + '<td>' + escapeHtml(item.fullname || '-') + '</td>'
          + '<td>' + escapeHtml(item.email || '-') + '</td>'
          + '<td>' + escapeHtml(item.username || '-') + '</td>'
          + '<td>' + badge(item.status) + '</td>'
          + '<td>' + escapeHtml(submitted) + '</td>'
          + '<td><button class="btn-mini view" type="button" data-id="' + item.id + '">View</button></td>'
          + '</tr>';
      }).join('');
    }

    function openModal(item) {
      selectedRequest = item;
      requestTitle.textContent = item.fullname || 'Registration Request';
      requestMeta.textContent = 'Status: ' + String(item.status || 'pending').toUpperCase() + ' • Submitted: ' + String(item.created_at || '-');
      requestDetails.innerHTML = [
        ['Full name', item.fullname || '-'],
        ['First name', item.first_name || '-'],
        ['Middle name', item.middle_name || '-'],
        ['Last name', item.last_name || '-'],
        ['Username', item.username || '-'],
        ['Email', item.email || '-'],
        ['Contact', item.contact || '-'],
        ['Age', item.age || '-'],
        ['Address', item.address || '-'],
        ['Reviewed by', item.reviewed_by || '-'],
        ['Reviewed at', item.reviewed_at || '-'],
        ['Reason', item.decision_reason || '-'],
      ].map(([label, value]) => '<div><strong>' + escapeHtml(label) + ':</strong> <span>' + escapeHtml(value) + '</span></div>').join('');

      if (item.has_image) {
        requestImage.src = item.image_url;
        requestImage.hidden = false;
        requestImageEmpty.hidden = true;
      } else {
        requestImage.src = '';
        requestImage.hidden = true;
        requestImageEmpty.hidden = false;
      }

      const pending = normalize(item.status) === 'pending';
      decisionReasonWrap.style.display = pending ? 'block' : 'none';
      declineBtn.disabled = !pending;
      approveBtn.disabled = !pending;

      requestModal.hidden = false;
      requestModal.classList.add('open');
    }

    function closeModal() {
      requestModal.hidden = true;
      requestModal.classList.remove('open');
      selectedRequest = null;
      decisionReason.value = '';
    }

    rowsEl.addEventListener('click', (event) => {
      const button = event.target.closest('button[data-id]');
      if (!button) return;
      const item = allRequests.find((entry) => String(entry.id) === String(button.dataset.id));
      if (item) openModal(item);
    });

    requestClose.addEventListener('click', closeModal);
    requestModal.addEventListener('click', (event) => {
      if (event.target === requestModal) closeModal();
    });

    async function postDecision(action, payload = {}) {
      if (!selectedRequest) return;
      const res = await fetch('/resident-registration-requests/' + encodeURIComponent(selectedRequest.id) + '/' + action, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(payload)
      });

      const body = await res.json().catch(() => ({}));
      if (!res.ok) {
        throw new Error(body.message || 'Action failed.');
      }
      await loadRequests();
      closeModal();
    }

    approveBtn.addEventListener('click', async () => {
      try {
        await postDecision('approve');
      } catch (error) {
        alert(error.message || 'Unable to approve request.');
      }
    });

    declineBtn.addEventListener('click', async () => {
      try {
        const reason = prompt('Optional decline reason:', decisionReason.value || '');
        await postDecision('decline', { reason: reason || '' });
      } catch (error) {
        alert(error.message || 'Unable to decline request.');
      }
    });

    qEl.addEventListener('input', renderRequests);
    statusFilterEl.addEventListener('change', renderRequests);

    document.getElementById('adminLogout').addEventListener('click', async () => {
      try {
        await fetch('/loginadmin/logout', {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF,
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
      } catch (error) {
        console.error('Logout error', error);
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
      admMenuToggle.addEventListener('click', () => setMobileMenu(!document.body.classList.contains('adm-menu-open')));
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

    loadRequests().catch((error) => {
      console.error(error);
      rowsEl.innerHTML = '<tr><td colspan="6" style="padding:1rem;color:#b91c1c">Failed to load requests.</td></tr>';
    });
  </script>
</body>
</html>
