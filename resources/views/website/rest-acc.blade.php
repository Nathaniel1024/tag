<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>Resident Accounts - DIGIBARANGAY</title>
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin: 1rem 0 1.25rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, .9);
            border: 1px solid rgba(15, 23, 42, .08);
            border-radius: 16px;
            padding: 1rem 1.1rem;
            box-shadow: 0 12px 28px rgba(15, 23, 42, .05);
        }

        .stat-card small {
            display: block;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: .35rem;
        }

        .stat-card strong {
            font-size: 1.6rem;
            color: #0f172a;
        }

        .request-name {
            font-weight: 700;
            color: #0f172a;
        }

        .request-meta {
            color: #64748b;
            font-size: .92rem;
        }

        .request-table th,
        .request-table td {
            vertical-align: middle;
        }

        .request-table td:nth-child(1) {
            min-width: 220px;
        }

        .request-table td:nth-child(2),
        .request-table td:nth-child(3),
        .request-table td:nth-child(4),
        .request-table td:nth-child(5) {
            white-space: nowrap;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) 280px;
            gap: 1rem;
            align-items: start;
        }

        .detail-list {
            display: grid;
            gap: .6rem;
        }

        .detail-item {
            padding: .72rem .8rem;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: #fff;
        }

        .detail-item strong {
            display: block;
            font-size: .83rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6b7280;
            margin-bottom: .2rem;
        }

        .detail-image {
            width: 100%;
            max-height: 300px;
            object-fit: contain;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            background: #fff;
        }

        .btn-reset {
            background: #0f766e;
            color: #fff;
            border: 1px solid #0f766e;
        }

        .btn-reset:hover {
            background: #115e59;
            border-color: #115e59;
        }

        #toastHost {
            position: fixed;
            top: 16px;
            right: 16px;
            z-index: 120;
            display: flex;
            flex-direction: column;
            gap: 8px;
            pointer-events: none;
        }

        #debugBar {
            position: fixed;
            left: 16px;
            bottom: 16px;
            z-index: 120;
            max-width: min(92vw, 520px);
            padding: .6rem .8rem;
            border-radius: 10px;
            background: rgba(17, 24, 39, .92);
            color: #fff;
            font-size: .82rem;
            line-height: 1.4;
            box-shadow: 0 12px 30px rgba(2, 6, 23, .22);
            white-space: pre-wrap;
            pointer-events: none;
        }

        @media (max-width: 900px) {

            .stats-grid,
            .detail-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body class="admin-dashboard">
    <div id="toastHost" aria-live="polite" aria-atomic="true"></div>
    <div id="debugBar" hidden></div>
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
                <a href="/resident"><span class="ico">👥</span><span>Resident Records</span></a>
                <a class="active" href="/rest-acc"><span class="ico">🔐</span><span>Resident Accounts</span></a>
                @if ($isAdmin)
                    <a href="/dashboard"><span class="ico">🧭</span><span>Admin Dashboard</span></a>
                @endif
            </nav>

            <div class="adm-sidebar-footer">
                <button class="adm-logout" type="button" id="adminLogout">
                    <span class="ico">&#9097;</span><span>Logout</span>
                </button>
            </div>
        </aside>

        <main class="adm-main">
            <header class="adm-topbar">
                <button class="adm-menu-toggle" id="admMenuToggle" type="button" aria-label="Toggle menu"
                    aria-expanded="false" aria-controls="admSidebarNav">
                    <span class="bars" aria-hidden="true"><span></span><span></span><span></span></span>
                </button>
                <div class="role">
                    <strong>{{ session('admin_name', 'CHAIRMAN') }}</strong>
                    <span>{{ $isAdmin ? 'Barangay Admin' : 'Barangay Official' }}</span>
                </div>
            </header>

            <section class="adm-content">
                <div>
                    <div class="adm-title">Resident Account Requests</div>
                    <div class="adm-subtitle">Review each resident application, inspect the details, and approve or
                        decline access.</div>
                </div>

                <div class="stats-grid" aria-label="Request summary">
                    <div class="stat-card">
                        <small>Pending</small>
                        <strong id="pendingCount">0</strong>
                    </div>
                    <div class="stat-card">
                        <small>Approved</small>
                        <strong id="approvedCount">0</strong>
                    </div>
                    <div class="stat-card">
                        <small>Declined</small>
                        <strong id="declinedCount">0</strong>
                    </div>
                </div>

                <div class="adm-card" style="padding:1rem">
                    <div class="adm-toolbar">
                        <div class="search" aria-label="Search">
                            <span style="opacity:.7">S</span>
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
                        <table class="adm-table request-table" aria-label="Resident account requests table">
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
                                <tr>
                                    <td colspan="6" style="padding:1rem;color:#6b7280">Loading requests...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <div id="resetPasswordModal" class="modal-overlay" hidden>
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="resetPasswordTitle"
            style="max-width:560px;">
            <button class="modal-close" id="resetPasswordClose" aria-label="Close">x</button>
            <div class="modal-header">
                <h2 id="resetPasswordTitle">Reset Resident Password</h2>
            </div>
            <div class="modal-body">
                <div id="resetPasswordMeta" class="muted" style="margin-bottom:1rem"></div>
                <div style="display:grid;gap:.75rem;">
                    <div>
                        <label style="display:block;font-weight:600;margin-bottom:.35rem;">New password</label>
                        <input id="resetPasswordInput" type="password"
                            style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;" />
                    </div>
                    <div>
                        <label style="display:block;font-weight:600;margin-bottom:.35rem;">Confirm password</label>
                        <input id="resetPasswordConfirmInput" type="password"
                            style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;" />
                    </div>
                    <div id="resetPasswordError" style="display:none;color:#b91c1c;font-size:.92rem;"></div>
                </div>
                <div class="form-actions" style="margin-top:1.2rem;justify-content:flex-end;gap:.75rem;">
                    <button class="btn" id="resetPasswordCancel" type="button">Cancel</button>
                    <button class="btn primary" id="resetPasswordSave" type="button">Save Password</button>
                </div>
            </div>
        </div>
    </div>

    <div id="requestModal" class="modal-overlay" hidden>
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="requestTitle" style="max-width:860px;">
            <button class="modal-close" id="requestClose" aria-label="Close">x</button>
            <div class="modal-header">
                <h2 id="requestTitle">Resident Account Request</h2>
            </div>
            <div class="modal-body">
                <div id="requestMeta" class="muted" style="margin-bottom:1rem"></div>
                <div class="detail-grid">
                    <div>
                        <div id="requestDetails" class="detail-list"></div>
                        <div id="decisionReasonWrap" style="margin-top:1rem;display:none;">
                            <label style="display:block;font-weight:600;margin-bottom:.4rem;">Decline reason</label>
                            <textarea id="decisionReason" rows="3"
                                style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;"></textarea>
                        </div>
                    </div>
                    <div>
                        <div style="font-weight:700;margin-bottom:.5rem;">Uploaded Image</div>
                        <img id="requestImage" class="detail-image" alt="Uploaded image" hidden />
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
        const resetPasswordModal = document.getElementById('resetPasswordModal');
        const resetPasswordClose = document.getElementById('resetPasswordClose');
        const resetPasswordCancel = document.getElementById('resetPasswordCancel');
        const resetPasswordInput = document.getElementById('resetPasswordInput');
        const resetPasswordConfirmInput = document.getElementById('resetPasswordConfirmInput');
        const resetPasswordMeta = document.getElementById('resetPasswordMeta');
        const resetPasswordError = document.getElementById('resetPasswordError');
        const resetPasswordSave = document.getElementById('resetPasswordSave');
        const pendingCount = document.getElementById('pendingCount');
        const approvedCount = document.getElementById('approvedCount');
        const declinedCount = document.getElementById('declinedCount');
        const debugBar = document.getElementById('debugBar');

        let allRequests = [];
        let selectedRequest = null;
        let selectedResetRequest = null;
        let lastRenderReason = 'initial';

        function normalize(value) {
            return String(value || '').trim().toLowerCase();
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function showToast(message, type = 'info') {
            const host = document.getElementById('toastHost');
            if (!host) return;

            const toast = document.createElement('div');
            const tone = type === 'error' ? {
                background: '#fee2e2',
                border: '#fecaca',
                color: '#991b1b',
            } : type === 'success' ? {
                background: '#dcfce7',
                border: '#bbf7d0',
                color: '#166534',
            } : {
                background: '#111827',
                border: '#1f2937',
                color: '#fff',
            };

            toast.style.pointerEvents = 'none';
            toast.style.minWidth = '240px';
            toast.style.maxWidth = '360px';
            toast.style.padding = '.75rem .9rem';
            toast.style.borderRadius = '12px';
            toast.style.boxShadow = '0 12px 30px rgba(2,6,23,.22)';
            toast.style.fontSize = '.92rem';
            toast.style.lineHeight = '1.35';
            toast.style.background = tone.background;
            toast.style.border = '1px solid ' + tone.border;
            toast.style.color = tone.color;
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-8px)';
            toast.style.transition = 'all .2s ease';
            toast.textContent = message;
            host.appendChild(toast);

            requestAnimationFrame(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
            });

            const duration = type === 'error' ? 3600 : 2200;
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-8px)';
                setTimeout(() => toast.remove(), 240);
            }, duration);
        }

        function setDebug(message) {
            if (!debugBar) return;
            debugBar.hidden = !message;
            debugBar.textContent = message || '';
        }

        function badge(status) {
            const s = normalize(status);
            if (s === 'approved') return '<span class="badge approved">Approved</span>';
            if (s === 'declined') return '<span class="badge rejected">Declined</span>';
            return '<span class="badge pending">Pending</span>';
        }

        function updateSummary() {
            const counts = allRequests.reduce((acc, item) => {
                const status = normalize(item.status) || 'pending';
                if (status === 'approved') acc.approved += 1;
                else if (status === 'declined') acc.declined += 1;
                else acc.pending += 1;
                return acc;
            }, {
                pending: 0,
                approved: 0,
                declined: 0
            });

            pendingCount.textContent = counts.pending;
            approvedCount.textContent = counts.approved;
            declinedCount.textContent = counts.declined;
        }

        async function loadRequests() {
            setDebug('Loading resident requests...');
            const res = await fetch('/resident-registration-requests', {
                headers: {
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            if (!res.ok) {
                setDebug('Load failed: HTTP ' + res.status);
                throw new Error('Failed to load requests.');
            }

            const body = await res.json();
            allRequests = Array.isArray(body.data) ? body.data : [];
            updateSummary();
            setDebug('Loaded ' + allRequests.length + ' request(s).');
            renderRequests();
        }

        function renderRequests() {
            const q = normalize(qEl.value);
            const statusFilter = normalize(statusFilterEl.value);
            const filtered = allRequests.filter((item) => {
                const haystack = normalize([item.fullname, item.email, item.username].filter(Boolean).join(' '));
                const matchesQuery = !q || haystack.includes(q);
                const matchesStatus = !statusFilter || normalize(item.status) === statusFilter;
                return matchesQuery && matchesStatus;
            });

            if (!filtered.length) {
                lastRenderReason = 'no results for q="' + q + '" status="' + statusFilter + '" total=' + allRequests.length;
                rowsEl.innerHTML =
                    '<tr><td colspan="6" style="padding:1rem;color:#6b7280">No resident requests found.</td></tr>';
                return;
            }

            lastRenderReason = 'rendered ' + filtered.length + '/' + allRequests.length;
            rowsEl.innerHTML = filtered.map((item) => {
                return '<tr>' +
                    '<td><div class="request-name">' + escapeHtml(item.fullname || '-') + '</div>' +
                    '<div class="request-meta">' + escapeHtml([item.first_name, item.middle_name, item.last_name]
                        .filter(Boolean).join(' ')) + '</div></td>' +
                    '<td>' + escapeHtml(item.email || '-') + '</td>' +
                    '<td>' + escapeHtml(item.username || '-') + '</td>' +
                    '<td>' + badge(item.status) + '</td>' +
                    '<td>' + escapeHtml(item.created_at || '-') + '</td>' +
                    '<td><div class="actions" style="gap:.35rem;flex-wrap:wrap;">' +
                    '<button class="btn-mini view" type="button" data-action="view" data-id="' + item.id + '">View</button>' +
                    '<button class="btn-mini btn-reset" type="button" data-action="reset-password" data-id="' + item.id + '"' +
                    (normalize(item.status) === 'approved' ? '' : ' disabled') +
                    '>Reset Password</button>' +
                    '</div></td>' +
                    '</tr>';
            }).join('');
        }

        function renderDetails(item) {
            const rows = [
                ['Full name', item.fullname || '-'],
                ['First name', item.first_name || '-'],
                ['Middle name', item.middle_name || '-'],
                ['Last name', item.last_name || '-'],
                ['Username', item.username || '-'],
                ['Email', item.email || '-'],
                ['Contact', item.contact || '-'],
                ['Age', item.age || '-'],
                ['Address', item.address || '-'],
                ['Status', String(item.status || '-').toUpperCase()],
                ['Reviewed by', item.reviewed_by || '-'],
                ['Reviewed at', item.reviewed_at || '-'],
                ['Decline reason', item.decision_reason || '-'],
            ];

            requestDetails.innerHTML = rows.map(([label, value]) => (
                '<div class="detail-item"><strong>' + escapeHtml(label) + '</strong><div>' + escapeHtml(value) +
                '</div></div>'
            )).join('');

            if (item.has_image) {
                requestImage.src = item.image_url;
                requestImage.hidden = false;
                requestImageEmpty.hidden = true;
            } else {
                requestImage.src = '';
                requestImage.hidden = true;
                requestImageEmpty.hidden = false;
            }
        }

        function renderLoadingDetails(message) {
            requestDetails.innerHTML = '<div class="detail-item"><strong>Loading</strong><div>' +
                escapeHtml(message || 'Loading request details...') + '</div></div>';
            requestImage.src = '';
            requestImage.hidden = true;
            requestImageEmpty.hidden = false;
        }

        async function fetchRequestById(id) {
            const res = await fetch('/resident-registration-requests/' + encodeURIComponent(id), {
                headers: {
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            if (!res.ok) return null;

            const body = await res.json().catch(() => ({}));
            return body.data || null;
        }

        async function openModal(itemOrId) {
            try {
                let item = itemOrId;
                if (!item || typeof item !== 'object') {
                    const id = itemOrId;
                    if (id == null || id === '') {
                        alert('Request not found.');
                        return;
                    }

                    requestModal.hidden = false;
                    requestModal.classList.add('open');
                    requestTitle.textContent = 'Resident Account Request';
                    requestMeta.textContent = 'Loading request details...';
                    renderLoadingDetails();
                    showToast('Loading request details...', 'info');
                    setDebug('Opening modal for id=' + id + ' with list size=' + allRequests.length);

                    item = await fetchRequestById(id);
                    if (!item) {
                        renderLoadingDetails('Request not found.');
                        requestMeta.textContent = 'Unable to load this request.';
                        showToast('Request not found.', 'error');
                        setDebug('Request ' + id + ' was not returned by detail fetch.');
                        return;
                    }
                }

                selectedRequest = item;
                requestTitle.textContent = item.fullname || 'Resident Account Request';
                requestMeta.textContent = 'Status: ' + String(item.status || 'pending').toUpperCase() + ' | Submitted: ' +
                    String(item.created_at || '-');
                renderDetails(item);

                const pending = normalize(item.status) === 'pending';
                decisionReasonWrap.style.display = pending ? 'block' : 'none';
                declineBtn.disabled = !pending;
                approveBtn.disabled = !pending;

                requestModal.hidden = false;
                requestModal.classList.add('open');
                setDebug('Modal open for id=' + String(item.id) + ' status=' + String(item.status || '-'));
            } catch (error) {
                console.error('Unable to open request modal', error);
                requestModal.hidden = false;
                requestModal.classList.add('open');
                requestTitle.textContent = 'Resident Account Request';
                requestMeta.textContent = 'Unable to load this request.';
                renderLoadingDetails(error.message || 'Unable to load request details.');
                showToast(error.message || 'Unable to load request details.', 'error');
                setDebug('Modal error: ' + (error.message || 'unknown error'));
            }
        }

        function openResetPasswordModal(item) {
            if (!item) return;
            selectedResetRequest = item;
            resetPasswordMeta.textContent = 'Set a new password for ' + String(item.fullname || item.email || 'this resident') +
                ' (' + String(item.email || '-') + ').';
            resetPasswordInput.value = '';
            resetPasswordConfirmInput.value = '';
            resetPasswordError.style.display = 'none';
            resetPasswordModal.hidden = false;
            resetPasswordModal.classList.add('open');
            resetPasswordInput.focus();
        }

        function closeModal() {
            requestModal.hidden = true;
            requestModal.classList.remove('open');
            selectedRequest = null;
            decisionReason.value = '';
        }

        function closeResetPasswordModal() {
            resetPasswordModal.hidden = true;
            resetPasswordModal.classList.remove('open');
            selectedResetRequest = null;
            resetPasswordError.style.display = 'none';
            resetPasswordInput.value = '';
            resetPasswordConfirmInput.value = '';
        }

        rowsEl.addEventListener('click', (event) => {
            const button = event.target.closest('button[data-id]');
            if (!button) return;

            const action = button.dataset.action || 'view';
            const item = allRequests.find((entry) => String(entry.id) === String(button.dataset.id));

            if (action === 'reset-password') {
                if (!item) return;
                if (normalize(item.status) !== 'approved') {
                    alert('Approve this account first before resetting the password.');
                    return;
                }
                openResetPasswordModal(item);
                return;
            }

            openModal(item || button.dataset.id);
        });

        requestClose.addEventListener('click', closeModal);
        requestModal.addEventListener('click', (event) => {
            if (event.target === requestModal) closeModal();
        });

        resetPasswordClose.addEventListener('click', closeResetPasswordModal);
        resetPasswordCancel.addEventListener('click', closeResetPasswordModal);
        resetPasswordModal.addEventListener('click', (event) => {
            if (event.target === resetPasswordModal) closeResetPasswordModal();
        });

        async function postDecision(action, payload = {}) {
            if (!selectedRequest) return;

            const res = await fetch('/resident-registration-requests/' + encodeURIComponent(selectedRequest.id) + '/' +
                action, {
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

            showToast(
                action === 'approve' ? 'Request approved.' : 'Request declined.',
                'success'
            );
            setDebug('Action "' + action + '" saved; refreshing list...');
            await loadRequests();
            closeModal();
        }

        async function saveResetPassword() {
            if (!selectedResetRequest) return;

            const password = String(resetPasswordInput.value || '').trim();
            const passwordConfirm = String(resetPasswordConfirmInput.value || '').trim();

            if (!password || password.length < 6) {
                resetPasswordError.textContent = 'Password must be at least 6 characters.';
                resetPasswordError.style.display = 'block';
                return;
            }
            if (password !== passwordConfirm) {
                resetPasswordError.textContent = 'Passwords do not match.';
                resetPasswordError.style.display = 'block';
                return;
            }

            resetPasswordError.style.display = 'none';
            resetPasswordSave.disabled = true;
            resetPasswordSave.textContent = 'Saving...';

            try {
                const response = await fetch('/rest-acc/change-password', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        email: selectedResetRequest.email,
                        password,
                        password_confirmation: passwordConfirm
                    })
                });

                const body = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(body.message || 'Failed to reset password.');
                }

                closeResetPasswordModal();
                showToast(body.message || 'Password reset successfully.', 'success');
            } catch (error) {
                resetPasswordError.textContent = error.message || 'Failed to reset password.';
                resetPasswordError.style.display = 'block';
            } finally {
                resetPasswordSave.disabled = false;
                resetPasswordSave.textContent = 'Save Password';
            }
        }

        resetPasswordSave.addEventListener('click', saveResetPassword);
        resetPasswordConfirmInput.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') saveResetPassword();
        });

        approveBtn.addEventListener('click', async () => {
            try {
                await postDecision('approve');
            } catch (error) {
                showToast(error.message || 'Unable to approve request.', 'error');
            }
        });

        declineBtn.addEventListener('click', async () => {
            try {
                const reason = prompt('Optional decline reason:', decisionReason.value || '');
                await postDecision('decline', {
                    reason: reason || ''
                });
            } catch (error) {
                showToast(error.message || 'Unable to decline request.', 'error');
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
            admMenuToggle.addEventListener('click', () => setMobileMenu(!document.body.classList.contains(
                'adm-menu-open')));
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
            rowsEl.innerHTML =
                '<tr><td colspan="6" style="padding:1rem;color:#b91c1c">Failed to load requests.</td></tr>';
            showToast(error.message || 'Failed to load requests.', 'error');
            setDebug('Initial load error: ' + (error.message || 'unknown error'));
        });
    </script>
</body>

</html>
